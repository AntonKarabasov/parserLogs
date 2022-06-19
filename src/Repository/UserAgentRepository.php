<?php

namespace App\Repository;

use App\Entity\UserAgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Log;

/**
 * @extends ServiceEntityRepository<UserAgent>
 *
 * @method UserAgent|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAgent|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAgent[]    findAll()
 * @method UserAgent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAgent::class);
    }

    public function add(UserAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function getMostPopularBrowsers($limit)
	{
		$entityManager = $this->getEntityManager();

//		$query = $entityManager->createQuery(
//			'SELECT u.browser AS browser, COUNT(u.browser) AS count_requests
//            FROM App\Entity\UserAgent AS u
//            JOIN App\Entity\Log AS l
//            WHERE NOT u.browser = :browser
//            GROUP BY browser
//            ORDER BY count_requests ASC'
//		)->setParameter('browser', 'unknown')->setMaxResults($limit);

		$query = $entityManager->createQueryBuilder('u')
			->select('u.browser', 'COUNT(u.browser)')
			->from( UserAgent::class, 'u')
			->innerJoin( 'u.log', 'l')
			->where('u.browser = :browser')
			->setParameter('browser', 'unknown')
			->groupBy('u.browser')
			->orderBy('COUNT(u.browser)');

		return $query->getQuery()->getResult();
	}

//    /**
//     * @return UserAgent[] Returns an array of UserAgent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserAgent
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
