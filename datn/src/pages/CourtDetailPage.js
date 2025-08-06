import React, { useEffect, useState } from 'react';
import axios from 'axios';
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

export default function CourtListPage() {
  const [courts, setCourts] = useState([]);
  const [selectedCourt, setSelectedCourt] = useState(null); // sân đang xem chi tiết
  const [bookings, setBookings] = useState([]);

  // New states for date and time range
  const [selectedDate, setSelectedDate] = useState(() => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = (today.getMonth() + 1).toString().padStart(2, '0');
    const dd = today.getDate().toString().padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
  });
  const [activeTab, setActiveTab] = useState('morning');

  useEffect(() => {
    const fetchCourts = async () => {
      try {
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
        setCourts(res.data.data || []);
      } catch (err) {
        console.error('Lỗi khi lấy danh sách sân:', err);
        alert('Không thể tải danh sách sân.');
        setCourts([]);
      }
    };

    fetchCourts();
  }, [selectedDate, activeTab]);

  const openDetail = async (court) => {
    setSelectedCourt(court);

    try {
      const res = await axios.get(`/api/bookings?court_id=${court.Courts_ID}`);
      setBookings(res.data || []);
    } catch (err) {
      console.error('Lỗi khi tải lịch đặt:', err);
      setBookings([]);
    }
  };

  const closeModal = () => {
    setSelectedCourt(null);
    setBookings([]);
  };

  return (
    <>
      <Header />

      <main style={{ maxWidth: 1200, margin: '0 auto', padding: '32px 16px', marginBottom: 100 }}>
        <h1 style={{ marginBottom: 24, fontSize: 28, fontWeight: 700 }}>
          Danh sách sân cầu lông
        </h1>

        {/* Date selector */}
        <label style={{ marginBottom: 16, display: 'block', fontWeight: '600' }}>
          Chọn ngày:{' '}
          <input
            type="date"
            value={selectedDate}
            onChange={(e) => setSelectedDate(e.target.value)}
            min={(() => {
              const today = new Date();
              const yyyy = today.getFullYear();
              const mm = (today.getMonth() + 1).toString().padStart(2, '0');
              const dd = today.getDate().toString().padStart(2, '0');
              return `${yyyy}-${mm}-${dd}`;
            })()}
            style={{ padding: '6px 10px', fontSize: 16, borderRadius: 6, border: '1px solid #ccc' }}
          />
        </label>

        {/* Time range tabs */}
        <div style={{ display: 'flex', gap: 12, marginBottom: 24 }}>
          {Object.keys(TIME_SLOTS).map((slotKey) => (
            <button
              key={slotKey}
              onClick={() => setActiveTab(slotKey)}
              style={{
                padding: '8px 20px',
                borderRadius: 9999,
                fontWeight: 600,
                cursor: 'pointer',
                backgroundColor: activeTab === slotKey ? '#2563eb' : '#e5e7eb',
                color: activeTab === slotKey ? '#fff' : '#374151',
                border: 'none',
                transition: 'all 0.3s ease',
                userSelect: 'none',
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
          <p>Không có sân nào trong hệ thống.</p>
        ) : (
          <div style={{ display: 'flex', flexDirection: 'column', gap: 24 }}>
            {courts.map(court => (
              <div
                key={court.Courts_ID}
                style={{
                  display: 'flex',
                  flexDirection: 'row',
                  border: '1px solid #ddd',
                  borderRadius: 10,
                  overflow: 'hidden',
                  boxShadow: '0 2px 8px rgba(0,0,0,0.08)',
                  background: '#fff',
                  width: '100%',
                }}
              >
                <div style={{ width: '50%', maxHeight: 200 }}>
                  <img
                    src={court.Image}
                    alt={court.Name}
                    style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                  />
                </div>

                <div style={{ width: '50%', padding: '16px 24px' }}>
                  <h2 style={{ fontSize: 20, fontWeight: 600, marginBottom: 8 }}>{court.Name}</h2>
                  <p><strong>📍 Vị trí:</strong> {court.Location}</p>
                  <p><strong>🏸 Loại sân:</strong> {court.Court_type}</p>
                  <p><strong>💵 Giá/giờ:</strong> {court.Price_per_hour?.toLocaleString()}₫</p>

                  <button
                    onClick={() => openDetail(court)}
                    style={{
                      marginTop: 12,
                      background: '#007bff',
                      color: '#fff',
                      padding: '8px 16px',
                      border: 'none',
                      borderRadius: 4,
                      cursor: 'pointer',
                    }}
                  >
                    Xem chi tiết
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}

        {/* Modal */}
        {selectedCourt && (
          <div
            style={{
              position: 'fixed',
              top: 0, left: 0, right: 0, bottom: 0,
              background: 'rgba(0,0,0,0.5)',
              display: 'flex',
              justifyContent: 'center',
              alignItems: 'center',
              zIndex: 999,
            }}
            onClick={closeModal}
          >
            <div
              onClick={(e) => e.stopPropagation()}
              style={{
                background: '#fff',
                borderRadius: 12,
                padding: 24,
                maxWidth: 700,
                width: '90%',
                maxHeight: '90vh',
                overflowY: 'auto',
              }}
            >
              <h2 style={{ fontSize: 24, marginBottom: 16 }}>{selectedCourt.Name}</h2>
              <img
                src={selectedCourt.Image}
                alt={selectedCourt.Name}
                style={{ width: '100%', height: 250, objectFit: 'cover', borderRadius: 8, marginBottom: 16 }}
              />
              <p><strong>🏸 Loại sân:</strong> {selectedCourt.Court_type}</p>
              <p><strong>💵 Giá/giờ:</strong> {selectedCourt.Price_per_hour?.toLocaleString()}₫</p>
              <p><strong>📍 Địa chỉ:</strong> {selectedCourt.Location}</p>

              <div style={{ marginTop: 24 }}>
                <h3 style={{ fontWeight: 600, marginBottom: 8 }}>📅 Lịch đặt sắp tới</h3>
                {bookings.length === 0 ? (
                  <p>Chưa có lịch đặt nào.</p>
                ) : (
                  <ul style={{ paddingLeft: 20 }}>
                    {bookings.map((booking, i) => (
                      <li key={i}>
                        ⏰ {new Date(booking.start_time).toLocaleString()} → {new Date(booking.end_time).toLocaleTimeString()}
                      </li>
                    ))}
                  </ul>
                )}
              </div>

              <button
                onClick={closeModal}
                style={{
                  marginTop: 20,
                  background: '#dc3545',
                  color: '#fff',
                  padding: '8px 16px',
                  border: 'none',
                  borderRadius: 4,
                  cursor: 'pointer',
                }}
              >
                Đóng
              </button>
            </div>
          </div>
        )}
      </main>

      <Footer />
    </>
  );
}
