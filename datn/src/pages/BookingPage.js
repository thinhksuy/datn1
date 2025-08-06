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
  const [loadingCourts, setLoadingCourts] = useState(false);
  const [selectedDate, setSelectedDate] = useState(() => {
    const now = new Date();
    const yyyy = now.getFullYear();
    const mm = (now.getMonth() + 1).toString().padStart(2, '0');
    const dd = now.getDate().toString().padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  });
  
  const [activeTab, setActiveTab] = useState(() => {
    const now = new Date();
    const currentHour = now.getHours();

    if (currentHour >= 5 && currentHour < 12) {
      return 'morning';
    }

    if (currentHour >= 12 && currentHour < 18) {
      return 'afternoon';
    }

    if (currentHour >= 18 && currentHour < 24) {
      return 'evening';
    }

    // Default fallback
    return 'morning';
  });

  // Add useEffect to update activeTab on mount to current session
  useEffect(() => {
    const now = new Date();
    const currentHour = now.getHours();

    if (currentHour >= 5 && currentHour < 12) {
      setActiveTab('morning');
    } else if (currentHour >= 12 && currentHour < 18) {
      setActiveTab('afternoon');
    } else if (currentHour >= 18 && currentHour < 24) {
      setActiveTab('evening');
    } else {
      setActiveTab('morning');
    }
  }, []);
  const [selectedSlots, setSelectedSlots] = useState([]);
  const [selectedCourt, setSelectedCourt] = useState(null);
  const [user] = useState(() => {
    try {
      return JSON.parse(localStorage.getItem('user'));
    } catch {
      return null;
    }
  });

  const fetchCourts = useCallback(async () => {
    try {
      setLoadingCourts(true);
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
    } finally {
      setLoadingCourts(false);
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
    fetchBookings();
  }, [fetchCourts, fetchBookings]);

  const isSlotBooked = (courtId, time) => {
    return bookings.some(
      (booking) =>
        booking.Courts_ID === courtId &&
        booking.Booking_date === selectedDate &&
        booking.Start_time === time
    );
  };

  const handleSlotSelection = (courtId, time) => {
    if (!user) {
      alert('Vui lòng đăng nhập để đặt sân!');
      navigate('/login');
      return;
    }
    const slotKey = `${courtId}-${time}`;
    const isSelected = selectedSlots.includes(slotKey);
    
    if (isSelected) {
      setSelectedSlots(prev => prev.filter(slot => slot !== slotKey));
    } else {
      // Check if this slot is consecutive with existing selections
      const existingSlots = selectedSlots.filter(slot => slot.startsWith(courtId));
      if (existingSlots.length === 0) {
        setSelectedSlots(prev => [...prev, slotKey]);
      } else {
        // Check if this slot is adjacent to existing selections
        const existingTimes = existingSlots.map(slot => slot.split('-')[1]);
        const isAdjacent = existingTimes.some(existingTime => {
          const [h1, m1] = existingTime.split(':').map(Number);
          const [h2, m2] = time.split(':').map(Number);
          const diff = (h2 * 60 + m2) - (h1 * 60 + m1);
          return Math.abs(diff) === 60;
        });
        
        if (isAdjacent || existingSlots.length === 0) {
          setSelectedSlots(prev => [...prev, slotKey]);
        }
      }
    }
  };

  const handleBookSelectedSlots = async () => {
    if (!user) {
      alert('Vui lòng đăng nhập để đặt sân!');
      navigate('/login');
      return;
    }

    if (selectedSlots.length === 0) {
      alert('Vui lòng chọn ít nhất một khung giờ!');
      return;
    }

    if (!selectedCourt) {
      alert('Vui lòng chọn sân cần đặt!');
      return;
    }

    // Group slots by court
    const slotsByCourt = {};
    selectedSlots.forEach(slot => {
      const [courtId, time] = slot.split('-');
      if (!slotsByCourt[courtId]) {
        slotsByCourt[courtId] = [];
      }
      slotsByCourt[courtId].push(time);
    });

    // Sort times for each court
    Object.keys(slotsByCourt).forEach(courtId => {
      slotsByCourt[courtId].sort();
    });

    // Create booking for each court
    const bookings = [];
    for (const courtId in slotsByCourt) {
      const times = slotsByCourt[courtId];
      const startTime = times[0];
      const endTime = addOneHour(times[times.length - 1]);
      
      const [h1, m1] = startTime.split(':').map(Number);
      const [h2, m2] = endTime.split(':').map(Number);
      const duration = (h2 + m2 / 60) - (h1 + m1 / 60);
      
      const court = courts.find(c => c.Courts_ID === parseInt(courtId));
      const pricePerHour = court ? court.Price_per_hour : 0;
      const totalPrice = duration * pricePerHour;

      bookings.push({
        Courts_ID: parseInt(courtId),
        User_ID: user.ID || user.id,
        Booking_date: selectedDate,
        Start_time: startTime + ':00',
        End_time: endTime + ':00',
        Duration_hours: Math.ceil(duration),
        Price_per_hour: pricePerHour,
        Total_price: totalPrice,
        Status: true
      });
    }

    try {
      // Save booking data to localStorage with timestamp for 30 minutes expiry
      const bookingData = {
        bookings,
        totalAmount: bookings.reduce((sum, b) => sum + b.Total_price, 0),
        timestamp: Date.now()
      };
      localStorage.setItem('pendingBooking', JSON.stringify(bookingData));

      const token = localStorage.getItem('auth_token');
      
      // Create all bookings
      for (const booking of bookings) {
        await axios.post('/api/court_bookings', booking, {
          headers: {
            Authorization: token ? `Bearer ${token}` : ''
          }
        });
      }
      
      alert(`Đặt ${bookings.length} khung giờ thành công!`);
      setSelectedSlots([]);
      setSelectedCourt(null);
      fetchBookings();
      
      // Redirect to payment page
      navigate('/payment', { 
        state: { 
          bookings: bookings,
          totalAmount: bookings.reduce((sum, b) => sum + b.Total_price, 0)
        } 
      });
      
    } catch (error) {
      console.error('Booking error:', error.response || error.message || error);
      const backendMessage = error.response?.data?.message || 'Đặt sân thất bại!';
      alert(backendMessage);
    }
  };

  const getSelectedSlotsForCourt = (courtId) => {
    if (!courtId) return [];
    return selectedSlots
      .filter(slot => slot.startsWith(courtId.toString()))
      .map(slot => slot.split('-')[1])
      .sort();
  };

  const calculateTotalPrice = () => {
    let total = 0;
    const slotsByCourt = {};
    
    selectedSlots.forEach(slot => {
      const [courtId, time] = slot.split('-');
      if (!slotsByCourt[courtId]) {
        slotsByCourt[courtId] = [];
      }
      slotsByCourt[courtId].push(time);
    });
    
    Object.keys(slotsByCourt).forEach(courtId => {
      const times = slotsByCourt[courtId].sort();
      const startTime = times[0];
      const endTime = addOneHour(times[times.length - 1]);
      
      const [h1, m1] = startTime.split(':').map(Number);
      const [h2, m2] = endTime.split(':').map(Number);
      const duration = (h2 + m2 / 60) - (h1 + m1 / 60);
      
      const court = courts.find(c => c.Courts_ID === parseInt(courtId));
      const pricePerHour = court ? court.Price_per_hour : 0;
      total += duration * pricePerHour;
    });
    
    return total;
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
              padding: '12px 16px',
              fontSize: '1.25rem',
              borderRadius: '12px',
              border: '1.5px solid #3b82f6',
              backgroundColor: '#f0f9ff',
              outline: 'none',
              boxShadow: '0 2px 6px rgba(59, 130, 246, 0.3)',
              transition: 'border-color 0.3s ease, box-shadow 0.3s ease',
              color: '#1e40af',
              fontWeight: '600',
              cursor: 'pointer',
              userSelect: 'none',
            }}
            min={(() => {
              const today = new Date();
              const yyyy = today.getFullYear();
              const mm = (today.getMonth() + 1).toString().padStart(2, '0');
              const dd = today.getDate().toString().padStart(2, '0');
              return `${yyyy}-${mm}-${dd}`;
            })()}
            onFocus={e => {
              e.target.style.borderColor = '#2563eb';
              e.target.style.boxShadow = '0 0 12px rgba(37, 99, 235, 0.8)';
            }}
            onBlur={e => {
              e.target.style.borderColor = '#3b82f6';
              e.target.style.boxShadow = '0 2px 6px rgba(59, 130, 246, 0.3)';
            }}
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
              <p style={{
                marginBottom: '16px',
                fontSize: '1.125rem',
                color: '#059669',
                fontWeight: '700'
              }}><strong>Giá:</strong> {court.Price_per_hour?.toLocaleString()}đ/giờ</p>
              <div style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(5, minmax(0, 1fr))',
                gap: '12px'
              }}>
                {TIME_SLOTS[activeTab].map((time) => {
                  const booked = isSlotBooked(court.Courts_ID, time);
                  const slotKey = `${court.Courts_ID}-${time}`;
                  const isSelected = selectedSlots.includes(slotKey);
                  
                  // Determine if the slot should be disabled due to current time and selected date
                  const now = new Date();
                  const currentDateStr = now.toISOString().split('T')[0];
                  const isToday = selectedDate === currentDateStr;
                  const [slotHour, slotMinute] = time.split(':').map(Number);
                  const slotDateTime = new Date(selectedDate);
                  slotDateTime.setHours(slotHour, slotMinute, 0, 0);
                  const disableDueToTime = isToday && slotDateTime <= now;

                  return (
                    <button
                      key={time}
                      disabled={booked || disableDueToTime}
                      onClick={() => {
                        setSelectedCourt(court);
                        handleSlotSelection(court.Courts_ID, time);
                      }}
                      style={{
                        borderRadius: '8px',
                        border: '1px solid',
                        borderColor: booked || disableDueToTime ? '#dc2626' : isSelected ? '#2563eb' : '#22c55e',
                        padding: '10px 12px',
                        fontSize: '0.875rem',
                        fontWeight: '600',
                        color: '#fff',
                        backgroundColor: booked || disableDueToTime ? '#dc2626' : isSelected ? '#2563eb' : '#22c55e',
                        cursor: booked || disableDueToTime ? 'not-allowed' : 'pointer',
                        transition: 'background-color 0.3s ease',
                        userSelect: 'none',
                        boxShadow: isSelected ? '0 0 0 2px #3b82f6' : 'none'
                      }}
                      onMouseEnter={e => {
                        if (!booked && !disableDueToTime && !isSelected) e.target.style.backgroundColor = '#16a34a';
                      }}
                      onMouseLeave={e => {
                        if (!booked && !disableDueToTime && !isSelected) e.target.style.backgroundColor = '#22c55e';
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

        {selectedSlots.length > 0 && (
          <div style={{
            position: 'fixed',
            bottom: '20px',
            left: '50%',
            transform: 'translateX(-50%)',
            backgroundColor: '#2563eb',
            color: '#fff',
            padding: '16px 24px',
            borderRadius: '12px',
            boxShadow: '0 4px 12px rgba(37, 99, 235, 0.6)',
            display: 'flex',
            alignItems: 'center',
            gap: '16px',
            zIndex: 1000,
            maxWidth: '90%',
            width: '600px',
            justifyContent: 'space-between'
          }}>
            <div>
              <strong>Đã chọn:</strong> {selectedSlots.length} khung giờ - Tổng giá: {calculateTotalPrice().toLocaleString()}đ
            </div>
        <button
          onClick={handleBookSelectedSlots}
          disabled={!user}
          title={!user ? 'Vui lòng đăng nhập để đặt sân' : ''}
          style={{
            backgroundColor: !user ? '#9ca3af' : '#10b981',
            border: 'none',
            borderRadius: '8px',
            padding: '12px 24px',
            fontWeight: '700',
            fontSize: '1rem',
            color: '#fff',
            cursor: !user ? 'not-allowed' : 'pointer',
            transition: 'background-color 0.3s ease'
          }}
          onMouseEnter={e => {
            if (user) e.currentTarget.style.backgroundColor = '#059669';
          }}
          onMouseLeave={e => {
            if (user) e.currentTarget.style.backgroundColor = '#10b981';
          }}
        >
          Đặt sân
        </button>
      </div>
    )}
      </main>
      <Footer />
    </>
  );
}
