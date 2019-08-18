<?php 
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{

    public function __construct($settings)
    {
        // Instantiation and passing `true` enables exceptions
        $this->mail = new PHPMailer(true);
        //Server settings
        //$this->mail->SMTPDebug = 2;                                   // Enable verbose debug output
        $this->mail->isSMTP();                                          // Set mailer to use SMTP
        $this->mail->Host       = $settings['host'];                    // Specify main and backup SMTP servers
        $this->mail->SMTPAuth   = true;                                 // Enable SMTP authentication
        $this->mail->Username   = $settings['username'];                // SMTP username
        $this->mail->Password   = $settings['password'];                // SMTP password
        $this->mail->SMTPSecure = $settings['secure'];                  // Enable TLS encryption, `ssl` also accepted
        $this->mail->Port       = $settings['port'];                    // TCP port to connect to
    }

    public function sendEmail($object, $body, $from, $emailsTo){
      
        try {
            //Recipients
            $this->mail->setFrom($from);

            foreach($emailsTo as $email)
                $this->mail->addAddress($email);  // no name 
            //$mail->addAddress('joe@example.net', 'Joe User');     
            
            // Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            
            // Content
            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = $object;
            $this->mail->Body    = $body;

            $this->mail->send();
            return 'Email has been sent';
        } 
        catch (Exception $e) {
            return "Message could not be sent. Error: {$this->mail->ErrorInfo}";
        }
    }
}