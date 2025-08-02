<?php

namespace App\Controller;

use App\Service\CharacterService;
use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocationController extends AbstractController
{
    private CharacterService $characterService;
    private LocationService $locationService;

    public function __construct(
        CharacterService $characterService,
        LocationService $dimensionService
    )
    {
        $this->characterService = $characterService;
        $this->locationService = $dimensionService;
    }

    public function searchCharactersDimension(): Response
    {
        return $this->render('location/search-dimension.html.twig');
    }

    public function searchCharactersLocation(): Response
    {
        return $this->render('location/search-location.html.twig');
    }

    public function getCharactersByLocation(string $location, Request $request): Response
    {
        try {
            $page = (int) $request->query->get('page', 1);
            $data = $this->characterService->getCharactersByLocation($location, $page);
        } catch (\Exception $e) {
            $data = [];
        }

        return $this->render('location/search-location.html.twig', [
            'characters' => $data['characters'],
            'pagination' => $data['pagination'],
            'search' => $location,
        ]);
    }

    public function getCharactersByDimension(string $dimension, Request $request): Response
    {
        try {
            $page = (int) $request->query->get('page', 1);
            $data = $this->characterService->getCharactersByDimension($dimension, $page);
        } catch (\Exception $e) {
            $data = [];
        }

        return $this->render('location/search-dimension.html.twig', [
            'characters' => $data['characters'],
            'pagination' => $data['pagination'],
            'search' => $dimension,
        ]);
    }

    public function getLocationById(int $id, Request $request) {
        $page = (int) $request->query->get('page', 1);
        $data = $this->locationService->getLocationById($id, $page);
        $characterData = [];

        if (!empty($data['pagination']['characters_per_page'])) {
            foreach ($data['pagination']['characters_per_page'] as $characterUrl) {
                $characterId = substr($characterUrl, strrpos($characterUrl, '/') + 1);
                $characterDetails = $this->characterService->getCharacterById((int)$characterId);
                $characterData[] = $characterDetails;
            }
        }

        return $this->render('location/show.html.twig', [
            'details' => $data['location_data'],
            'pagination' => $data['pagination'],
            'characters' => $characterData,
        ]);
    }
}
