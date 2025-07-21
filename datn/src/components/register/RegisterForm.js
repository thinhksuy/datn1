import React, { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { FiEye, FiEyeOff } from "react-icons/fi";
import axios from "axios";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

function RegisterForm() {
  const [formData, setFormData] = useState({
    Role_ID: "",
    Name: "",
    Email: "",
    Phone: "",
    Password: "",
    ConfirmPassword: "",
  });

  const [roles, setRoles] = useState([]);
  const [showPassword, setShowPassword] = useState(false);
  const [showConfirm, setShowConfirm] = useState(false);

  useEffect(() => {
    axios
      .get("http://localhost:8000/api/roles")
      .then((res) => setRoles(res.data))
      .catch((err) => {
        toast.error("Không lấy được danh sách vai trò");
        console.error(err);
      });
  }, []);

  const handleChange = (e) => {
    setFormData((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    const {
      Role_ID,
      Name,
      Email,
      Phone,
      Password,
      ConfirmPassword,
    } = formData;

    if (!Role_ID) {
      toast.error("❌ Vui lòng chọn vai trò trước khi đăng ký");
      return;
    }

    if (Password !== ConfirmPassword) {
      toast.error("❌ Mật khẩu không khớp");
      return;
    }

    try {
      await axios.post("http://localhost:8000/api/users", {
        Role_ID: Number(Role_ID),
        Name,
        Email,
        Password,
        Phone,
        Gender: null,
        Date_of_birth: null,
        Avatar: null,
        Status: 1,
        Address: null,
      });

      toast.success(" Đăng ký thành công! Hãy đăng nhập");
      setFormData({
        Role_ID: "",
        Name: "",
        Email: "",
        Phone: "",
        Password: "",
        ConfirmPassword: "",
      });

      setTimeout(() => {
        window.location.href = "/login";
      }, 1500);
    } catch (err) {
      if (err.response?.status === 422 && err.response?.data?.errors) {
        const firstError = Object.values(err.response.data.errors)[0][0];
        toast.error(`❌ ${firstError}`);
      } else {
        toast.error("❌ Đăng ký thất bại, vui lòng thử lại sau.");
        console.error("Lỗi đăng ký:", err);
      }
    }
  };

  return (
    <div className="register-wrapper">
      <ToastContainer />
      <motion.form
        className="register-form"
        initial={{ opacity: 0, y: 50 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6, ease: "easeOut" }}
        onSubmit={handleSubmit}
      >
        <h2>Đăng Ký</h2>

        <motion.select
          name="Role_ID"
          value={formData.Role_ID}
          onChange={handleChange}
          whileFocus={{ scale: 1.01 }}
          transition={{ type: "spring", stiffness: 300 }}
          required
          className="styled-select"
        >
          <option value="">-- Chọn vai trò --</option>
          {roles.map((role) => (
            <option key={role.Role_ID} value={role.Role_ID}>
              {role.Name}
            </option>
          ))}
        </motion.select>

        <motion.input
          type="text"
          placeholder="Họ và tên"
          name="Name"
          value={formData.Name}
          onChange={handleChange}
          whileFocus={{ scale: 1.01 }}
          required
        />

        <motion.input
          type="email"
          placeholder="Email"
          name="Email"
          value={formData.Email}
          onChange={handleChange}
          whileFocus={{ scale: 1.01 }}
          required
        />

        <motion.input
          type="tel"
          placeholder="Số điện thoại"
          name="Phone"
          value={formData.Phone}
          onChange={handleChange}
          whileFocus={{ scale: 1.01 }}
          required
        />

        <div className="password-wrapper">
          <input
            type={showPassword ? "text" : "password"}
            placeholder="Mật khẩu"
            name="Password"
            value={formData.Password}
            onChange={handleChange}
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
            name="ConfirmPassword"
            value={formData.ConfirmPassword}
            onChange={handleChange}
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
          Đã có tài khoản? <a href="/login">Đăng Nhập</a>
        </p>
      </motion.form>
    </div>
  );
}

export default RegisterForm;
