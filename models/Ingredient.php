<?php

include_once 'models/Email.php';

class Ingredient{

    //DB stuff
    private $conn;
    private $table= 'ingredients';

    // ingredient properties
    public $id;
    public $name;
    public $stock_threshold;
    public $current_stock;
    public $lowstock_alert_sent;
    public $start_stock;
    
    public $responses;
    public $errors;
    
    //constractor with DB
    public function __construct($db){
        $this->conn = $db; 
    } 
    
    public function get_single_ingredient(){
        //creat quary   
        $query ='
        SELECT
            `id`,
            `name`,
            `stock_threshold`,
            `lowstock_alert_sent`,
            `current_stock`,
            `start_stock`
        FROM
            `'.$this->table.'`
        WHERE id = ?
        LIMIT 0,1
        ';

        //prer stmt
        $stmt = $this->conn->prepare($query);

        //bind id
        $stmt->bindParam(1,$this->id);

        //Execute stmt
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //set properties 
        $this->id=$row['id'];
        $this->name= $row['name'];
        $this->stock_threshold= $row['stock_threshold'];
        $this->lowstock_alert_sent= $row['lowstock_alert_sent'];
        $this->current_stock= $row['current_stock'];
        $this->start_stock= $row['start_stock'];
    }

    public function decrease_stock($decrement_quntity){
        $query='
            UPDATE '.$this->table.'
            SET      
                `current_stock`=:current_stock     
            WHERE id = :id
        ';
        $stmt = $this->conn->prepare($query);
        $this->current_stock -= $decrement_quntity;
        $stmt->bindParam(':current_stock',$this->current_stock );
        $stmt->bindParam(':id',$this->id);
        
        try{
            if( $stmt->execute() ){
                if($this->current_stock <= (($this->stock_threshold/100) * $this->start_stock) && !$this->lowstock_alert_sent){
                    $this->send_stock_alert($this->name);
                }
                return 'true';
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function set_stock_alert_state($alert_state){
        $query='
        UPDATE '.$this->table.'
        SET      
            `lowstock_alert_sent`=:lowstock_alert_sent     
        WHERE id = :id
            ';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lowstock_alert_sent',($alert_state));
        $stmt->bindParam(':id',$this->id);
        
        //Execute 
        try{
            if( $stmt->execute() ){
                return 'true';
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function send_stock_alert($ingredient_name){

        $this->set_stock_alert_state(true);        

        $email = new Email();

        $email->emailSubject = "Ingredient Alert: $ingredient_name";
        $email->recipientEmail = "momenhamza90@yahoo.com"; // try your email 
        $email->recipientName = "momenhamza";
        $email->emailBody = "The stock of $ingredient_name has fallen below  $this->stock_threshold%.";

        //update email
        if($email->sendHtmlEmail()){
            return array('message'=> $email->emailSendingResult);
        }else{
            return array('message'=> $email->emailSendingResult);
        }
    }
}
