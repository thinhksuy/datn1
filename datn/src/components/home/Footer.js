const Footer = () => {
  return (
   <footer className="footer">
  <div className="footer-container">
    <div className="footer-col footer-logo-col">
      <img src="/img/logo/Logo_vicnec.png" alt="Logo VicNex" className="footer-logo" />
    </div>

    {/* Cột 1 - Logo + Giới thiệu */}
    <div className="footer-col footer-logo-col">
      <h4>📢 VicNex – Cửa Hàng Cầu Lông Chính Hãng</h4>
      <p>
        Chuyên cung cấp vợt, giày, quần áo & phụ kiện cầu lông chính hãng từ Yonex, Lining, Victor. <br />
        ✅ Cam kết chính hãng 100% <br />
        🔁 Hỗ trợ đổi trả & bảo hành dễ dàng <br />
        <a href="/#">Tìm hiểu thêm →</a>
      </p>
    </div>

    {/* Cột 2 - Chính sách */}
    <div className="footer-col">
      <h4>Chính sách & Hỗ trợ</h4>
      <ul>
        <li><a href="/#">📦 Giao hàng toàn quốc</a></li>
        <li><a href="/#">🔁 Chính sách đổi trả</a></li>
        <li><a href="/#">🛡️ Bảo hành 1 đổi 1</a></li>
        <li><a href="/#">❓ Câu hỏi thường gặp</a></li>
      </ul>
    </div>

    {/* Cột 3 - Liên hệ */}
    <div className="footer-col">
      <h4>Liên hệ & Địa chỉ</h4>
      <p>📞 0123 456 789</p>
      <p>📧 support@shop.com</p>
      <p>📍 123 Đường ABC, Quận X, TP.HCM</p>
    </div>

    {/* Cột 4 - Đăng ký + MXH */}
    <div className="footer-col">
      <h4>Kết nối với VicNex</h4>
      <div className="social-icons">
        <a href="/#"><i className="fa-brands fa-facebook"></i> Facebook</a><br />
        <a href="/#">Zalo</a><br />
        <a href="/#"><i className="fa-brands fa-youtube"></i> YouTube</a>
      </div>
      <input type="email" placeholder="Nhập email của bạn..." />
      <button>Đăng ký nhận tin</button>
    </div>
  </div>
</footer>


  );
};

export default Footer;
