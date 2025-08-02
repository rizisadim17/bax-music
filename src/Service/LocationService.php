<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LocationService {
    private string $baseUrl;
    private HttpClientInterface $client;
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

    public function getLocationById(int $id, int $page) {
        $url = $this->baseUrl . '/location/' . $id;

        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            $characterUrls = $data['residents'] ?? [];
            
            $pagination = $this->paginationService->pagination($characterUrls, $page, 20);

            $data = [
                'id' => $data['id'] ?? '',
                'name' => $data['name'] ?? '',
                'type' => $data['type'] ?? '',
                'dimension' => $data['dimension'] ?? '',
                'url' => $data['url'] ?? '',
            ];

            return [
                'location_data' => $data,
                'pagination' => $pagination,
            ];

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

}