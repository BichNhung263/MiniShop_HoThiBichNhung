<?php

namespace Models;

class Customer
{
    public int $id;
    public string $fullname;
    public ?string $email;
    public string $phone;
    public ?string $address;
    public string $note;
    public int $status;
    public string $createdAt;
    public string $updatedAt;
    public function __construct(
        string $fullname = "",
        string $email = "",
        string $phone = "",
        ?string $address = null,
        string $note = "",
        int $status = 1
    ) {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->note = $note;
        $this->status = $status;
    }
}
