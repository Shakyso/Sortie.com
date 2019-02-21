<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    public function findAllVille(){
    $q = $this->createQueryBuilder('v')
        ->select('v , l')
        ->innerJoin('v.lieux', 'l')
        ->orderBy('v.nom', 'ASC');

    return $q->getQuery()->execute();
}
    public function findAllVilleLieu($idVille){

        $q = $this->createQueryBuilder('v')
            ->select('l.id, l.nom,l.rue, l.latitude, l.longitude, v.codePostal')
            ->innerJoin('v.lieux', 'l')
            ->where('v.id = :idVille' )
            ->orderBy('v.nom', 'ASC')
        ->setParameter('idVille', $idVille);

        return $q->getQuery()->execute();
    }




    // /**
    //  * @return Ville[] Returns an array of Ville objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Ville
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
