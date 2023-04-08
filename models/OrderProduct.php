<?php

include_once 'Ingredient.php';
include_once 'Product.php';

class OrderProduct {
    
    //DB stuff
    private $conn;
    private $table= 'order_products';

    // Order Product properties
    public $order_id;
    public $product_id;
    public $quantity;
    public $decreaseProductIngredientsStockResp;
    
    //constractor with DB
    public function __construct($db){
        $this->conn = $db; 
    } 

    //Create Order Product record in the DB
    public function create(){
        $query='
        INSERT INTO '.$this->table.'
        SET
            `order_id`=:order_id,
            `product_id`=:product_id,
            `quantity`=:quantity
        ';
        
        $stmt = $this->conn->prepare($query);

        //bind data
        $stmt->bindParam(':order_id',$this->order_id);
        $stmt->bindParam(':product_id',$this->product_id);
        $stmt->bindParam(':quantity',$this->quantity);
        
        //execute
        try{
            if( $stmt->execute() ){
                // get product ingredients then decrese stock
                $this->decreaseProductIngredientsStockResp =$this->decreaseProductIngredientsStock($this->product_id);
                return 'true';
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    function decreaseProductIngredientsStock($product_id){
        $product = new Product($this->conn);
        $product->id = $product_id;
        $product->get_single_product();  // get products data and init the ingredients array
        $response_arr=[];
        foreach ($product->ingredients as &$productIngredient) {
            $res = $this->decreaseIngredientsStock($productIngredient);
            $product_res = array(
                'ingredient_id' => $productIngredient['ingredient_id'],
                'quantityPerUnit' => $productIngredient['quantity'],
                'current_stock' => $res['current_stock']
            );
            //push to data
            array_push($response_arr,$product_res);
        }
        return $response_arr;

    }

    function decreaseIngredientsStock($productIngredient){
        $ingredient = new Ingredient($this->conn);
        $ingredient->id = $productIngredient['ingredient_id'];
        $ingredient->get_single_ingredient(); // get ingredient data
        $res = $ingredient->decrease_stock($productIngredient['quantity'] * $this->quantity);
        if($res){
            return  array('current_stock' => $ingredient->current_stock);
        }else{
            return $res;
        }
    }    
}








































































