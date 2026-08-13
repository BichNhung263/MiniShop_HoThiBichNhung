<?php
namespace Models;

class Brand
{
    public int $id;
    public string $brandname;
    public string $slug;
    public ?string $description;
    public ?string $image;
    public int $status;

    public string $createdAt;
    public string $updatedAt;

    public function __construct(
        string $brandname = "",
        string $slug = "",
        ?string $description = null,
        ?string $image = null,
        int $status = 1
    ) {
        $this->brandname = $brandname;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
    }
}
?>