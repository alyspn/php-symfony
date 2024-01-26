<?php


namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Se connecter');
        $form = $buttonCrawlerNode->form([
            '_username' => 'user@example.com',
            '_password' => 'password123',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/some/redirect/page');
    }
}
