// export default Banner;
import { useEffect, useState } from "react";

const Banner = () => {
  // Danh sách banner thay đổi
  const banner2List = ["/img/banner/banner2.png", "/img/banner/banner4.png", "/img/banner/banner5.png"];
  const banner3List = ["/img/banner/banner3.png", "/img/banner/banner6.png", "/img/banner/banner7.png"];

  const [index2, setIndex2] = useState(0);
  const [index3, setIndex3] = useState(0);

  // Đổi ảnh banner 2 mỗi 5s
  useEffect(() => {
    const interval2 = setInterval(() => {
      setIndex2((prev) => (prev + 1) % banner2List.length);
    }, 5000);
    return () => clearInterval(interval2);
  }, []);

  // Đổi ảnh banner 3 mỗi 5s
  useEffect(() => {
    const interval3 = setInterval(() => {
      setIndex3((prev) => (prev + 1) % banner3List.length);
    }, 5000);
    return () => clearInterval(interval3);
  }, []);

  return (
    <div className="banner">
      <div className="banner-item item-1">
        <img src="/img/banner/banner1.png" alt="Banner 1" />
      </div>
      <div className="banner-item item-2">
        <img src={banner2List[index2]} alt="Banner 2" />
      </div>
      <div className="banner-item item-3">
        <img src={banner3List[index3]} alt="Banner 3" />
      </div>
    </div>
  );
};

export default Banner;
