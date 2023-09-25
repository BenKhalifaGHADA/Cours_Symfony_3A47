<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudenttController extends AbstractController
{
    #[Route('/studentt', name: 'app_studentt')]
    public function index(): Response
    {
        return $this->render('studentt/index.html.twig', [
            'controller_name' => 'StudenttController',
        ]);
    }
}
