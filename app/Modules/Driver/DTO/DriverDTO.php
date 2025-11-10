<?php

namespace App\Modules\Driver\DTO;

class DriverDTO
{
    public ?string $id;
    public ?string $name;
    public ?string $email;
    public ?string $password;
    public ?string $created_at;
    public ?string $updated_at;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }
}
