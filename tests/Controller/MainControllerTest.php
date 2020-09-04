<?php

namespace App\Tests\Controller;

use App\Controller\MainController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{


    public function testList()
    {
        $client = static::createClient();

        $client->request('GET', '/20');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $client->request('GET', '/203');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $client->request('GET', '/200');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());

        $client->request('GET', '/201');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('GET', '/202');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('GET', '/20000');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $client->request('GET', '/20001');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
