// // components/cart/CartLeft.jsx
// import CartItem from "./CartItem";

// function CartLeft() {
//   return (
//     <div className="cart-left">
//       <h2>Giỏ Hàng</h2>
//       <p className="sub-heading">Danh sách sản phẩm bạn đã thêm vào giỏ</p>
//       <table className="cart-table">
//         <thead>
//           <tr>
//             <th>Sản phẩm</th>
//             <th>Đơn giá</th>
//             <th>Số lượng</th>
//             <th>Thành tiền</th>
//             <th>Xoá</th>
//           </tr>
//         </thead>
//         <tbody>
//           {Array.from({ length: 5 }).map((_, index) => (
//             <CartItem key={index} />
//           ))}
//         </tbody>
//       </table>
//       <a href="/" className="back-link">← Quay lại trang chủ</a>
//     </div>
//   );
// }

// export default CartLeft;
// components/cart/CartLeft.jsx
import CartItem from "./CartItem";

function CartLeft({ cartItems, setCartItems }) {
  const updateQuantity = (id, amount) => {
    setCartItems((prevItems) =>
      prevItems.map((item) =>
        item.id === id
          ? { ...item, quantity: Math.max(1, item.quantity + amount) }
          : item
      )
    );
  };

  const removeItem = (id) => {
    setCartItems((prevItems) => prevItems.filter((item) => item.id !== id));
  };

  return (
    <div className="cart-left">
      <h2>Giỏ Hàng</h2>
      <p className="sub-heading">Danh sách sản phẩm bạn đã thêm vào giỏ</p>
      <table className="cart-table">
        <thead>
          <tr>
            <th>Sản phẩm</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Xoá</th>
          </tr>
        </thead>
        <tbody>
          {cartItems.map((item) => (
            <CartItem
              key={item.id}
              item={item}
              updateQuantity={updateQuantity}
              removeItem={removeItem}
            />
          ))}
        </tbody>
      </table>
      <a href="/" className="back-link">← Quay lại trang chủ</a>
    </div>
  );
}

export default CartLeft;
