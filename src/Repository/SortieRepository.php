<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    public function FindListSortie()
    {

        /*SELECT * FROM sortie s
        INNER JOIN sortie_user su
         ON s.id = su.sortie_id
        INNER JOIN user u
         ON su.user_id = u.id
        INNER JOIN etat_sortie es
        ON s.etat_id = es.id**/

        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('o.participeA','participe_a')
            ->join('s.etat','e');


        $q->getQuery()->execute();
       // dd($q);
        return $q->getQuery()->execute();

    }

    public function FindNbParticipant($id)
    {

        $q = $this->createQueryBuilder('s')
            ->select('COUNT(s')
            ->join('s.organisateur', 'o')
            ->where('s.organisateur = :id');

        //dd($q)
        return $q->getQuery()->execute();

    }



    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
