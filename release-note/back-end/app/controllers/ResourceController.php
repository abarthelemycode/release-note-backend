<?php
namespace App\Controllers;


class ResourceController
{

     public function __construct($container)
     {
          $this->notFoundHandler = $container->get('notFoundHandler');
          $this->logger =  $container->get('logger');
          $this->tagService = $container->get('tagService');
          $this->categoryService = $container->get('categoryService');
     }

     public function getCategories($request, $response, $args) {
          $categories =  $this->categoryService->getAll();
          return $response->withJson($categories);
     }

     public function getTags($request, $response, $args) {
          $tags =  $this->tagService->getAll();
          return $response->withJson($tags);
     }

}

?>