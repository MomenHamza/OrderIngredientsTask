<?php
class Product{

    //DB stuff
    private $conn;
    private $table= 'products';

    // Product properties
    public $id;
    public $name;
    public $ingredients;

    //constractor with DB
    public function __construct($db){
        $this->conn = $db; 
    } 

    // Get single Product by id
    public function get_single_product(){
        //creat quary   
           $query ='
            SELECT
                `id`,
                `name`
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
        $this->ingredients = $this->get_product_ingredients($this->id);

    } 


    //Get product ingredients
    public function get_product_ingredients($product_id){    
        $query ='
            SELECT
                `ingredient_id`,
                `quantity`
            FROM
                `product_ingredients`
            WHERE 
                product_id=:product_id
        ';
       
        //prer stmt
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam(':product_id',$product_id);    

        //Execute stmt
        $stmt->execute();
        $result =  $stmt;

        //get row count
        $num = $result->rowCount();

        
        // check if any ingredients
        if($num > 0){
            //ingredients array
            $ingredients_arr = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                extract($row);
            
                $ingredient_item = array(
                    'ingredient_id' => $ingredient_id,
                    'quantity' => $quantity
                    );
                //push to data
                array_push($ingredients_arr,$ingredient_item);
            }

            // return ingredients 
            return $ingredients_arr;
        }else {
            return [];
        }
    }
}