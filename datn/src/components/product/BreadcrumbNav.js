// BreadcrumbNav.jsx
import React from 'react';
import { Link } from 'react-router-dom';
import { motion } from 'framer-motion';

function BreadcrumbNav() {
  return (
    <motion.nav
      className="breadcrumb-nav"
      initial={{ opacity: 0, x: -40 }}
      animate={{ opacity: 1, x: 0 }}
      transition={{ duration: 0.6, ease: 'easeOut' }}
    >
      <div className="breadcrumb-container">
        <Link to="/">Trang chủ</Link>
        <span className="separator">›</span>
        <Link to="/san-pham">Sản phẩm</Link>
        <span className="separator">›</span>
        <span className="current">Vợt cầu lông</span>
      </div>
    </motion.nav>
  );
}

export default BreadcrumbNav;
