<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CharacterService;

final class CharacterController extends AbstractController
{
    private CharacterService $characterService;

    public function __construct(
        CharacterService $characterService
    )
    {
        $this->characterService = $characterService;
    }
    public function getAllCharacters() {
        $data = $this->characterService->getAllCharacters();

        return $this->render('character/index.html.twig', [
            'data' => $data,
        ]);
    }

    public function getCharacterById(int $id) {
        $data = $this->characterService->getCharacterById($id);
        return $this->render('character/show.html.twig', [
            'data' => $data,
        ]);
    }
}
