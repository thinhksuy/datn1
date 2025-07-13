// ProductCategories.jsx
import { motion } from "framer-motion";
import { useInView } from "react-intersection-observer";
import ProductBox from "./ProductBox";
import { useEffect, useState } from "react";
import { fetchCategories } from "../../api/categoryApi"; // Đường dẫn chính xác

const ProductCategories = () => {
  const { ref, inView } = useInView({ triggerOnce: true, threshold: 0.33 });
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    fetchCategories()
      .then((res) => {
        setCategories(res.data); // Laravel trả danh sách JSON
      })
      .catch((err) => {
        console.error("Lỗi khi gọi API category:", err);
      });
  }, []);

  return (
    <motion.div
      className="product-categories"
      ref={ref}
      initial={{ opacity: 0, y: 30 }}
      animate={inView ? { opacity: 1, y: 0 } : {}}
      transition={{ duration: 0.6, ease: "easeOut" }}
    >
      {categories.map((cat, idx) => (
       <motion.div key={cat.Categories_ID || idx} whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.97 }}>
 <ProductBox
  imgSrc={`http://localhost:8000/${cat.Image}?v=${Date.now()}`}
  altText={cat.Name}
  title={cat.Name}
  description={cat.Description}
  buttonText="Xem thêm"
/>

</motion.div>

      ))}
    </motion.div>
  );
};

export default ProductCategories;
