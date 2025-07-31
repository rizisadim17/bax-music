<?php

namespace App\Controller;

use App\Service\CharacterService;
use App\Service\LocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    public function getCharactersByLocation(string $location): Response
    {
        try {
            $characters = $this->characterService->getCharactersByLocation($location);
        } catch (\Exception $e) {
            $characters = [];
        }
        return $this->render('location/search-location.html.twig', [
            'characters' => $characters,
            'search' => $location,
        ]);
    }
    public function getCharactersByDimension(string $dimension): Response
    {
        try {
            $characters = $this->characterService->getCharactersByDimension($dimension);
        } catch (\Exception $e) {
            $characters = [];
        }

        return $this->render('location/search-dimension.html.twig', [
            'characters' => $characters,
            'search' => $dimension,
        ]);
    }
    public function getLocationById(int $id) {
        $data = $this->locationService->getLocationById($id);
        $characterData = [];
        
        $locationDetails = [
            'id' => $data['id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'dimension' => $data['dimension'],
            'residents' => $data['residents'],
        ];
        if (!empty($data['residents'])) {
            foreach ($data['residents'] as $characterUrl) {
                //Get the id of the url
                $characterId = substr($characterUrl, strrpos($characterUrl, '/') + 1);
                $characterDetails = $this->characterService->getCharacterById((int)$characterId);
                $characterData[] = $characterDetails;
            }
        }
        return $this->render('location/show.html.twig', [
            'details' => $locationDetails,
            'characters' => $characterData,
        ]);
    }
}
