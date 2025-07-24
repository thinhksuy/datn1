import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Header from '../components/home/Header';
import Footer from '../components/home/Footer';

export default function CourtListPage() {
  const [courts, setCourts] = useState([]);
  const [selectedCourt, setSelectedCourt] = useState(null); // sân đang xem chi tiết
  const [bookings, setBookings] = useState([]);

  useEffect(() => {
    axios.get('/api/courts')
      .then(res => setCourts(res.data || []))
      .catch(err => {
        console.error('Lỗi khi lấy danh sách sân:', err);
        alert('Không thể tải danh sách sân.');
      });
  }, []);

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
