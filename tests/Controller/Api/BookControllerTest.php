<?php

namespace App\Tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testCreateBook(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
       $client->request('POST', '/api/books');

        // Validate a successful response and some content
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }
}