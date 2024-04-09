<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    private $paginator; 
    // (importer la pagination sur tout le repository)
    public function __construct(ManagerRegistry $registry,PaginatorInterface $paginator)
    {
       
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }


    public function findPublished(int $page,$categorie = null):PaginationInterface

    {

        $data = $this->createQueryBuilder('p')
        ->andWhere('p.state = :state')
        ->setParameter('state', 'PUBLISHED')
        ->orderBy('p.createdAt', 'DESC');
        // SI mes categories ne sont pas nulles on va faire une jointure sur l'entité catégorie elle même passée en paramètre
        if($categorie !=null ){
            $data->join('p.category', 'categ')

                ->andWhere('categ = :categ')

                ->setParameter('categ', $categorie);
        }
        $query = $data
        ->getQuery()
        ->getResult();
        $posts = $this->paginator->paginate($query, $page, 10);

        return $posts;

    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
