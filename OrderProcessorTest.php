<?php

use PHPUnit\Framework\TestCase;
use Biller;
use Order;
use OrderProcessor;

class OrderProcessorTest extends TestCase
{
    public function test_process()
    {
        $order          = new Order();
        $biller         = new Biller();
        $orderProcessor = new OrderProcessor();

        $result = $orderProcessor->process($order, $biller);

        $this->assertTrue($result);
    }
}