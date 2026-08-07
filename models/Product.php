<?php
class Product
{
    public int $id;
    public ?int $categoryId;
    public ?int $brandId;
    public string $proname;
    public ?string $slug;
    public float $price;
    public float $discountPrice;
    public int $quantity;
    public ?string $image;
    public ?string $description;
    public int $status;

    public string $createdAt;
    public string $updatedAt;

    // Dữ liệu lấy từ JOIN (không lưu trong bảng products)
    public ?string $cateName = null;
    public ?string $brandName = null;

    public function __construct(
        ?int $categoryId = null,
        ?int $brandId = null,
        string $proname = "",
        ?string $slug = null,
        float $price = 0,
        float $discountPrice = 0,
        int $quantity = 0,
        ?string $image = null,
        ?string $description = null,
        int $status = 1
    ) {
        $this->categoryId   = $categoryId;
        $this->brandId      = $brandId;
        $this->proname      = $proname;
        $this->slug         = $slug;
        $this->price        = $price;
        $this->discountPrice = $discountPrice;
        $this->quantity     = $quantity;
        $this->image        = $image;
        $this->description  = $description;
        $this->status       = $status;
    }
}
?>
