<?php

namespace App\Repository;

use App\DTO\AdminDTO;
use App\Models\Admins;

class AdminRepo
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create(AdminDTO $data)
    {
        $admin = Admins::create(
            [
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'role' => $data->role
            ]
        );
        return AdminDTO::fromModel($admin);
    }

    public function findByEmail(string $email)
    {
        $admin = Admins::where('email',$email)->first();
        if(!$admin)
        {
            return null;
        }
        else
        {
            return AdminDTO::fromModel($admin);
        }
    }

    public function delete(string $email)
    {
        $admin = Admins::where('email',$email)->first();
        if(!$admin)
        {
            return false;
        }
        return $admin->delete();
    }
}
