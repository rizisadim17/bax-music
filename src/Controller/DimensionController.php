<?php

namespace App\Controller;

use App\Service\CharacterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DimensionController extends AbstractController
{
    private CharacterService $characterService;

    public function __construct(
        CharacterService $characterService
    )
    {
        $this->characterService = $characterService;
    }

    public function searchCharactersDimension(): Response
    {
        return $this->render('dimension/search-dimension.html.twig');
    }
    public function searchCharactersLocation(): Response
    {
        return $this->render('dimension/search-location.html.twig');
    }

    public function getCharactersByLocation(string $location): Response
    {
        try {
            $characters = $this->characterService->getCharactersByLocation($location);
        } catch (\Exception $e) {
            $characters = [];
        }
        return $this->render('dimension/search-location.html.twig', [
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

        return $this->render('dimension/search-dimension.html.twig', [
            'characters' => $characters,
            'search' => $dimension,
        ]);
    }
}
