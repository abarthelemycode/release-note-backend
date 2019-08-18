<?php 
namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getOneByUsername($username)
    {
        return $this->userRepository->getOneByUsername($username);
    }

    public function hasPassword($password, $salt){
        $options = [ 'salt' => $salt];
        return password_hash($password, PASSWORD_BCRYPT , $options);
    }

}