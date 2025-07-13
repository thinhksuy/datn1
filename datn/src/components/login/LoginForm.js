import React, { useState } from "react";
import { motion } from "framer-motion";
import { FiEye, FiEyeOff } from "react-icons/fi";

const LoginForm = () => {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <div className="login-wrapper">
      <motion.form
        className="login-form"
        initial={{ opacity: 0, y: 40 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, ease: "easeOut" }}
      >
        <h2>Đăng Nhập</h2>

        <motion.input
          type="email"
          placeholder="Vui lòng nhập email"
          whileFocus={{ scale: 1.02 }}
          transition={{ type: "spring", stiffness: 300 }}
          required
        />

        <motion.div
          className="input-group"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
        >
          <input
            type={showPassword ? "text" : "password"}
            placeholder="Vui lòng nhập mật khẩu"
            required
          />
          <motion.span
            className="eye-icon"
            onClick={() => setShowPassword(!showPassword)}
whileHover={{ scale: 1.2, y: "-50%" }} // giữ lại translateY(-50%)
            transition={{ type: "spring", stiffness: 300 }}
          >
            {showPassword ? <FiEyeOff size={20} /> : <FiEye size={20} />}
          </motion.span>
        </motion.div>

        <div className="social-login">
          <span className="social-label">Đăng nhập với</span>
          <div className="social-icons">
            <motion.img
              src="https://cdn-icons-png.flaticon.com/512/145/145802.png"
              alt="Facebook"
              className="social-icon"
              whileHover={{ scale: 1.2, rotate: 5 }}
              transition={{ type: "spring", stiffness: 200 }}
            />
            <motion.img
              src="https://cdn-icons-png.flaticon.com/512/281/281764.png"
              alt="Google"
              className="social-icon"
              whileHover={{ scale: 1.2, rotate: -5 }}
              transition={{ type: "spring", stiffness: 200 }}
            />
          </div>
          <a href="/#" className="forgot-password">Quên mật khẩu?</a>
        </div>

        <motion.button
          type="submit"
          whileHover={{ scale: 1.05, boxShadow: "0px 8px 20px rgba(0,123,255,0.3)" }}
          whileTap={{ scale: 0.97 }}
          transition={{ type: "spring", stiffness: 300 }}
        >
          Đăng Nhập
        </motion.button>
      </motion.form>
    </div>
  );
};

export default LoginForm;
