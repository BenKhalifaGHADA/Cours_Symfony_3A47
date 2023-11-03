<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

//    /**
//     * @return Author[] Returns an array of Author objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Author
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    //Query Builder: Question 4
    public function showBooksByDateAndNbBooks($nbooks, $year)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->addSelect('a')
            ->where('a.nb_books > :nbooks')
            ->andWhere("b.publicationDate < :year")
            ->setParameter('nbooks', $nbooks)
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

    //Query Builder: Question 5
    public function updateBooksCategoryByAuthor($c)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->update('App\Entity\Book', 'b')

            ->set('b.category', '?1')
            ->setParameter(1, 'Romance')
            ->where('b.category LIKE ?2')
            ->setParameter(2, $c)
            ->getQuery()
            ->getResult();
    }
}
