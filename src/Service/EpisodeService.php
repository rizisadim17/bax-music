<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EpisodeService {
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

    public function getAllEpisodes() {
        $url = $this->baseUrl . '/episode';

        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            $episodes = [];
            foreach ($data['results'] as $episode) {
                $episodes[] = [
                    'id' => $episode['id'] ?? '',
                    'name' => $episode['name'] ?? '',
                    'air_date' => $episode['air_date'] ?? '',
                    'episode' => $episode['episode'] ?? '',
                    'url' => $episode['url'] ?? '',
                ];
            }
            usort($episodes, function ($a, $b) {
                return strtotime($b['air_date']) <=> strtotime($a['air_date']);
            });
            return $episodes;

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

    public function getEpisodeById(int $id, int $page) {
        $url = $this->baseUrl . '/episode/' . $id;

        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();
            $characterUrls = $data['characters'] ?? [];
            $pagination = $this->paginationService->pagination($characterUrls, $page, 20);

            $data = [
                'id' => $data['id'] ?? '',
                'name' => $data['name'] ?? '',
                'air_date' => $data['air_date'] ?? '',
                'episode' => $data['episode'] ?? '',
                'url' => $data['url'] ?? '',
            ];

            return [
                'episdode_data' => $data,
                'pagination' => $pagination,
            ];

        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Transport error: ' . $e->getMessage());
        }
    }

}