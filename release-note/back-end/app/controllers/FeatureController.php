<?php
namespace App\Controllers;


class FeatureController
{

     public function __construct($container)
     {
          $this->notFoundHandler = $container->get('notFoundHandler');
          $this->logger =  $container->get('logger');
          $this->featureService = $container->get('featureService');
          $this->tokenService = $container->get('tokenService');
     }

     public function getAll($request, $response, $args) {
          
          $features = $this->featureService->getAll();
          return $response->withJson($features);
     }


     public function getAllByVersions($request, $response, $args) {
          $body= (object) $request->getParsedBody();
          $versions = $this->tokenService->getVersionsByToken($body->token);
          $features = $this->featureService->getAllByVersion($versions['versionFrom'], $versions['versionTo']);
          return $response->withJson($features);
     }

     public function getOne($request, $response, $args) {

          $feature = $this->featureService->getOne($args['id']);
          if($feature === null)
               return call_user_func($this->notFoundHandler, $request, $response);

          return $response->withJson($feature);
     }

     public function add($request, $response, $args) {

          $body= (object) $request->getParsedBody();
          $check = $this->checkFeature($body);

          if($check['error'] === true)
               return $response->withStatus(400)->withJson($check);

          try {
               $feature = $this->featureService->create($body);
          }
          catch(\Exception $e){
               $this->logger->info("Error add feature block - ".$e->getMessage());
          }
          return $response->withJson($feature);
     }

     public function update($request, $response, $args) {

          $body = (object) $request->getParsedBody();
          $check = $this->checkFeature($body);

          if($check['error'] === true)
               return $response->withStatus(400)->withJson($check);

          try{
               $feature = $this->featureService->update($args['id'], $body);

               if($feature === null)
                    return call_user_func($this->notFoundHandler, $request, $response);
          }
          catch(\Exception $e){
               $this->logger->info("Error update feature block - ". $e->getMessage());
          }
          return $response->withJson($feature);
     }

     public function delete($request, $response, $args) {

          try{
               $feature = $this->featureService->delete($args["id"]);
               
               if($feature === null)
                    return call_user_func($this->notFoundHandler, $request, $response);
          }
          catch(\Exception $e) {
               $this->logger->info("Error delete feature block - ". $e->getMessage());
          }
          return $response->withJson(['message' => 'Feature deleted']);
     }

     private function checkFeature($feature){

          if($feature->name === '') {
               return ['error' => true, 'message' => 'Name cannot be empty'];  
          }
          if(empty($feature->category)) {
               return ['error' => true, 'message' => 'Category cannot be empty']; 
          }
          if($feature->version === '') {
               return ['error' => true, 'message' => 'Version cannot be empty']; 
          }
          if(!preg_match_all('/^(\d+\.)?(\d+\.)?(\*|\d+)$/', (string) $feature->version)) {
               return ['error' => true, 'message' => 'Version must be number like : 1.0.0)']; 
          }
          /*
          if(!empty($feature->link) && !preg_match_all('/https?:\/\/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&\/\/-=]*)/', (string) $feature->link)) {
               return ['error' => true, 'message' => 'Link must be like : https://support.finelab.fr/']; 
          }
          */
         
          return ['error' => false, 'message' => ''];
     }

}

?>