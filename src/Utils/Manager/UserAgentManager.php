<?php

namespace App\Utils\Manager;

use App\Entity\UserAgent;
use Doctrine\Persistence\ObjectRepository;

class UserAgentManager extends AbstractBaseManager
{
	/**
	 * @return ObjectRepository
	 */
	public function getRepository(): ObjectRepository
	{
		return $this->entityManager->getRepository(UserAgent::class);
	}
}