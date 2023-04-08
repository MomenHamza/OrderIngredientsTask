<?php
class Crids{
    //DB prams
    private $host = 'localhost:3306';
    private $username = 'root';
    private $password = '';
    

    // Set the SMTP settings for Zoho
    private $mailHost = 'smtp.zoho.com';
    private $mailSMTPAuth = true;
    private $mailUsername = 'momenhamza40@gmail.com';
    private $mailPassword = 'u8PvA7cEjBnU';  // Password expires in 18/4/2023
    private $mailSMTPSecure = 'ssl';
    private $mailPort = 465;
    
    public function __construct(){
    } 

    public function getDBUsername(){
        return $this->username ;
    }
    public function getDBPassword(){
        return $this->password ;
    }
    public function getDBHost(){
        return $this->host ;
    }


    
    public function getmailHost(){
        return $this->mailHost ;
    }
    public function getMailSMTPAuth(){
        return $this->mailSMTPAuth ;
    }
    public function getMailUsername(){
        return $this->mailUsername ;
    }
    public function getMailPassword(){
        return $this->mailPassword ;
    }
    public function getMailSMTPSecure(){
        return $this->mailSMTPSecure ;
    }
    public function getMailPort(){
        return $this->mailPort ;
    }

}


