<?php
namespace App\Controllers;

class AuthenticationController
{
    public function __construct($container){
        
        $this->settings = (object) $container->get('settings')['login'];
        $this->userService = $container->get('userService');
        $this->tokenService = $container->get('tokenService');
    }

    public function login ($request, $response, $args) {
          
        $input = $request->getParsedBody();
        $user = $this->userService->getOneByUsername($input["username"]);

        // verify user
        if(!$user) 
            return $response->withStatus(400)->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  

        // verify password
        if (!password_verify($input['password'], $user->password)) 
            return $response->withStatus(400)->withJson(['error' => true, 'message' => 'These credentials do not match our records.']);  

        $token = $this->tokenService->createAuthenticationToken($user, $this->settings->secretkey);

        return $response->withJson(['token' => (string) $token]);
    }


    public function getBookerTokenVersion ($request, $response, $args) {
          
        $input = $request->getParsedBody();
        $versionFrom = $input["versionFrom"];
        $versionTo = $input["versionTo"];

        $token = $this->tokenService->createVersionToken($versionFrom, $versionTo, $this->settings->secretkey);
        
        return $response->withJson(['error' => false, 'message' => '', 'token' => (string) $token ]);
    }

    public function redirectToken ($request, $response, $args) {  
        $token = (string) $args["token"];
  
        $error = $this->tokenService->checkVersionToken($token, $this->settings->secretkey);
        if(!empty($error))
            return  $response->withStatus(401)->withJson(['error' => true, 'message' => $error ]);

        session_start();
        $_SESSION['phpTokenBooker'] =  (string) $token;    
        return $response->withStatus(200)->withHeader('Location', '/');
    }


}

?>