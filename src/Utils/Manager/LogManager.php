<?php

namespace App\Utils\Manager;

use App\Entity\Log;
use Doctrine\Persistence\ObjectRepository;

class LogManager extends AbstractBaseManager
{
	/**
	 * @return ObjectRepository
	 */
	public function getRepository(): ObjectRepository
	{
		return $this->entityManager->getRepository(Log::class);
	}
}