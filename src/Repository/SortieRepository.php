<?php

namespace App\Repository;

use App\Entity\Site;
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

    public function findListSortieUser()
    {

        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('s.users','u')
            ->join('s.etat','e')
            ->orderBy('s.dateHeureDebut', 'DESC');

        $query = $q->getQuery();
        return $query->getResult();

    }
    public function findListSortie()
    {
        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('s.siteOrganisateur','si')
            ->join('s.etat','e')
            ->where('e.libelle != :e')
            ->setParameter('e', 'Créée');
        $q->orderBy('s.dateHeureDebut', 'DESC');
        $query = $q->getQuery();
        return $query->getResult();
    }

    public function selectListSortie(?Site $site=null)
    {
        //var_dump('je suis dans mon repository');
        //$nomSite=$site->getNom();
        //var_dump($site);

        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('s.siteOrganisateur','si')
            ->join('s.etat','e')
            ->where('e.libelle != :e')
            ->setParameter('e', 'Créée');

        if($site!==0){
            $q->andWhere('si = :site');
            $q->setParameter('site', $site);
        }

        $q->orderBy('s.dateHeureDebut', 'DESC');


        //$q->getQuery()->execute();

        //dd($q);
        $query = $q->getQuery();
        return $query->getResult();


    }

    public function findOrganisateur(){

        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('o.sorties','sorties')
            ->where('s.organisateur = sorties.organisateur');

        return $q->getQuery()->execute();
    }

    public function findNbParticipant($idSortie)
    {

     /*   "SELECT COUNT(sortie_user.user_id)
 FROM `sortie_user`  INNER JOIN sortie s 
 ON sortie_user.sortie_id = s.id 
 INNER JOIN user u 
 ON u.id = sortie_user.user_id WHERE sortie_id = 1";*/

        $q = $this->createQueryBuilder('s')
            ->select('COUNT(u.id)')
            ->innerjoin('s.users', 'u')
            ->where('s.id = :idSortie')
            ->setParameter('idSortie', $idSortie)
            ->getQuery()
            ->getSingleScalarResult();

        return $q;

    }

    public function findParticipation($id)
    {
        $q = $this->createQueryBuilder('s')
            ->select('s.id')
            ->innerjoin('s.users', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
        return $q;

    }

    public function findListParticipant($id){

        $q = $this->createQueryBuilder('s')
            ->select('s, u')
            ->innerJoin('s.users','u')
            ->innerJoin('u.sortiesInscrit', 'si')
            ->where('s.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->execute();
        return $q;

    }

    public function findAllInformtion($id){

        $q = $this->createQueryBuilder('s')
            ->select('s, l, v ,si')
            ->innerJoin('s.lieu', 'l')
            ->innerJoin('l.ville', 'v')
            ->innerJoin('s.siteOrganisateur', 'si')
            ->where('s.id = :id' )
            ->setParameter('id',$id)
            ->getQuery()
            ->execute();
        return $q;
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
