<?php

use PHPUnit\Framework\TestCase;

include_once 'models/OrderProduct.php';
include_once 'models/Order.php';
include_once 'config/Database.php';

class OrderTest extends TestCase
{
    private $db;
    private $order;

    protected function setUp(): void
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->order = new Order($this->db);
    }

    protected function tearDown(): void
    {
        // Clean up the database after each test and reset stock to  20kg Beef, 5kg Cheese, 1kg Onion
        $stmt = $this->db->prepare('
            DELETE FROM orders;
            DELETE FROM order_products;
            UPDATE `ingredients` SET `start_stock`=20000 ,`current_stock`=20000 , `lowstock_alert_sent`=0 WHERE id = 1;
            UPDATE `ingredients` SET `start_stock`=5000 ,`current_stock`=5000,  `lowstock_alert_sent`=0 WHERE id = 2;
            UPDATE `ingredients` SET `start_stock`=1000 ,`current_stock`=1000,  `lowstock_alert_sent`=0 WHERE id = 3;
        ');
        $stmt->execute();
    }

    public function testRegisterOrder()
    {
        // Set up test data
        $product1 = new stdClass();
        $product1->product_id = 1;
        $product1->quantity = 2;
        $product2 = new stdClass();
        $product2->product_id = 1;
        $product2->quantity = 1;
        $this->order->products = array($product1, $product2);

        // Call the function being tested
        $result = $this->order->register_order();

        // assert the order was correctly stored
        $this->assertTrue($result);
        $this->assertNotEmpty($this->order->id);
        $this->assertCount(2, $this->order->created_products_state);
        $this->assertEquals('order product created', $this->order->created_products_state[0]['message']);
        $this->assertEquals('order product created', $this->order->created_products_state[1]['message']);
       
        
        // assert the stock was correctly updated.
        $created_products_state = $this->order->created_products_state[0];
        $intial_ingrediant_stock=[20000,5000,1000];
        for ($i=0; $i <3 ; $i++) { 
            $this->assertEquals($intial_ingrediant_stock[$i]  - ($created_products_state['quantity'] * $created_products_state['ingredents'][$i]['quantityPerUnit']),$created_products_state['ingredents'][$i]['current_stock']);
        }
    }
}

