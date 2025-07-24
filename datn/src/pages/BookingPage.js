// src/pages/BookingPage.js
import React, { useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';

import Header from "../components/home/Header";
import Footer from "../components/home/Footer";

export default function BookingPage() {
  const [date, setDate] = useState('');
  const [start, setStart] = useState('');
  const [end, setEnd] = useState('');
  const [courts, setCourts] = useState([]);
  const [loading, setLoading] = useState(false);
  const nav = useNavigate();

  const checkAvailability = async () => {
    if (!date || !start || !end) {
      return alert('Vui lòng chọn đủ ngày và giờ.');
    }
    const token = localStorage.getItem('auth_token');
    if (!token) {
      alert('Vui lòng đăng nhập để kiểm tra sân.');
      return nav('/login');
    }

    setLoading(true);
    try {
      const res = await axios.get('/api/courts', {
        params: {
          date,
          start_time: start + ':00',
          end_time: end + ':00'
        },
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
      setCourts(res.data.data || []);
    } catch (err) {
      console.error('Lỗi khi kiểm tra sân trống:', err.response || err.message);
      if (err.response?.status === 401) {
        alert('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại.');
        return nav('/login');
      }
      alert('Có lỗi xảy ra khi kiểm tra sân.');
    } finally {
      setLoading(false);
    }
  };

  const bookCourt = async court => {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      alert('Vui lòng đăng nhập để đặt sân.');
      return nav('/login');
    }

    const [h1, m1] = start.split(':').map(Number);
    const [h2, m2] = end.split(':').map(Number);
    const duration = (h2 + m2 / 60) - (h1 + m1 / 60);
    const total = duration * court.Price_per_hour;

    try {
      await axios.post('/api/court_bookings', {
        Courts_ID: court.Courts_ID,
        Booking_date: date,
        Start_time: start + ':00',
        End_time: end + ':00',
        Duration_hours: duration,
        Price_per_hour: court.Price_per_hour,
        Total_price: total,
        Status: 'pending'
      }, {
        headers: { Authorization: `Bearer ${token}` }
      });

      alert(`Đặt sân "${court.Name}" thành công!`);
      checkAvailability();
    } catch (err) {
      console.error('Lỗi khi đặt sân:', err.response || err.message);
      if (err.response?.status === 401) {
        alert('Phiên đăng nhập hết hạn, vui lòng đăng nhập lại.');
        return nav('/login');
      }
      alert('Đặt sân thất bại.');
    }
  };

  // CSS styles nội bộ
  const styles = {
    container: {
      maxWidth: '800px',
      margin: '0 auto',
      padding: '1rem',
    },
    heading: {
      textAlign: 'center',
      marginBottom: '2rem'
    },
    form: {
      display: 'flex',
      flexDirection: 'column',
      gap: '1rem',
      marginBottom: '2rem'
    },
    input: {
      padding: '0.5rem',
      fontSize: '1rem'
    },
    button: {
      padding: '0.7rem 1.2rem',
      backgroundColor: '#007bff',
      color: 'white',
      border: 'none',
      borderRadius: '4px',
      cursor: 'pointer'
    },
    card: {
      border: '1px solid #ccc',
      borderRadius: '8px',
      padding: '1rem',
      marginBottom: '1rem',
      boxShadow: '0 2px 4px rgba(0,0,0,0.1)'
    },
    courtList: {
      display: 'flex',
      flexDirection: 'column',
      gap: '1rem'
    },
    noCourt: {
      textAlign: 'center',
      color: '#666'
    }
  };

  return (
    <>
      <Header />

      <main style={styles.container}>
        <h1 style={styles.heading}>Đặt Sân Cầu Lông</h1>

        <div style={styles.form}>
          <label>
            Ngày:
            <input
              type="date"
              value={date}
              onChange={e => setDate(e.target.value)}
              style={styles.input}
            />
          </label>

          <label>
            Bắt đầu:
            <input
              type="time"
              value={start}
              onChange={e => setStart(e.target.value)}
              style={styles.input}
            />
          </label>

          <label>
            Kết thúc:
            <input
              type="time"
              value={end}
              onChange={e => setEnd(e.target.value)}
              style={styles.input}
            />
          </label>

          <button
            onClick={checkAvailability}
            disabled={loading}
            style={{ ...styles.button, opacity: loading ? 0.6 : 1 }}
          >
            {loading ? 'Đang kiểm tra…' : 'Kiểm tra sân trống'}
          </button>
        </div>

        {courts.length === 0 ? (
          <p style={styles.noCourt}>
            Chưa có sân trống trong khung giờ này.
          </p>
        ) : (
          <div style={styles.courtList}>
            {courts.map(court => (
              <div key={court.Courts_ID} style={styles.card}>
                <h2>{court.Name}</h2>
                <p><strong>Vị trí:</strong> {court.Location}</p>
                <p><strong>Giá/giờ:</strong> {court.Price_per_hour.toLocaleString()}₫</p>
                <button style={styles.button} onClick={() => bookCourt(court)}>
                  Đặt sân
                </button>
              </div>
            ))}
          </div>
        )}
      </main>

      <Footer />
    </>
  );
}
