<?php
// test/Entity/SubscribersTest.php
namespace App\Test\Entity;

use App\Entity\Subscribers;
use PHPUnit\Framework\TestCase;

class SubscribersTest extends TestCase
{
    public function testGetSubscribers()
    {
        $subscribers = new Subscribers();
        $result = $subscribers->getSubscribers([],[],["start"=>0, "length"=>1]);

        $this->assertEquals(count($result), 1); 
    }
}

