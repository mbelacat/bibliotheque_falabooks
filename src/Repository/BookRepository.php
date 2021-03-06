<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Library;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */

    public function findByCategory(Library $library ,Category $category = null)
    {
        $request =  $this->createQueryBuilder('b')
          ->addSelect('c')
          ->leftJoin('b.category', 'c')
          ->where('b.library = :library')
          ->setParameter('library', $library);
          if($category) {
            $request = $request->andWhere('b.category = :category')
                              ->setParameter('category', $category);
          }
          return $request->getQuery()
          ->getResult();
        ;
    }

    public function findBookAndUser(int $id): ?Book
   {
     return $this->createQueryBuilder('b')
       ->addSelect('u')
       ->leftJoin('b.borrower', 'u')
       ->addSelect('c')
       ->leftJoin('b.category', 'c')
       ->andWhere('b.id = :id')
       ->setParameter('id', $id)
       ->getQuery()
       ->getOneOrNullResult();
   }

   public function findBySearch(array $search=null)
   {
     $request =  $this->createQueryBuilder('b')
       ->addSelect('c')
       ->leftJoin('b.category', 'c');
       if($search[""]) {
         $request = $request->andWhere('c.id = :id')
                           ->setParameter('id', $category->getId());
       }
       return $request->getQuery()
       ->getResult();
   }

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
