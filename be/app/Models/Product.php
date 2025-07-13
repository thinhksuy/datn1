<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    // ✅ Tên bảng
    protected $table = 'products';

    // ✅ Khóa chính không theo mặc định
    protected $primaryKey = 'Product_ID';

    // ✅ Không sử dụng timestamps tự động của Laravel
    public $timestamps = false;

    // ✅ Các cột được phép gán dữ liệu hàng loạt
    protected $fillable = [
        'Categories_ID',
        'Name',
        'SKU',
        'Brand',
        'Description',
        'Image',
        'Price',
        'Discount_price',
        'Quantity',
        'Status',
        'Created_at',
        'Updated_at',
    ];

    // ✅ Ép kiểu dữ liệu cho các cột
    protected $casts = [
        'Created_at'     => 'datetime',
        'Updated_at'     => 'datetime',
        'Price'          => 'decimal:2',
        'Discount_price' => 'decimal:2',
        'Status'         => 'boolean',
    ];

    // ✅ Quan hệ: Sản phẩm thuộc về một danh mục
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'Categories_ID', 'Categories_ID');
    }

    // ✅ Quan hệ: Sản phẩm có nhiều biến thể
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'Product_ID', 'Product_ID');
    }

    // ✅ Quan hệ: Sản phẩm có một biến thể chính
    public function variant(): HasOne
    {
        return $this->hasOne(ProductVariant::class, 'Product_ID', 'Product_ID');
    }

    // ✅ Quan hệ: Sản phẩm có nhiều giá trị thuộc tính (nối bảng trung gian)
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'product_variant_values', // bảng trung gian
            'Product_ID',             // FK từ bảng trung gian tới Product
            'Values_ID',              // FK từ bảng trung gian tới AttributeValue
            'Product_ID',             // local key
            'Values_ID'               // related key
        )->with('attribute');
    }

    // ✅ Quan hệ: Sản phẩm có nhiều hình ảnh
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'Product_ID', 'Product_ID');
    }

    // ✅ Quan hệ: Sản phẩm có nhiều chi tiết đơn hàng
    public function orderDetails(): HasMany
{
    return $this->hasMany(OrderDetail::class, 'Product_ID', 'Product_ID');
}

}
