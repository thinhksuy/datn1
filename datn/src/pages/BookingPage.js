import React, { useEffect, useState, useCallback } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import Header from '../components/home/Header';
import Footer from '../components/home/Footer';

// Helper to format hour and minute as HH:mm
const formatTime = (hour, minute = 0) => {
  const h = hour.toString().padStart(2, '0');
  const m = minute.toString().padStart(2, '0');
  return `${h}:${m}`;
};

// Generate time slots for morning, afternoon, evening
const TIME_SLOTS = {
  morning: Array.from({ length: 7 }, (_, i) => formatTime(i + 5)), // 05:00 - 11:00
  afternoon: Array.from({ length: 6 }, (_, i) => formatTime(i + 12)), // 12:00 - 17:00
  evening: Array.from({ length: 6 }, (_, i) => formatTime(i + 18)), // 18:00 - 23:00
};

// Helper to add one hour to a time string HH:mm
const addOneHour = (time) => {
  const [h, m] = time.split(':').map(Number);
  const newHour = (h + 1) % 24;
  return formatTime(newHour, m);
};

export default function BookingPage() {
  const navigate = useNavigate();
  const [courts, setCourts] = useState([]);
  const [bookings, setBookings] = useState([]);
  const [selectedDate, setSelectedDate] = useState(() => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = (today.getMonth() + 1).toString().padStart(2, '0');
    const dd = today.getDate().toString().padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  });
  const [activeTab, setActiveTab] = useState('morning');
  const [user] = useState(() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch {
      return null;
    }
  });

  const fetchCourts = useCallback(async () => {
    try {
      // Determine start_time and end_time based on activeTab
      const times = TIME_SLOTS[activeTab];
      const start_time = times[0] + ':00';
      const end_time = addOneHour(times[times.length - 1]) + ':00';

      const res = await axios.get('/api/courts', {
        params: {
          date: selectedDate,
          start_time,
          end_time
        }
      });
      console.log('Courts API response:', res.data);
      setCourts(res.data.data || []);
    } catch (error) {
      console.error('Failed to fetch courts:', error);
    }
  }, [activeTab, selectedDate]);

  const fetchBookings = useCallback(async () => {
    try {
      const res = await axios.get('/api/court_bookings', {
        params: { date: selectedDate }
      });
      setBookings(res.data);
    } catch (error) {
      console.error('Failed to fetch bookings:', error);
    }
  }, [selectedDate]);

  useEffect(() => {
    fetchCourts();
  }, [fetchCourts]);

  useEffect(() => {
    fetchBookings();
  }, [fetchBookings]);

  const isSlotBooked = (courtId, time) => {
    return bookings.some(
      (booking) =>
        booking.Courts_ID === courtId &&
        booking.Booking_date === selectedDate &&
        booking.Start_time === time
    );
  };

  const handleBooking = async (courtId, time) => {
    if (!user) {
      alert('Vui lòng đăng nhập để đặt sân!');
      navigate('/login');
      return;
    }

    const [h1, m1] = time.split(':').map(Number);
    const [h2, m2] = addOneHour(time).split(':').map(Number);
    const duration = (h2 + m2 / 60) - (h1 + m1 / 60);
    const court = courts.find(c => c.Courts_ID === courtId);
    const pricePerHour = court ? court.Price_per_hour : 0;
    const totalPrice = duration * pricePerHour;

    console.log('Booking user:', user);
    const payload = {
      Courts_ID: courtId,
      User_ID: user.ID || user.id,
      Booking_date: selectedDate,
      Start_time: time + ':00',
      End_time: addOneHour(time) + ':00',
      Duration_hours: Math.ceil(duration),
      Price_per_hour: pricePerHour,
      Total_price: totalPrice,
      Status: 'pending'
    };

    try {
      const token = localStorage.getItem('auth_token');
      await axios.post('/api/court_bookings', payload, {
        headers: {
          Authorization: token ? `Bearer ${token}` : ''
        }
      });
      alert('Đặt sân thành công!');
      fetchBookings();
    } catch (error) {
      console.error('Booking error:', error.response || error.message || error);
      const backendMessage = error.response?.data?.message || 'Đặt sân thất bại!';
      // Translate common backend validation messages to Vietnamese
      let message = backendMessage;
      if (backendMessage.includes('The status field must be true or false')) {
        message = 'Trường trạng thái phải là true hoặc false.';
      } else if (backendMessage.includes('The start time field must match the format')) {
        message = 'Trường thời gian bắt đầu phải đúng định dạng HH:mm:ss.';
      } else if (backendMessage.includes('The user id field is required')) {
        message = 'Trường ID người dùng là bắt buộc.';
      }
      alert(message);
    }
  };

  return (
    <>
      <Header />
      <main style={{
        maxWidth: '960px',
        margin: '0 auto',
        padding: '24px',
        fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif",
        backgroundColor: '#f9fafb',
        borderRadius: '12px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.1)'
      }}>
        <h1 style={{
          fontSize: '2.25rem',
          fontWeight: '700',
          marginBottom: '24px',
          color: '#2563eb',
          textAlign: 'center'
        }}>Đặt sân cầu lông</h1>

        <label style={{
          display: 'block',
          marginBottom: '16px',
          fontSize: '1.125rem',
          fontWeight: '600',
          color: '#374151'
        }}>
          Chọn ngày:
          <input
            type="date"
            value={selectedDate}
            onChange={(e) => setSelectedDate(e.target.value)}
            style={{
              marginLeft: '12px',
              padding: '8px 12px',
              fontSize: '1rem',
              borderRadius: '6px',
              border: '1px solid #d1d5db',
              outline: 'none',
              transition: 'border-color 0.3s ease',
            }}
            min={(() => {
              const today = new Date();
              const yyyy = today.getFullYear();
              const mm = (today.getMonth() + 1).toString().padStart(2, '0');
              const dd = today.getDate().toString().padStart(2, '0');
              return `${yyyy}-${mm}-${dd}`;
            })()}
            onFocus={e => e.target.style.borderColor = '#2563eb'}
            onBlur={e => e.target.style.borderColor = '#d1d5db'}
          />
        </label>

        <div style={{
          display: 'flex',
          gap: '12px',
          marginBottom: '24px',
          justifyContent: 'center',
          flexWrap: 'wrap'
        }}>
          {Object.keys(TIME_SLOTS).map((slotKey) => (
            <button
              key={slotKey}
              onClick={() => setActiveTab(slotKey)}
              style={{
                padding: '10px 24px',
                borderRadius: '9999px',
                fontWeight: '600',
                cursor: 'pointer',
                boxShadow: activeTab === slotKey ? '0 4px 12px rgba(37, 99, 235, 0.6)' : 'none',
                backgroundColor: activeTab === slotKey ? '#2563eb' : '#e5e7eb',
                color: activeTab === slotKey ? '#fff' : '#374151',
                border: 'none',
                transition: 'all 0.3s ease',
                userSelect: 'none'
              }}
              onMouseEnter={e => {
                if (activeTab !== slotKey) e.target.style.backgroundColor = '#3b82f6';
                if (activeTab !== slotKey) e.target.style.color = '#fff';
              }}
              onMouseLeave={e => {
                if (activeTab !== slotKey) e.target.style.backgroundColor = '#e5e7eb';
                if (activeTab !== slotKey) e.target.style.color = '#374151';
              }}
            >
                {{
                  morning: 'Buổi sáng',
                  afternoon: 'Buổi chiều',
                  evening: 'Buổi tối'
                }[slotKey]}
            </button>
          ))}
        </div>

        {courts.length === 0 ? (
          <p style={{ textAlign: 'center', color: '#6b7280', fontSize: '1.125rem' }}>Đang tải danh sách sân...</p>
        ) : courts.length === 0 ? (
          <p style={{ textAlign: 'center', color: '#6b7280', fontSize: '1.125rem' }}>Không có sân nào để hiển thị.</p>
        ) : (
          courts.map((court) => (
            <div key={court.Courts_ID} style={{
              border: '1px solid #d1d5db',
              borderRadius: '12px',
              padding: '24px',
              marginBottom: '32px',
              boxShadow: '0 2px 8px rgba(0,0,0,0.1)',
              transition: 'box-shadow 0.3s ease',
            }}
            onMouseEnter={e => e.currentTarget.style.boxShadow = '0 8px 24px rgba(37, 99, 235, 0.4)'}
            onMouseLeave={e => e.currentTarget.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)'}
            >
              <h2 style={{
                fontSize: '1.5rem',
                fontWeight: '700',
                marginBottom: '16px',
                color: '#1e40af'
              }}>{court.Name}</h2>
              {court.Image && (
                <img
                  src={`/uploads/courts/${court.Image}`}
                  alt={court.Name}
                  style={{
                    width: '100%',
                    maxWidth: '400px',
                    height: '200px',
                    objectFit: 'cover',
                    borderRadius: '12px',
                    marginBottom: '16px',
                    display: 'block',
                    marginLeft: 'auto',
                    marginRight: 'auto'
                  }}
                />
              )}
              <p style={{
                marginBottom: '24px',
                fontSize: '1rem',
                color: '#4b5563',
                fontWeight: '600'
              }}><strong>Vị trí:</strong> {court.Location}</p>
              <div style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(5, minmax(0, 1fr))',
                gap: '12px'
              }}>
                {TIME_SLOTS[activeTab].map((time) => {
                  const booked = isSlotBooked(court.Courts_ID, time);
                  return (
                    <button
                      key={time}
                      disabled={booked}
                      onClick={() => handleBooking(court.Courts_ID, time)}
                      style={{
                        borderRadius: '8px',
                        border: '1px solid',
                        borderColor: booked ? '#dc2626' : '#22c55e',
                        padding: '10px 12px',
                        fontSize: '0.875rem',
                        fontWeight: '600',
                        color: booked ? '#fff' : '#fff',
                        backgroundColor: booked ? '#dc2626' : '#22c55e',
                        cursor: booked ? 'not-allowed' : 'pointer',
                        transition: 'background-color 0.3s ease',
                        userSelect: 'none'
                      }}
                      onMouseEnter={e => {
                        if (!booked) e.target.style.backgroundColor = '#16a34a';
                      }}
                      onMouseLeave={e => {
                        if (!booked) e.target.style.backgroundColor = '#22c55e';
                      }}
                    >
                      {time} - {addOneHour(time)}
                    </button>
                  );
                })}
              </div>
            </div>
          ))
        )}
      </main>
      <Footer />
    </>
  );
}
