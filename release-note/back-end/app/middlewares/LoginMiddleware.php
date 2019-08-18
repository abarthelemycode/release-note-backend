<?php
namespace App\Middlewares;

class LoginMiddleware {

     private $settings;

     public function __construct($container) {
          
          $this->settings = (object) $container['settings']['login'];
          $this->tokenService = $container->get('tokenService');
     }

     public function __invoke($request, $response, $next) {

          //should check Authentication header not "Token"
          $token = $request->getHeader("Token")[0];
          $error = $this->tokenService->checkAuthenticationToken($token, $this->settings->secretkey);

          if(!empty($error))
               return  $response->withStatus(401)->withJson(['error' => true, 'message' => $error ]);

          $response = $next($request, $response);
          return $response;
     }
}



?>