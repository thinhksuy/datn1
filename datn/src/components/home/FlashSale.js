import { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";

const FlashSale = ({
  image,
  alt,
  title = "Flash Sale ⚡",
  productName,
  priceSale,
  priceOld,
  discount,
  stars = "★★★★★",
}) => {
  // ⏱️ Thời gian đếm ngược ảo khởi tạo: 5 giờ 
  const [timeLeft, setTimeLeft] = useState({
    hours: 5,
    minutes: 0,
    seconds: 0,
  });

  const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.33 });

  useEffect(() => {
    const interval = setInterval(() => {
      setTimeLeft((prev) => {
        let { hours, minutes, seconds } = prev;

        if (hours === 0 && minutes === 0 && seconds === 0) {
          clearInterval(interval);
          return { hours: 0, minutes: 0, seconds: 0 };
        }

        if (seconds > 0) {
          seconds--;
        } else {
          if (minutes > 0) {
            minutes--;
            seconds = 59;
          } else if (hours > 0) {
            hours--;
            minutes = 59;
            seconds = 59;
          }
        }

        return { hours, minutes, seconds };
      });
    }, 1000);

    return () => clearInterval(interval);
  }, []);

  // Format giờ sang 2 chữ số
  const format = (num) => String(num).padStart(2, "0");

  return (
    <motion.div
      className="flash-sale-wrapper"
      ref={ref}
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.6, ease: "easeOut" }}
    >
      <div className="flash-sale-content">
        <motion.div
          className="flash-sale-image"
          whileHover={{ scale: 1.05 }}
          transition={{ type: "spring", stiffness: 300 }}
        >
          <img src={image} alt={alt} />
        </motion.div>

        <motion.div
          className="flash-sale-info"
          initial={{ opacity: 0, y: 20 }}
          animate={inView ? { opacity: 1, y: 0 } : {}}
          transition={{ delay: 0.2, duration: 0.5 }}
        >
          <motion.h4 className="flash-sale-title">{title}</motion.h4>
          <motion.h3 className="product-name">{productName}</motion.h3>
          <motion.p
            className="price-sale"
            initial={{ scale: 0.8, opacity: 0 }}
            animate={inView ? { scale: 1, opacity: 1 } : {}}
            transition={{ delay: 0.3, duration: 0.4 }}
          >
            Giá bán: {priceSale}
          </motion.p>
          <motion.p
            className="price-old"
            initial={{ opacity: 0, y: 10 }}
            animate={inView ? { opacity: 1, y: 0 } : {}}
            transition={{ delay: 0.4, duration: 0.4 }}
          >
            Giá gốc: {priceOld} <span className="discount">({discount})</span>
          </motion.p>
          <motion.div
            className="stars"
            initial={{ opacity: 0 }}
            animate={inView ? { opacity: 1 } : {}}
            transition={{ delay: 0.5, duration: 0.3 }}
          >
            {stars}
          </motion.div>
          <motion.button
            className="buy-now"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            initial={{ opacity: 0, y: 10 }}
            animate={inView ? { opacity: 1, y: 0 } : {}}
            transition={{ delay: 0.6, duration: 0.4 }}
          >
            Mua Ngay
          </motion.button>
          <motion.div
            className="countdown"
            initial={{ opacity: 0, scale: 0.9 }}
            animate={inView ? { opacity: 1, scale: 1 } : {}}
            transition={{ delay: 0.7, duration: 0.4 }}
          >
            <div className="time-box">{format(timeLeft.hours)}</div>:
            <div className="time-box">{format(timeLeft.minutes)}</div>:
            <div className="time-box">{format(timeLeft.seconds)}</div>
          </motion.div>
        </motion.div>
      </div>
    </motion.div>
  );
};

export default FlashSale;