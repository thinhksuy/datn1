import { Link } from "react-router-dom";
import { useCart } from "../../context/CartContext";

const ProductItem = ({ id, imgSrc, alt, title, priceSale, priceOld, stars }) => {
  const { addToCart } = useCart();

  const handleAddToCart = (e) => {
    e.preventDefault(); // chặn chuyển trang
    const product = { id, imgSrc, title, priceSale };
    addToCart(product);
    console.log("Đã thêm vào giỏ:", product); // 👈 Thêm log test
  };

  return (
    <div className="product-item">
      <div className="product-content">
        <Link to="/productdetail" className="product-link">
          <img src={imgSrc} alt={alt} />
          <div className="product-info">
            <h4>{title}</h4>
            <p className="price-sale">Giá bán: {priceSale}</p>
            <p className="price-old">Giá gốc: {priceOld}</p>
            <div className="stars">{stars}</div>
          </div>
        </Link>
        <div className="cart-overlay">
          <button className="cart-center-btn" onClick={handleAddToCart}>🛒</button>
        </div>
      </div>

      <div className="product-actions">
        <button className="buy-btn">Mua Ngay</button>
      </div>
    </div>
  );
};

export default ProductItem;
