import React, { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import PropTypes from 'prop-types';

function FilterSidebar({ setFilters }) {
  const [selectedFilters, setSelectedFilters] = useState({});

  // ✅ Cập nhật selectedFilters khi checkbox thay đổi
  const handleCheckboxChange = (groupTitle, option) => {
    setSelectedFilters((prev) => {
      const group = prev[groupTitle] || [];
      const updatedGroup = group.includes(option)
        ? group.filter((item) => item !== option)
        : [...group, option];

      return {
        ...prev,
        [groupTitle]: updatedGroup,
      };
    });
  };

  // ✅ Gửi filters ra ngoài cho component cha xử lý API
  useEffect(() => {
    setFilters(selectedFilters);
  }, [selectedFilters, setFilters]);

  return (
    <motion.aside
      className="filter-wrapper"
      initial={{ x: -100, opacity: 0 }}
      animate={{ x: 0, opacity: 1 }}
      transition={{ duration: 0.6, ease: 'easeOut' }}
    >
      <div className="filter-box">
        <h3 className="filter-box__title">BỘ LỌC SẢN PHẨM</h3>

        {filterData.map((group, index) => (
          <div className="filter-group" key={index}>
            <h4 className="filter-group__title">{group.title}</h4>

            {group.type === 'color' ? (
              <div className="color-options">
                {group.options.map((color, idx) => (
                  <span
                    key={idx}
                    className={`color-option ${color.class}`}
                    title={color.label}
                    onClick={() => handleCheckboxChange(group.title, color.label)}
                    style={{
                      border: selectedFilters[group.title]?.includes(color.label)
                        ? '2px solid #333'
                        : '1px solid #ccc',
                    }}
                  ></span>
                ))}
              </div>
            ) : (
              <ul className="scrollable-list">
                {group.options.map((option, idx) => (
                  <li key={idx}>
                    <label>
                      <input
                        type="checkbox"
                        checked={selectedFilters[group.title]?.includes(option) || false}
                        onChange={() => handleCheckboxChange(group.title, option)}
                      />{' '}
                      {option}
                    </label>
                  </li>
                ))}
              </ul>
            )}
          </div>
        ))}
      </div>
    </motion.aside>
  );
}

// ✅ Ràng buộc prop để tránh lỗi khi không truyền
FilterSidebar.propTypes = {
  setFilters: PropTypes.func.isRequired,
};

// ✅ Dữ liệu bộ lọc
const filterData = [
  {
    title: 'Lọc theo loại sản phẩm',
    type: 'list',
    options: ['Vợt cầu lông', 'Giày cầu lông', 'Túi đựng vợt', 'Quần áo', 'Phụ kiện', 'Cước vợt', 'Quấn cán'],
  },
  {
    title: 'Lọc theo thương hiệu',
    type: 'list',
    options: ['Yonex', 'Lining', 'Victor', 'Forza', 'Protech', 'Kumpoo', 'Kawasaki', 'Mizuno', 'Apacs', 'Babolat', 'Adidas', 'Astec'],
  },
  {
    title: 'Lọc theo giá',
    type: 'list',
    options: ['Dưới 500.000đ', '500.000đ - 1.000.000đ', '1.000.000đ - 2.000.000đ', 'Trên 2.000.000đ'],
  },
  {
    title: 'Lọc theo đánh giá',
    type: 'list',
    options: ['★★★★★ (5 sao)', '★★★★☆ (4 sao trở lên)', '★★★☆☆ (3 sao trở lên)'],
  },
  {
    title: 'Lọc theo màu sắc',
    type: 'color',
    options: [
      { label: 'Đỏ', class: 'red' },
      { label: 'Xanh dương', class: 'blue' },
      { label: 'Xanh lá', class: 'green' },
      { label: 'Đen', class: 'black' },
      { label: 'Trắng', class: 'white' },
      { label: 'Vàng', class: 'yellow' },
    ],
  },
  {
    title: 'Lọc theo giới tính',
    type: 'list',
    options: ['Nam', 'Nữ', 'Unisex'],
  },
  {
    title: 'Lọc theo kích cỡ',
    type: 'list',
    options: ['Size S', 'Size M', 'Size L', 'Size XL', 'Size 38–44 (Giày)'],
  },
  {
    title: 'Lọc theo cấp độ',
    type: 'list',
    options: ['Người mới chơi', 'Trung cấp', 'Nâng cao', 'Chuyên nghiệp'],
  },
  {
    title: 'Lọc theo cân nặng vợt',
    type: 'list',
    options: ['3U (85–89g)', '4U (80–84g)', '5U (75–79g)'],
  },
  {
    title: 'Lọc theo độ cứng thân',
    type: 'list',
    options: ['Mềm', 'Trung bình', 'Cứng'],
  },
  {
    title: 'Lọc theo điểm cân bằng',
    type: 'list',
    options: ['Nặng đầu (tấn công)', 'Cân bằng', 'Nặng đuôi (phòng thủ)'],
  },
  {
    title: 'Lọc theo khuyến mãi',
    type: 'list',
    options: ['Đang giảm giá', 'Mua 1 tặng 1', 'Có quà tặng'],
  },
];

export default FilterSidebar;
