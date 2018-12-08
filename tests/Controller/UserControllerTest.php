<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testRegisterPage()
    {
        $crawler = $this->client->request('GET', '/register');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testForgotPassword()
    {
        $crawler = $this->client->request('GET', '/forgot-password');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}