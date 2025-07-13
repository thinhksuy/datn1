import React, { useRef, useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import { fetchProducts } from '../../api/productApi'; // để lấy tổng số trang

const Carousel = ({ page, setPage }) => {
  const trackRef = useRef(null);
  const [lastPage, setLastPage] = useState(1);

  useEffect(() => {
    // Gọi API để lấy last_page từ server
    fetchProducts(1)
      .then((res) => {
        setLastPage(res.data.last_page || 1);
      })
      .catch((err) => console.error("Lỗi khi lấy số trang:", err));
  }, []);

  const scrollLeft = () => {
    if (trackRef.current) {
      trackRef.current.scrollBy({ left: -150, behavior: 'smooth' });
    }
  };

  const scrollRight = () => {
    if (trackRef.current) {
      trackRef.current.scrollBy({ left: 150, behavior: 'smooth' });
    }
  };

  const handlePageClick = (num) => {
    if (num !== page) {
      setPage(num);
    }
  };

  const renderPageNumbers = () => {
    const pages = [];

    const maxVisible = 10;
    const showLast = lastPage > maxVisible;

    const limit = Math.min(maxVisible, lastPage);

    for (let i = 1; i <= limit; i++) {
      pages.push(
        <motion.div
          className={`carousel-item ${page === i ? 'active' : ''}`}
          key={i}
          onClick={() => handlePageClick(i)}
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ delay: i * 0.03 }}
          viewport={{ once: true, amount: 0.5 }}
        >
          {i}
        </motion.div>
      );
    }

    if (showLast) {
      pages.push(
        <div className="carousel-item" key="dots">...</div>,
        <motion.div
          className={`carousel-item ${page === lastPage ? 'active' : ''}`}
          key={lastPage}
          onClick={() => handlePageClick(lastPage)}
        >
          {lastPage}
        </motion.div>
      );
    }

    return pages;
  };

  return (
    <div className="carousel-container">
      <div className="carousel-track" ref={trackRef}>
        <div className="carousel-arrow left" onClick={scrollLeft}>
          <span className="material-symbols-outlined">chevron_left</span>
        </div>

        {renderPageNumbers()}

        <div className="carousel-arrow right" onClick={scrollRight}>
          <span className="material-symbols-outlined">chevron_right</span>
        </div>
      </div>
    </div>
  );
};

export default Carousel;
