<?php

namespace App\Utils\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

abstract class AbstractBaseManager
{
	/**
	 * @var EntityManagerInterface
	 */
	protected EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * @return \Doctrine\ORM\EntityManagerInterface
	 */
	public function getEntityManager(): EntityManagerInterface
	{
		return $this->entityManager;
	}

	/**
	 * @return ObjectRepository
	 */
	abstract public function getRepository(): ObjectRepository;


	/**
	 * @param object $entity
	 *
	 * @return void
	 */
	public function save(object $entity): void
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}

	/**
	 * @param object $entity
	 *
	 * @return void
	 */
	public function remove(object $entity): void
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
	}
}