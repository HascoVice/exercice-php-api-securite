<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CompanyApiTest extends ApiTestCase
{
    public function testGetCompanies()
    {
        $client = static::createClient();
        $response = $client->request('GET', '/api/companies');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/api/contexts/Company',
        ]);
    }

    public function testCreateCompany()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'name' => 'Nouvelle Société',
                'siret' => '12345678901234',
                'address' => '456 Rue de Exemple',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'Nouvelle Société',
        ]);
    }

    public function testUpdateCompany()
    {
        $client = static::createClient();

  
        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'name' => 'Société Ancienne',
                'siret' => '12345678901234',
                'address' => '123 Rue de Exemple',
            ],
        ]);

        $companyData = $response->toArray();
        $companyId = $companyData['id'];

     
        $client->request('PUT', '/api/companies/' . $companyId, [
            'json' => [
                'name' => 'Société Mise à Jour',
                'siret' => '12345678901234',
                'address' => '456 Rue de Exemple',
            ],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'Société Mise à Jour',
        ]);
    }

    public function testDeleteCompany()
    {
        $client = static::createClient();

        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'name' => 'Société Supprimable',
                'siret' => '12345678901234',
                'address' => '789 Rue de Exemple',
            ],
        ]);

        $companyData = $response->toArray();
        $companyId = $companyData['id'];

       
        $client->request('DELETE', '/api/companies/' . $companyId);

        $this->assertResponseStatusCodeSame(204);  
    }

    public function testInvalidSiret()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'name' => 'Société Invalide',
                'siret' => '123456789',  
                'address' => '789 Rue de Exemple',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);  
        $this->assertJsonContains([
            'hydra:description' => 'Le SIRET doit comporter exactement 14 chiffres.',
        ]);
    }

    public function testCreateCompanyWithMissingName()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'siret' => '12345678901234',
                'address' => '456 Rue de Exemple',
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);  
        $this->assertJsonContains([
            'hydra:description' => 'Le nom est obligatoire.',
        ]);
    }

    public function testCreateCompanyWithTooLongAddress()
    {
        $client = static::createClient();
        $response = $client->request('POST', '/api/companies', [
            'json' => [
                'name' => 'Société Test',
                'siret' => '12345678901234',
                'address' => str_repeat('A', 256),  
            ],
        ]);

        $this->assertResponseStatusCodeSame(400);  
        $this->assertJsonContains([
            'hydra:description' => 'L\'adresse ne peut pas dépasser 255 caractères.',
        ]);
    }
}
