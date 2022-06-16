<?php

namespace App\Controller;

use App\Utils\Parser\LogParsing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
}
