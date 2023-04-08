<?php

$data = json_decode(file_get_contents("php://input"));

if($data && isset($data->products)){    
    
    include_once 'config/Database.php';
    include_once 'models/Order.php';


    $database = new Database();
    $db = $database->connect();

    $order = new Order($db);

    $order->products = $data->products;
    
    //create order
    $result=$order->register_order();

    if($result == 'true'){
        http_response_code(201);
        echo json_encode( array('message'=>'order created' ,'order_id'=>$order->id,'products_state'=> $order->created_products_state ) );
    }else{
        echo json_encode( array('message'=>'order not created','err'=>$result) );
    }
}else{
    http_response_code(422);
    echo json_encode( array('message'=>'order not created','err'=>'no data sent') );
}

