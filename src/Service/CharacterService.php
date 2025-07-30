<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CharacterService {
    private HttpClientInterface $client;
    private string $baseUrl;

    public function __construct(HttpClientInterface $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }


    public function getAllCharacters() {
        $url = $this->baseUrl . '/character';
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            $characters = [];
            if (!empty($data)) {
                foreach ($data['results'] as $character) {
                    $characters[] = $this->characterDetail($character);
                }
            }
            return $characters;
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

    public function getCharactersByDimension(string $dimension): ?array
    {
        $url = $this->baseUrl . '/location?dimension=' . $dimension;
        return $this->getCharactersByLocationOrDimension($url);
    }
    public function getCharactersByLocation(string $location): ?array
    {
        $url = $this->baseUrl . '/location?name=' . $location;
        return $this->getCharactersByLocationOrDimension($url);
    }

    private function getCharactersByLocationOrDimension(string $url): ?array
    {
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();
            $allCharacters = [];
            
            if (!empty($data['results'])) {
                foreach ($data['results'] as $location) {
                    if (!empty($location['residents'])) {
                        foreach ($location['residents'] as $residentUrl) {
                            // Fetch character data by URL
                            $characterResponse = $this->client->request('GET', $residentUrl);
                            $characterData = $characterResponse->toArray();
                            $allCharacters[] = $this->characterDetail($characterData);
                        }
                    }
                }
            }
            return $allCharacters;
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }


    public function getCharacterById(int $id): ?array
    {
        $url = $this->baseUrl . '/character/' . $id;
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            $characters = [];
            if (!empty($data)) {
                $characters = $this->characterDetail($data);
                $characters['status'] = $data['status'] ?? 'unknown';
            }

            return $characters;
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

    private function characterDetail($data): array
    {
        return [
            'id' => $data['id'],
            'name' => $data['name'],
            'status' => $data['status'],
            'species' => $data['species'],
            'gender' => $data['gender'],
            'image' => $data['image'],
            'origin' => [
                'name' => $data['origin']['name'] ?? 'Unknown'
            ],
            'location' => [
                'name' => $data['location']['name'] ?? 'Unknown'
            ],
            'episode' => $data['episode'] ?? []
        ];
    }

}