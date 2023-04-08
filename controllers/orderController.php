<?php 

$controller = new OrderController();
$controller->processRequest($_SERVER['REQUEST_METHOD']);

class OrderController{
    public function processRequest(string $method):void{
        $this->processCollectionRequest($method);
    }
    private function processCollectionRequest(string $method){
        switch ($method) {
            case 'POST':
                require 'api/order/create.php';
            break;
        }
    }
}