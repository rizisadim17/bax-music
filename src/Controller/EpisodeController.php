<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EpisodeService;
use App\Service\CharacterService;

final class EpisodeController extends AbstractController
{
    private EpisodeService $episodeService;
    private CharacterService $characterService;

    public function __construct(
        EpisodeService $episodeService,
        CharacterService $characterService
    )
    {
        $this->episodeService = $episodeService;
        $this->characterService = $characterService;
    }

    public function getAllEpisodes(): Response
    {
        $data = $this->episodeService->getAllEpisodes();

        return $this->render('episode/index.html.twig', [
            'data' => $data,
        ]);
    }

    public function getEpisodeById(int $id, Request $request) {
        $page = (int) $request->query->get('page', 1);
        $data = $this->episodeService->getEpisodeById($id, $page);
        $characterData = [];
       
        if (!empty($data['pagination']['characters_per_page'])) {
            foreach ($data['pagination']['characters_per_page'] as $characterUrl) {
                $characterId = substr($characterUrl, strrpos($characterUrl, '/') + 1);
                $characterDetails = $this->characterService->getCharacterById((int)$characterId);
                $characterData[] = $characterDetails;
            }
        }

        return $this->render('episode/show.html.twig', [
            'data' => $data['episdode_data'],
            'pagination' => $data['pagination'],
            'characters' => $characterData,
        ]);
    }
}