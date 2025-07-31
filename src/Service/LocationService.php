<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LocationService {
    private string $baseUrl;
    private HttpClientInterface $client;

    public function __construct(
        HttpClientInterface $client,
        string $baseUrl
    ) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    public function getLocationById($id) {
        $url = $this->baseUrl . '/location/' . $id;

        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();
            return $data;

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

}