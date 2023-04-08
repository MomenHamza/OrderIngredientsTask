<?php
include_once 'models/OrderProduct.php';

class Order{
    //DB stuff
    private $conn;
    private $table= 'orders';

    // Order properties
    public $id;
    public $products=[];
    public $created_products_state=[];

        
    public function __construct($db){
        $this->conn = $db; 
    } 

    //add the order to the DB table
    public function register_order(){
        $query='
        INSERT 
            INTO '.$this->table.'
                (`id`, `date`) 
            VALUES 
                (NULL, CURRENT_TIMESTAMP);
        ';
        $stmt = $this->conn->prepare($query);

        try{
            if( $stmt->execute() ){
                $this->id= $this->conn->lastInsertId();
                $this->created_products_state = $this->register_order_products();
                return true;
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function register_order_products(){
        $products_response_arr=[];
        foreach ($this->products as &$product) {
            $res = $this->register_product($product);
            $product_res = array(
                'product_id' => $product->product_id,
                'quantity' => $product->quantity,
                'message' => $res['message' ],
                'ingredents' => $res['ingredents' ],
                
            );
            array_push($products_response_arr,$product_res);
        }
        return $products_response_arr;
    }

    // add order's product record to the DB
    function register_product($request_order_product){
        $order_product = new OrderProduct($this->conn);

        $order_product->order_id = $this->id;
        $order_product->product_id = $request_order_product->product_id;
        $order_product->quantity = $request_order_product->quantity;    
        
        
        $result = $order_product->create();

        if($result == 'true'){
            return  array('message'=>'order product created','ingredents'=>$order_product->decreaseProductIngredientsStockResp);
        }else{
            return  array('message'=>'order product not created','err'=>$result);
        }

    }    
}

