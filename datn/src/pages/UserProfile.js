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

  const [editMode, setEditMode] = useState(false);
  const [avatarFile, setAvatarFile] = useState(null);
  const [loading, setLoading] = useState(false);

  const [passwords, setPasswords] = useState({
    currentPassword: "",
    newPassword: "",
    confirmNewPassword: "",
  });

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    console.log("storedUser from localStorage:", storedUser);
    if (storedUser !== null && storedUser !== undefined) {
      let userData;
      try {
        userData = JSON.parse(storedUser);
      } catch (e) {
        console.error("Error parsing storedUser JSON:", e);
        navigate("/login");
        return;
      }
      const fetchUserInfo = async () => {
        try {
          if (!userData.ID) {
            console.error("User ID not found in localStorage user data");
            return;
          }
          const res = await axios.get(`http://localhost:8000/api/users/${userData.ID}`, {
            headers: {
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          });

          if (res.data) {
            // Helper to format date to YYYY-MM-DD
            const formatDate = (dateStr) => {
              if (!dateStr) return "";
              const d = new Date(dateStr);
              if (isNaN(d)) return "";
              const month = (d.getMonth() + 1).toString().padStart(2, "0");
              const day = d.getDate().toString().padStart(2, "0");
              const year = d.getFullYear();
              return `${year}-${month}-${day}`;
            };

            setUser({
              name: res.data.Name || "",
              email: res.data.Email || "",
              phone: res.data.Phone || "",
              gender: res.data.Gender || "",
              dob: formatDate(res.data.Date_of_birth),
              address: res.data.Address || "",
              avatar: res.data.Avatar || "",
            });
          }
        } catch (error) {
          console.error("Failed to fetch user info:", error);
        }
      };

      fetchUserInfo();
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

      const storedUser = localStorage.getItem("user");
      if (!storedUser) {
        alert("User not logged in.");
        setLoading(false);
        return;
      }
      const userData = JSON.parse(storedUser);

      const updateRes = await axios.put(
        `http://localhost:8000/api/users/${userData.ID}`,
        {
          Role_ID: user.Role_ID || 2,
          Name: user.name,
          Phone: user.phone,
          Gender: user.gender,
          Date_of_birth: user.dob,
          Address: user.address,
          Avatar: avatarUrl,
          currentPassword: passwords.currentPassword || undefined,
          newPassword: passwords.newPassword || undefined,
        },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
          },
        }
      );

      // Keep existing user data and token in localStorage to avoid logout
      // Commenting out update of user data to prevent logout issue
      // const existingToken = localStorage.getItem("token");
      // localStorage.setItem("user", JSON.stringify(updateRes.data.user));
      // if (existingToken) {
      //   localStorage.setItem("token", existingToken);
      // }
      alert("Cập nhật thông tin thành công!");

      setPasswords({
        currentPassword: "",
        newPassword: "",
        confirmNewPassword: "",
      });

      setEditMode(false);
    } catch (err) {
      console.error(err);
      if (err.response && err.response.data && err.response.data.message) {
        alert(`Lỗi khi cập nhật: ${err.response.data.message}`);
      } else if (err.response && err.response.data && err.response.data.errors) {
        const errors = err.response.data.errors;
        const messages = Object.values(errors).flat().join("\\n");
        alert(`Lỗi khi cập nhật:\\n${messages}`);
      } else {
        alert("Có lỗi xảy ra khi cập nhật. Vui lòng thử lại.");
      }
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
            <img
              src={user.avatar || "/default-avatar.png"}
              alt="avatar"
              style={{ width: 120, height: 120, borderRadius: "50%", objectFit: "cover", marginBottom: 10 }}
              onError={(e) => { e.target.onerror = null; e.target.src = "/default-avatar.png"; }}
            />
            {editMode && <input type="file" accept="image/*" onChange={handleAvatarChange} />}
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Họ và tên:</label>
            <input
              name="name"
              value={user.name}
              onChange={handleChange}
              disabled={!editMode}
              style={{ width: "100%", padding: 8 }}
            />
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Số điện thoại:</label>
            <input
              name="phone"
              value={user.phone}
              onChange={handleChange}
              disabled={!editMode}
              style={{ width: "100%", padding: 8 }}
            />
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Giới tính:</label>
            <select
              name="gender"
              value={user.gender}
              onChange={handleChange}
              disabled={!editMode}
              style={{ width: "100%", padding: 8 }}
            >
              <option value="">Chọn giới tính</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Ngày sinh:</label>
            <input
              type="date"
              name="dob"
              value={user.dob}
              onChange={handleChange}
              disabled={!editMode}
              style={{ width: "100%", padding: 8 }}
            />
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Địa chỉ:</label>
            <input
              name="address"
              value={user.address}
              onChange={handleChange}
              disabled={!editMode}
              style={{ width: "100%", padding: 8 }}
            />
          </div>

          <div style={{ marginBottom: 15 }}>
            <label>Email (không thể thay đổi):</label>
            <input
              name="email"
              value={user.email}
              disabled
              style={{ width: "100%", padding: 8, backgroundColor: "#f3f4f6" }}
            />
          </div>

          {editMode && (
            <>
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
            </>
          )}

          {!editMode ? (
            <button type="button" onClick={(e) => { e.preventDefault(); setEditMode(true); }} style={{ padding: 10, fontSize: 16 }}>
              Chỉnh sửa
            </button>
          ) : (
            <button type="submit" disabled={loading} style={{ padding: 10, fontSize: 16 }}>
              {loading ? "Đang lưu..." : "Lưu thay đổi"}
            </button>
          )}
        </form>
      </div>
      <Footer />
    </>
  );
}

export default UserProfile;
