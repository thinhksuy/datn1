// export default function CartItem() {
//   return (
//     <tr>
//       <td className="product-info">
//         <img src="/htm_css/img/product/font-size 18px;.png" alt="" />
//         <div className="product-detail">
//           <p className="product-name">Vợt Cầu Lông Yonex Astrox 100ZZ – Chính Hãng</p>
//           <p>Màu: Xanh | Trọng lượng: 4U</p>
//           <p>SKU: YX100ZZ-BLUE</p>
//           <p className="product-note">Chỉ còn 2 sản phẩm!</p>
//         </div>
//       </td>
//       <td>
//         <span className="new-price">₫1.200.000</span>
//         <br />
//         <span className="old-price">₫1.500.000</span>{" "}
//         <span className="discount">-20%</span>
//       </td>
//       <td className="quantity">
//         <button>-</button>
//         <input type="number" value="1" readOnly />
//         <button>+</button>
//       </td>
//       <td className="new-price">₫1.200.000</td>
//       <td>
//         <button className="delete-btn">🗑️</button>
//       </td>
//     </tr>
//   );
// }
// components/cart/CartItem.jsx

export default function CartItem({ item, updateQuantity, removeItem }) {
  const {
    id,
    name,
    price,
    priceOld,
    quantity,
    image,
    color,
    weight,
    sku,
    note,
  } = item;

  const discount = Math.round(((priceOld - price) / priceOld) * 100);

  return (
    <tr>
      <td className="product-info">
        <img src={image} alt={name} />
        <div className="product-detail">
          <p className="product-name">{name}</p>
          <p>Màu: {color} | Trọng lượng: {weight}</p>
          <p>SKU: {sku}</p>
          <p className="product-note">{note}</p>
        </div>
      </td>
      <td>
        <span className="new-price">₫{price.toLocaleString()}</span>
        <br />
        <span className="old-price">₫{priceOld.toLocaleString()}</span>{" "}
        <span className="discount">-{discount}%</span>
      </td>
      <td className="quantity">
        <button onClick={() => updateQuantity(id, -1)}>-</button>
        <input type="number" value={quantity} readOnly />
        <button onClick={() => updateQuantity(id, 1)}>+</button>
      </td>
      <td className="new-price">₫{(price * quantity).toLocaleString()}</td>
      <td>
        <button className="delete-btn" onClick={() => removeItem(id)}>🗑️</button>
      </td>
    </tr>
  );
}
