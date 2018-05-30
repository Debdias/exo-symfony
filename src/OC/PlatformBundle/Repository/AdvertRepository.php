<?php
namespace OC\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AdvertRepository extends EntityRepository
{
    public function myFindAll()
    {
      // Méthode 1 : en passant par l'EntityManager
      // $queryBuilder = $this->_em->createQueryBuilder()
      //   ->select('a')
      //   ->from($this->_entityName, 'a');
      // // Dans un repository, $this->_entityName est le namespace de l'entité gérée
      // // Ici, il vaut donc OC\PlatformBundle\Entity\Advert
      //
      // // Méthode 2 : en passant par le raccourci (je recommande)
      // $queryBuilder = $this->createQueryBuilder('a');
      //
      // // On n'ajoute pas de critère ou tri particulier, la construction
      // // de notre requête est finie
      //
      // // On récupère la Query à partir du QueryBuilder
      // $query = $queryBuilder->getQuery();
      //
      // // On récupère les résultats à partir de la Query
      // $results = $query->getResult();
      //
      // // On retourne ces résultats
      // return $results;

      return $this
      ->createQueryBuilder('a')
      ->getQuery()
      ->getResult();
    }

    public function myFindOne($id)
    {
      $qb = $this->createQueryBuilder('a');

      $qb->where('a.id = :id')->setParameter('id', $id);

      return $qb->getQuery()->getResult();
    }

    public function findByAuthorAndDate($author, $year)
    {
      $qb = $this->createQueryBuilder('a');

      $qb->where('a.author = :author')
      ->setParameter('author', $author)
      ->andWhere('a.date < :year')
      ->setParameter('year', $year)
      ->orderBy('a.date', DESC);

      return $qb->getQuery()->getResult();
    }

    public function whereCurrentYear(QueryBuilder $qb)
    {
      $qb->andWhere('a.date BETWEEN :start AND :end')
      ->setParameter('start', new \DateTime(date('Y').'-01-01')) // Date entre le 1er janvier de cette année
      ->setParameter('end', new \Datetime(date('Y').'-12-31')); //Et le 31 décembre de cette année
    }

    public function myFind(){
      $qb = $this->createQueryBuilder('a');

      $qb->where('a.author = :author')
      ->setParameter('author', 'Marine');

      $this->whereCurrentYear($qb);

      $qb->orderBy('a.date', 'DESC');

      return $qb->getQuery()->getResult();
    }

    public function myFindAllDQL(){
      $query = $this->_em->createQuery('SELECT a FROM OCPlatformBundle:Advert a');
      $results = $query->getResult();

      return $results;
    }

    public function getAdvertWithApplications()
    {
      $qb = $this->createQueryBuilder('a')
      ->leftJoin('a.applications', 'app')
      ->addSelect('app');

      return $qb->getQuery()->getResult();
    }
    public function getAdvertWithCategories(array $categoryNames)
    {
      $qb = $this->createQueryBuilder('a')
      ->innerJoin('a.categories', 'c')
      ->addSelect('c');

      $qb->where($qb->expr()->in('c.name'< $categoryNames));

      return $qb->getQuery()->getResult();
    }

    public function getAdverts()
    {
      $query = $this->createQueryBuilder('a')
      ->leftJoin('a.image', 'i')
      ->addSelect('i')
      ->leftJoin('a.categories', 'c')
      ->addSelect('c')
      ->orderBy('a.date', 'DESC')
      ->getQuery();

      $query
      ->setFirstResult(($page-1) * $nbPerPage)
      ->setMaxResults($nbPerPage)

      return new Paginator($query, true);
    }

}
