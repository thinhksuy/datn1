import React, { useState } from "react";
import { motion } from "framer-motion";
import { FiEye, FiEyeOff } from "react-icons/fi"; // 👈 Thêm icon mắt

function RegisterForm() {
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);

  return (
    <div className="register-wrapper">
      <motion.form
        className="register-form"
        initial={{ opacity: 0, y: 50 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, ease: "easeOut" }}
      >
        <h2>Đăng Ký</h2>

        <input type="text" placeholder="Họ và tên" required />
        <input type="email" placeholder="Email" required />
        <input type="tel" placeholder="Số điện thoại" required />

        <div className="password-wrapper">
          <input
            type={showPassword ? "text" : "password"}
            placeholder="Mật khẩu"
            required
          />
          <span
            className="toggle-password"
            onClick={() => setShowPassword((prev) => !prev)}
          >
            {showPassword ? <FiEyeOff /> : <FiEye />}
          </span>
        </div>

        <div className="password-wrapper">
          <input
            type={showConfirm ? "text" : "password"}
            placeholder="Nhập lại mật khẩu"
            required
          />
          <span
            className="toggle-password"
            onClick={() => setShowConfirm((prev) => !prev)}
          >
            {showConfirm ? <FiEyeOff /> : <FiEye />}
          </span>
        </div>

        <div className="terms">
          <input type="checkbox" id="agree" required />
          <label htmlFor="agree">
            Tôi đồng ý với <a href="/#">Điều khoản sử dụng</a>
          </label>
        </div>

        <motion.button
          type="submit"
          whileHover={{ scale: 1.05 }}
          whileTap={{ scale: 0.95 }}
        >
          Đăng Ký
        </motion.button>

        <p className="login-link">
          Đã có tài khoản? <a href="/#">Đăng Nhập</a>
        </p>
      </motion.form>
    </div>
  );
}

export default RegisterForm;
