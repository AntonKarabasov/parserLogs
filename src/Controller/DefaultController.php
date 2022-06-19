<?php

namespace App\Controller;

use App\Utils\Manager\LogManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function PHPUnit\Framework\exactly;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="mainpage")
     */
    public function index(LogManager $logManager): Response
    {
		$requestsPerDays = json_encode($logManager->getRepository()->getRequestPerDay());
		$requestsMostPopularBrowsers = json_encode($logManager->getPercentRequestMostPopularBrowsersPerDay());
	    $mostPopularDataForTable = $logManager->getMostPopularDataForTable();

        return $this->render('main/index.html.twig',
            [
				'requestsPerDays' => $requestsPerDays,
				'requestsMostPopularBrowsers' => $requestsMostPopularBrowsers,
				'mostPopularDataForTable' => $mostPopularDataForTable
            ]
        );
    }
}
