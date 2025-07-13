import React, { useEffect, useState } from "react";
import { fetchProducts } from "../../api/productApi";

function ProductList({ page, filters }) {
  const [products, setProducts] = useState([]);
  const [meta, setMeta] = useState({});

  useEffect(() => {
    const fetchData = async () => {
      try {
        const res = await fetchProducts(page, filters);
        setProducts(res.data.data); // mảng sản phẩm
        setMeta({
          currentPage: res.data.current_page,
          lastPage: res.data.last_page,
        });
      } catch (err) {
        console.error("Lỗi gọi API:", err);
      }
    };

    fetchData();
  }, [page, filters]);

  return (
    <main className="product-list1">
      {products.length === 0 ? (
        <p style={{ padding: "1rem" }}>Không tìm thấy sản phẩm phù hợp.</p>
      ) : (
        products.map((product) => (
          <div className="product-card1" key={product.Product_ID}>
            <img
              src={`/${product.Image}`}
              alt={product.Name}
              className="product-image1"
            />
            <h3 className="produt-name1">{product.Name}</h3>
            <div className="product-price1">
              {product.Discount_price ? (
                <>
                  <span style={{ color: "red", fontWeight: "bold" }}>
                    {Number(product.Discount_price).toLocaleString("vi-VN")}₫
                  </span>{" "}
                  <del style={{ color: "#999" }}>
                    {Number(product.Price).toLocaleString("vi-VN")}₫
                  </del>
                </>
              ) : (
                <span>{Number(product.Price).toLocaleString("vi-VN")}₫</span>
              )}
            </div>
            <div className="product-rating">
              ★★★★☆ <span>(24)</span>
            </div>
            <div className="product-status">
              {product.Status ? "✅ Còn hàng" : "❌ Hết hàng"}
            </div>
            <div className="product-fav">♡</div>
          </div>
        ))
      )}
    </main>
  );
}

export default ProductList;
