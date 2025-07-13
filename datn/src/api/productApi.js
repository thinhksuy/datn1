// src/api/productApi.js
import axios from "axios";

// ✅ CHỈ dùng tới /api, KHÔNG thêm /products
const API_BASE_URL = "http://localhost:8000/api";

// Gọi danh sách sản phẩm
// src/api/productApi.js
export const fetchProducts = (page = 1, filters = {}) => {
  const params = { page, ...filters };
  return axios.get("http://localhost:8000/api/products", { params });
};



// Gọi chi tiết sản phẩm theo ID
export const fetchProductById = (id) => {
  return axios.get(`${API_BASE_URL}/products/${id}`);
};


