<?php

namespace App\Repository;

use App\Entity\Log;
use App\Entity\UserAgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function add(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function getDates(): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT DATE(l.datetime) AS day
            FROM App\Entity\Log AS l
            GROUP BY day
            ORDER BY day ASC'
		);

		return $query->getResult();
	}

	public function getRequestsByDate($date): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT DATE(l.datetime) AS date, COUNT(l.id) AS requests
            FROM App\Entity\Log AS l
            WHERE DATE(l.datetime) = :date
            GROUP BY date'
		)->setParameter('date', $date);

		return $query->getResult();
	}

	public function getMostPopularBrowserByDate($date): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT u.browser AS browser, COUNT(l.id) AS count_requests
            FROM App\Entity\Log   AS l
            JOIN App\Entity\UserAgent AS u WITH u.id = l.userAgent
            WHERE NOT u.browser = :browser AND DATE(l.datetime) = :date 
            GROUP BY browser
            ORDER BY count_requests DESC'
		)->setParameters(new ArrayCollection([
			new Parameter('browser', 'unknown'),
			new Parameter('date', $date)
		]))->setMaxResults(1);

		return $query->getResult();
	}

	public function getMostPopularUrlByDate($date): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT l.url AS url, COUNT(l.id) AS count_requests
            FROM App\Entity\Log   AS l
            WHERE DATE(l.datetime) = :date 
            GROUP BY url
            ORDER BY count_requests DESC'
		)->setParameter('date', $date)->setMaxResults(1);

		return $query->getResult();
	}

	public function getRequestPerDay(): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT DATE(l.datetime) AS day, COUNT(l.id) AS requests
            FROM App\Entity\Log AS l
            GROUP BY day
            ORDER BY day ASC'
		);

		return $query->getResult();
	}

	public function getRequestPerDayByBrowser(string $browser): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT DATE(l.datetime) AS day, u.browser AS browser, COUNT(l.id) AS count_requests
            FROM App\Entity\Log   AS l
            JOIN App\Entity\UserAgent AS u WITH u.id = l.userAgent
            WHERE u.browser = :browser
            GROUP BY day
            ORDER BY day ASC'
		)->setParameter('browser', $browser);

		return $query->getResult();
	}

	public function getMostPopularBrowsers(int $limit): array
	{
		$entityManager = $this->getEntityManager();

		$query = $entityManager->createQuery(
			'SELECT u.browser AS browser, COUNT(l.id) AS count_requests
            FROM App\Entity\Log   AS l
            JOIN App\Entity\UserAgent AS u WITH u.id = l.userAgent
            WHERE NOT u.browser = :browser
            GROUP BY u.browser
            ORDER BY count_requests DESC'
		)->setParameter('browser', 'unknown')->setMaxResults($limit);

//		$query = $entityManager->createQueryBuilder('u')
//			->select('u.browser')
//			->from( UserAgent::class, 'u');

		return $query->getResult();
	}

//    /**
//     * @return Log[] Returns an array of Log objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Log
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
