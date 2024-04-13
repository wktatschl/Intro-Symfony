<?php

namespace App\Repository;

use App\DTO\SearchData;
use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
    private $searchData;
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator, SearchData $searchData)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
        $this->searchData = $searchData;
    }

    public function findPublished(int $page, $categorie = null, $tag = null): PaginationInterface

    {

        $data = $this->createQueryBuilder('p')
            ->andWhere('p.state = :state')
            ->setParameter('state', 'PUBLISHED')
            ->orderBy('p.createdAt', 'DESC');

        if ($categorie != null) {
            $data->join('p.category', 'categ')
                ->andWhere('categ = :categ')
                ->setParameter('categ', $categorie);
        }

        if ($tag != null) {
            $data->join('p.tags', 't')
                ->andWhere('t = :tag')
                ->setParameter('tag', $tag);
        }

        $query = $data
            ->getQuery()
            ->getResult();

        $posts = $this->paginator->paginate($query, $page, 10);
        return $posts;
    }

    public function searchResult(): PaginationInterface
    {
        $data = $this->createQueryBuilder('p')
            ->andWhere('p.state = :state')
            ->setParameter('state', 'PUBLISHED')
            ->orderBy('p.createdAt', 'DESC');

        if ($this->searchData->query) {
            $data->andWhere('p.title LIKE :query')
                //->setParameter('query', '%'.$this->searchData->query.'%'); syntaxe alternative
                ->setParameter('query', "%{$this->searchData->query}%");
        }

        if ($this->searchData->categories) {
            $data->join('p.category', 'categ')
                 ->andWhere('categ IN (:categ)')
                 ->setParameter('categ', $this->searchData->categories);
        }

        $query = $data
            ->getQuery()
            ->getResult();

        $query = $this->paginator->paginate($query, $this->searchData->page,10);
        return $query;
    }
}
