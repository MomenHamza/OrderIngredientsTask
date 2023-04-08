<?php
require_once 'config/crids.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{
    //DB stuff
    private $mail;
    private $crid;

    
    public $senderEmail='momenhamza40@gmail.com';
    public $senderName='Momen';
    
    public $emailBody;

    public $recipientEmail;
    public $recipientName;

    public $emailSubject;
    public $emailSendingResult;
        
    //constractor with DB
    public function __construct(){
     
        $this->crid = new Crids();

        // Create a new PHPMailer instance
        $this->mail = new PHPMailer;

        $this->mail->CharSet = 'UTF-8';       
        $this->mail->Encoding = "base64";    

        // Set the SMTP settings for Zoho
        $this->mail->isSMTP();
        $this->mail->Host = $this->crid->getmailHost();
        $this->mail->SMTPAuth = $this->crid->getMailSMTPAuth();
        $this->mail->Username = $this->crid->getMailUsername();
        $this->mail->Password = $this->crid->getMailPassword();
        $this->mail->SMTPSecure = $this->crid->getMailSMTPSecure();
        $this->mail->Port = $this->crid->getMailPort();    
    } 


    public function sendHtmlEmail(){
        // Set the email message
        $this->mail->setFrom($this->senderEmail,$this->senderName);
        $this->mail->addAddress($this->recipientEmail, $this->recipientName);
        $this->mail->Subject  =  '=?UTF-8?B?'.base64_encode($this->emailSubject).'?=';
        $this->mail->Body = $this->emailBody;

        try{
            if($this->mail->send()){
                $this->emailSendingResult = 'Email sent successfully';
                return true;
            }else{
                $this->emailSendingResult = 'Error sending email';
                return false;
            }
        }catch(Exception $e){
            $this->emailSendingResult = $e->getMessage() . 'Error sending email: ' . $this->mail->ErrorInfo;
            return false;
        }
    }

}
