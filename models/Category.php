<?php

namespace Models;

class Category
{
    public int $id;
    public string $catename;
    public string $slug;
    public ?string $description;
    public ?string $image;
    public int $status;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(
        string $catename = "",
        string $slug = "",
        ?string $description = null,
        ?string $image = null,
        int $status = 1
    ) {
        $this->catename = $catename;
        $this->slug = $slug;
        $this->description = $description;
        $this->image = $image;
        $this->status = $status;
    }
}
