<?php

namespace App\Http\Controllers;

use App\DTO\AdminDTO;
use App\Repository\AdminRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminContoller extends Controller
{
    private AdminRepo $admin_repo;

    public function __construct(AdminRepo $admin_repo)
    {
        $this->admin_repo = $admin_repo;
    }

    public function login(Request $request)
    {
        if($request->email == null)
        {
            return response()->json(['error'=>'Email is Null'],404);
        }
        $admin = $this->admin_repo->findByEmail($request->email);
        if($admin != null)
        {
            if(Hash::check($request->password,$admin->password))
            {
                $token = JWTAuth::fromUser($admin);
                return response()->json(['token'=>$token],200);
            }
            else
            {
                return response()->json(['error'=>'Invalid Credential'],401);
            }
        }
        else
        {
            return response()->json(['error'=>'Admin Not Found'],401);
        }
    }

    public function me(Request $request)
    {
        $admin = $this->admin_repo->findByEmail($request->email);
        if($admin != null)
        {
            return response()->json($admin,200);
        }
        return response()->json(['error'=>'Admin Not Found'],401);
    }

    public function createAdmin(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:50',
                'email' => 'required|string|max:50',
                'password' => 'required|string|max:20|min:8',
                'role' => 'nullable|string'
            ]
        );
        $admin = $this->admin_repo->findByEmail($request->email);
        if($admin == null)
        {
            $data = $this->admin_repo->create(AdminDTO::fromArray($request->all()));
            return response()->json($data,200);
        }
        else
        {
            return response()->json(['error'=>'Admin Already Existed'],401);
        }
    }
}
