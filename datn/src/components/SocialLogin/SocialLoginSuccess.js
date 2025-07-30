import { useEffect } from "react";
import { useNavigate } from "react-router-dom";

function SocialLoginSuccess() {
  const navigate = useNavigate();

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const token = params.get("token");
    const userId = params.get("user_id");

    if (token && userId) {
      // 👉 Lưu token và user_id vào localStorage
      localStorage.setItem("token", token);
      localStorage.setItem("user_id", userId);

      // 👉 Optional: gọi API lấy thông tin người dùng nếu cần
      // fetchUserProfile(token); // <-- nếu có function xử lý

      // ✅ Chuyển hướng về trang chính
      navigate("/");
    } else {
      // ❌ Nếu thiếu token hoặc user_id -> quay về login
      navigate("/login");
    }
  }, [navigate]); // ✅ Thêm navigate vào dependency array

  return (
    <div
      style={{
        textAlign: "center",
        marginTop: "100px",
        fontSize: "18px",
        fontWeight: "bold",
      }}
    >
      Đang xác thực đăng nhập...
    </div>
  );
}

export default SocialLoginSuccess;
