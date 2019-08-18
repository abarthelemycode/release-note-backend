<?php
namespace App\Controllers;


class EmailController
{

     public function __construct($container)
     {
          $this->viewRenderer      = $container->get('viewRenderer');
          $this->logger            = $container->get('logger');
          $this->featureService    = $container->get('featureService');
          $this->tokenService      = $container->get('tokenService');
          $this->emailService      = $container->get('emailService');
          $this->baseUrl           = $container->get('settings')['img_url'];
          $this->settings          = (object) $container->get('settings')['login'];
     }


     public function sendReleaseNote($request, $response, $args) {
          try{
               $body  = (object) $request->getParsedBody();
               $emailsTo      = $body->emailsTo;
               $versionFrom   = $body->versionFrom;
               $versionTo     = $body->versionTo;
               $object        = $body->object;

               if(empty($versionFrom) || empty($versionTo) )
                    return  $response->withStatus(400)->withJson(['error' => true, 'message' => 'Versions cannot be empty' ]);

               if($versionFrom > $versionTo )
                    return  $response->withStatus(400)->withJson(['error' => true, 'message' => 'Version From cannot be greater than Version To' ]);

               // check if expediteur
               if(empty($emailsTo))
                    return  $response->withStatus(400)->withJson(['error' => true, 'message' => 'There is no sender' ]);

               $from = 'support-releasenote@company.fr';
               $features = $this->featureService->getAllByVersion($versionFrom, $versionTo);

               // check object of email
               if(empty($object))
                    $object = 'Release note : '.$versionFrom;

               $emailBody     = $this->viewRenderer->fetch('emails/release_note.php', [ 'baseUrl' => $this->baseUrl, 'features' => $features ]);
               $emailReturn   = $this->emailService->sendEmail($object, $emailBody, $from, $emailsTo);
               
               return $response->withJson(['error' => false, 'message' =>  $emailReturn]);
          }
          catch(\Exception $e) {
               $this->logger->info("Error sending email - ". $e->getMessage());
               return $response->withStatus(500)->withJson(['error' => true, 'message' =>  "Error sending email"]);
          }

     }

}

?>