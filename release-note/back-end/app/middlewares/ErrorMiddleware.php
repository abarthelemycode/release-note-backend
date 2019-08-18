<?php
namespace App\Middlewares;

class ErrorMiddleware {

     public function __construct($container, $statusCode) {
          $this->statusCode = $statusCode;
          $this->logger = $container->get('logger');
     }

     public function __invoke($request, $response, $exception) {

          $message = '';
          switch($this->statusCode){
               case 404 :
                    $message = 'Resource not found'; 
                    break;
               case 500 :
                    $this->logger->error("Error - statusCode : ".$this->statusCode." - message : ".$exception->getMessage());
                    $message = 'Internal server error';
                    break;
          }

          return $response->withStatus($this->statusCode)->withJson([
               'error'=> true, 
               'message' => $message 
          ]);
     }

}



?>