<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CharacterService {
    private HttpClientInterface $client;
    private string $baseUrl;
    private PaginationService $paginationService;

    public function __construct(
        HttpClientInterface $client,
        string $baseUrl,
        PaginationService $paginationService,
    ) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->paginationService = $paginationService;
    }


    public function getAllCharacters() {
        $url = $this->baseUrl . '/character';
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            $characters = [];
            if (!empty($data)) {
                foreach ($data['results'] as $character) {
                    $characters[] = $this->characterDetails($character);
                }
            }
            return $characters;
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

    public function getCharactersByDimension(string $dimension, int $page = 1): ?array
    {
        $url = $this->baseUrl . '/location?dimension=' . $dimension;
        return $this->getCharactersByLocationOrDimension($url, $page);
    }
    public function getCharactersByLocation(string $location, int $page = 1): ?array
    {
        $url = $this->baseUrl . '/location?name=' . $location;
        return $this->getCharactersByLocationOrDimension($url, $page);
    }

    private function getCharactersByLocationOrDimension(string $url, int $page): ?array
    {
        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();
            $allCharacterUrls = [];

            if (!empty($data['results'])) {
                foreach ($data['results'] as $location) {
                    if (!empty($location['residents'])) {
                        $allCharacterUrls = array_merge($allCharacterUrls, $location['residents']);
                    }
                }
            }

            $characters = [];
            $pagination = $this->paginationService->pagination($allCharacterUrls, $page, 20);

            foreach ($pagination['characters_per_page'] as $residentUrl) {
                $characterResponse = $this->client->request('GET', $residentUrl);
                $characterData = $characterResponse->toArray();
                $characters[] = $this->characterDetails($characterData);
            }

            return [
                'characters' => $characters,
                'pagination' => $pagination,
            ];

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
                $characters = $this->characterDetails($data);
                // $characters['status'] = $data['status'] ?? 'unknown';
            }

            return $characters;
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

    private function characterDetails($data): array
    {
        return [
            'id' => $data['id'] ?? '',
            'name' => $data['name'] ?? '',
            'status' => $data['status'] ?? '',
            'species' => $data['species'] ?? '',
            'gender' => $data['gender'] ?? '',
            'image' => $data['image'] ?? '',
            'origin' => [
                'name' => $data['origin']['name'] ?? '',
                'url' => $data['origin']['url'] ?? ''
            ],
            'location' => [
                'name' => $data['location']['name'] ?? '',
                'url' => $data['location']['url'] ?? ''
            ],
            'episode' => $data['episode'] ?? [],
        ];
    }

}