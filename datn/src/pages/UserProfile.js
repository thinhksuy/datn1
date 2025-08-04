import React, { useState, useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";

function UserProfile() {
  const navigate = useNavigate();

  const [user, setUser] = useState({
    name: "",
    email: "",
    phone: "",
    gender: "",
    dob: "",
    address: "",
    avatar: "",
  });

  const [avatarFile, setAvatarFile] = useState(null);
  const [loading, setLoading] = useState(false);

  const [passwords, setPasswords] = useState({
    currentPassword: "",
    newPassword: "",
    confirmNewPassword: "",
  });

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (storedUser) {
      const userData = JSON.parse(storedUser);
      setUser({
        name: userData.name || "",
        email: userData.email || "",
        phone: userData.phone || "",
        gender: userData.gender || "",
        dob: userData.dob || "",
        address: userData.address || "",
        avatar: userData.avatar || "",
      });
    } else {
      navigate("/login");
    }
  }, [navigate]);

  const handleChange = (e) => {
    setUser((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handlePasswordChange = (e) => {
    setPasswords((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleAvatarChange = (e) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setAvatarFile(file);
      setUser((prev) => ({
        ...prev,
        avatar: URL.createObjectURL(file),
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    if (passwords.newPassword && passwords.newPassword !== passwords.confirmNewPassword) {
      alert("Mật khẩu mới và xác nhận không khớp.");
      setLoading(false);
      return;
    }

    try {
      let avatarUrl = user.avatar;

      if (avatarFile) {
        const formData = new FormData();
        formData.append("avatar", avatarFile);

        const uploadRes = await axios.post(
          "http://localhost:8000/api/upload-avatar",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        avatarUrl = uploadRes.data.url;
      }

      const updateRes = await axios.put(
        "http://localhost:8000/api/user",
        {
          name: user.name,
          phone: user.phone,
          gender: user.gender,
          dob: user.dob,
          address: user.address,
          avatar: avatarUrl,
          currentPassword: passwords.currentPassword || undefined,
          newPassword: passwords.newPassword || undefined,
        },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      );

      localStorage.setItem("user", JSON.stringify(updateRes.data.user));
      alert("Cập nhật thông tin thành công!");

      setPasswords({
        currentPassword: "",
        newPassword: "",
        confirmNewPassword: "",
      });
    } catch (err) {
      console.error(err);
      alert("Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <>
      <Header />
      <div className="user-profile-container" style={{ maxWidth: 600, margin: "auto", padding: 20 }}>
        <h2 style={{ marginBottom: 20 }}>Thông tin cá nhân</h2>
        <form onSubmit={handleSubmit}>
          <div style={{ textAlign: "center", marginBottom: 20 }}>
            {user.avatar && (
              <img
                src={user.avatar}
                alt="avatar"
                style={{ width: 120, height: 120, borderRadius: "50%", objectFit: "cover", marginBottom: 10 }}
              />
            )}
            <input type="file" accept="image/*" onChange={handleAvatarChange} />
          </div>

          {["name", "phone", "dob", "address"].map((field) => (
            <div style={{ marginBottom: 15 }} key={field}>
              <label>{field === "name" ? "Họ và tên" : field === "phone" ? "Số điện thoại" : field === "dob" ? "Ngày sinh" : "Địa chỉ"}:</label>
              <input
                type={field === "dob" ? "date" : "text"}
                name={field}
                value={user[field]}
                onChange={handleChange}
                style={{ width: "100%", padding: 8, fontSize: 16 }}
              />
            </div>
          ))}

          <div style={{ marginBottom: 15 }}>
            <label>Giới tính:</label>
            <select name="gender" value={user.gender} onChange={handleChange} style={{ width: "100%", padding: 8, fontSize: 16 }}>
              <option value="">Chọn giới tính</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Email (không thể thay đổi):</label>
            <input type="email" name="email" value={user.email} disabled style={{ width: "100%", padding: 8, fontSize: 16, backgroundColor: "#eee" }} />
          </div>

          <h3 style={{ marginTop: 30 }}>Đổi mật khẩu (tuỳ chọn)</h3>
          <input
            type="password"
            name="currentPassword"
            placeholder="Mật khẩu hiện tại"
            value={passwords.currentPassword}
            onChange={handlePasswordChange}
            style={{ width: "100%", padding: 8, marginBottom: 10 }}
          />
          <input
            type="password"
            name="newPassword"
            placeholder="Mật khẩu mới"
            value={passwords.newPassword}
            onChange={handlePasswordChange}
            style={{ width: "100%", padding: 8, marginBottom: 10 }}
          />
          <input
            type="password"
            name="confirmNewPassword"
            placeholder="Xác nhận mật khẩu mới"
            value={passwords.confirmNewPassword}
            onChange={handlePasswordChange}
            style={{ width: "100%", padding: 8, marginBottom: 20 }}
          />

          <button type="submit" disabled={loading} style={{ padding: "10px 20px", fontSize: 16 }}>
            {loading ? "Đang lưu..." : "Cập nhật thông tin"}
          </button>
        </form>
      </div>
      <Footer />
    </>
  );
}

export default UserProfile;
