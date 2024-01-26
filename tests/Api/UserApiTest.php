namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserApiTest extends ApiTestCase
{
    use ReloadDatabaseTrait; 

    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreateUser(): void
    {
        $response = $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'user@example.com',
                'firstName' => 'John',
                'lastName' => 'Doe',
                // autres champs nécessaires...
            ],
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetUser(): void
    {
        $response = $this->client->request('GET', '/api/users/1'); 

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'email' => 'user@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
        ]);
    }

    public function testUpdateUser(): void
    {
        $this->client->request('PUT', '/api/users/1', [
            'json' => [
                'firstName' => 'Jane',
                
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'firstName' => 'Jane',
        ]);
    }

    public function testDeleteUser(): void
    {
        $this->client->request('DELETE', '/api/users/1'); // Assurez-vous que l'ID existe

        $this->assertResponseStatusCodeSame(204);
    }

    
}
