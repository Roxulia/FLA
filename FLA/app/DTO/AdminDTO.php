<?php

namespace App\DTO;

use App\Models\Admins;

class AdminDTO extends baseDTO
{
    /**
     * Create a new class instance.
     */
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private string $role;
    public function __construct(
        int $id,
        string $name,
        string $email,
        string $password,
        string $role
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public static function fromArray(array $data) : self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password'],
            $data['role']
        );
    }

    public static function fromModel(Admins $data) : self
    {
        return new self(
            $data->id,
            $data->name,
            $data->email,
            $data->password,
            $data->role
        );
    }
}
