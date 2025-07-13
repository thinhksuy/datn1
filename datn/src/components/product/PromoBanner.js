import React from 'react';
import { motion } from 'framer-motion';

const promoVariants = {
  hidden: { opacity: 0, y: 50 },
  visible: (i) => ({
    opacity: 1,
    y: 0,
    transition: {
      delay: i * 0.2,
      duration: 0.5,
      ease: 'easeOut'
    }
  })
};

const PromoBanner = () => {
  const promos = [
    {
      icon: '🔥',
      text: (
        <>
          Giảm <strong>10%</strong> cho đơn từ 1 triệu –{' '}
          <span className="highlight">Mã:</span>{' '}
          <strong className="promo-code">GIAM10</strong>
        </>
      )
    },
    {
      icon: '⚡',
      text: (
        <>
          Flash Sale –{' '}
          <strong className="highlight">Giảm đến 50%</strong>
        </>
      )
    },
    {
      icon: '🎁',
      text: (
        <>
          Mua <strong>2 vợt</strong> tặng{' '}
          <strong className="highlight">túi đựng cao cấp</strong>
        </>
      )
    }
  ];

  return (
    <div className="promo-banner">
      {promos.map((item, i) => (
        <motion.div
          className="promo-item"
          key={i}
          custom={i}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.4 }}
          variants={promoVariants}
          whileHover={{
            scale: 1.05,
            boxShadow: '0 8px 24px rgba(0,0,0,0.15)',
            transition: { duration: 0.3 }
          }}
        >
          <span className="icon">{item.icon}</span>
          <span className="text">{item.text}</span>
        </motion.div>
      ))}
    </div>
  );
};

export default PromoBanner;
