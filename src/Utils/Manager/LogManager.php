<?php

namespace App\Utils\Manager;

use App\Entity\Log;
use App\Repository\LogRepository;


class LogManager extends AbstractBaseManager
{
	/**
	 * @return LogRepository
	 */
	public function getRepository(): LogRepository
	{
		return $this->entityManager->getRepository(Log::class);
	}

	public function getPercentRequestMostPopularBrowsersPerDay(): array
	{
		$totalRequestsPerDays = $this->getRepository()->getRequestPerDay();
		$mostPopularBrowsers = $this->getRepository()->getMostPopularBrowsers(3);

		$requestMostPopularBrowsersPerDays = [];

		foreach ($mostPopularBrowsers as $mostPopularBrowser) {
			$requestMostPopularBrowsersPerDays[] = $this->getRepository()
				->getRequestPerDayByBrowser($mostPopularBrowser['browser']);
		}



		$percentRequestMostPopularBrowsersPerDays = [];
		$i = 0;

		foreach ($requestMostPopularBrowsersPerDays as $requestMostPopularBrowserPerDays) {
			foreach ($requestMostPopularBrowserPerDays as $requestMostPopularBrowserPerDay) {
				foreach ($totalRequestsPerDays as $totalRequestsPerDay) {
					if ($requestMostPopularBrowserPerDay['day'] === $totalRequestsPerDay['day']) {
						$percentRequestMostPopularBrowsersPerDays[$i]['name'] = $requestMostPopularBrowserPerDay['browser'];
						$percentRequestMostPopularBrowsersPerDays[$i]['dates'][] = $totalRequestsPerDay['day'];
						$percentRequestMostPopularBrowsersPerDays[$i]['percent'][] = $this->getPercentageRatio(
							$requestMostPopularBrowserPerDay['count_requests'],
							$totalRequestsPerDay['requests']
						);
					}
				}
			}
			$i++;
		}

		return $percentRequestMostPopularBrowsersPerDays;
	}

	public function getMostPopularDataForTable()
	{
		$dates = $this->getRepository()->getDates();
		$mostPopularDataForTable = [];

		for ($i = 0, $iMax = count($dates); $i < $iMax; $i++) {
			$mostPopularDataForTable[$i]['date'] = $dates[$i]['day'];

			$requests = $this->getRepository()->getRequestsByDate($dates[$i]);
			$mostPopularDataForTable[$i]['requests'] = $requests[0]['requests'];

			$url = $this->getRepository()->getMostPopularUrlByDate($dates[$i]);
			$mostPopularDataForTable[$i]['url'] = $url[0]['url'];

			$browser = $this->getRepository()->getMostPopularBrowserByDate($dates[$i]);
			$mostPopularDataForTable[$i]['browser'] = $browser[0]['browser'];
		}

		return $mostPopularDataForTable;
	}

	public function getPercentageRatio(int $numberOne, int $numberTwo): float
	{
		return round($numberOne / $numberTwo * 100, 2);
	}
}