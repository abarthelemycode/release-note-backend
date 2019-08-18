<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getOneByUsername($username)
    {
        return User::where('username', $username)->first();
    }
}