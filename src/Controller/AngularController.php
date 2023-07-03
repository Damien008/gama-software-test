<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AngularController extends AbstractController
{
    #[Route('/', name: 'app_angular')]
    public function index(): Response
    {
        return $this->render('angular/index.html.twig');
    }
}
