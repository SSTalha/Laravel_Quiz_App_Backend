<?php

namespace App\DTOs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RegisterDTO extends BaseDTOs
{
    public string $name;
    public string $email;
    public string $password;
    public string $role;

    public function __construct(mixed $request) {
        $this->name = $request['name'];
        $this->email =$request['email'];
        $this->password=Hash::make(Str::random(8));
        $this->role = $request['role'];
    }

}