<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SearchController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('components/search.html.twig');
    }
}