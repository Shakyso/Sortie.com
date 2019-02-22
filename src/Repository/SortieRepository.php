<?php

namespace App\Repository;

use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
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
        $q = $this->createQueryBuilder('s');
            //->join('s.organisateur','o')
           // ->join('s.siteOrganisateur','si')
            //->join('s.etat','e')
            //->where('e.libelle != :e')
            //->setParameter('e', 'Créée');
        $q->orderBy('s.dateHeureDebut', 'DESC');
        $query = $q->getQuery();
        return $query->getResult();
    }

    //public function selectListSortie(User $user, $site, $searchBar, $dateStart, $dateEnd, $organizer, $signedOn, $notSignedOn, $pastEvent)
    public function selectListSortie($user, $site, $searchBar, $organizer, $signedOn, $notSignedOn, $pastEvent)
    {
       // var_dump($user);
        /////////////////////////////////////////
        /* ma requete

        //var_dump('je suis dans mon repository');
        //$nomSite=$site->getNom();
        //var_dump($site);

        $q = $this->createQueryBuilder('s')
            ->join('s.organisateur','o')
            ->join('s.siteOrganisateur','si')
            ->join('s.etat','e')
            ->where('e.libelle != :e')
            ->setParameter('e', 'Créée');

          $q->orderBy('s.dateHeureDebut', 'DESC');


        //$q->getQuery()->execute();

        //dd($q);
        $query = $q->getQuery();
        return $query->getResult();

*/
        /////////////////////FIN DE MA REQUETE

        $today = new \DateTime();
       // var_dump($today);
       // $interval= \DateInterval::createFromDateString("30 days");
        //$day30=$today->sub($interval);
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.users', 'p');
        //   $qb->addSelect('p');

        //$qb->andWhere('e.rdvTime>:day30');
        //$qb->setParameter('day30', $day30);

        //liste les events par site
        if($site!==null){
            $qb->andWhere('e.siteOrganisateur=:site');
            $qb->setParameter('site', $site);
        }
/*
        //liste les events selon rechercher
        if($searchBar!==""){
            $qb->andWhere('e.name LIKE :searchBar');
            $qb->setParameter('searchBar', '%'.$searchBar.'%');
        }

        //liste les events à partir de dateStart
        if($dateStart!==""){
            $qb->andWhere('e.rdvTime>:dateStart');
            $qb->setParameter('dateStart', $dateStart);
        }

        //liste les events après dateEnd
        if($dateEnd!==""){
            $qb->andWhere('e.rdvTime<:dateEnd');
            $qb->setParameter('dateEnd', $dateEnd);
        }
*/


        //liste les events dont le user est l'organisateur
        if($organizer==true){
           var_dump('je suis dans la recherche je suis ORGANISATEUR');
           // var_dump($user);
            $userId= $user->getId();
            var_dump($userId);
            $qb->andWhere('e.organisateur=:user');
            $qb->setParameter('user', $user);
        }

        //liste les events auxquels je suis inscrits
        if($signedOn== true){
            var_dump('je suis dans la recherche je suis inscrite');

            $userId= $user->getId();
            var_dump($userId);
            $qb->andWhere('p.id=:userId');
            $qb->setParameter('userId', $userId);
        }

        //liste les events auxquels je ne suis PAS inscrits
        if($notSignedOn== true){
            $qb->andWhere('p.id!=:userId');
            $qb->setParameter('userId', $user->getId());
        }

        //liste les events déjà passés
        if($pastEvent== true){
            $qb->andWhere('e.dateHeureDebut<:today');
            $qb->setParameter('today', $today);
        }
        //var_dump($qb);
        $query = $qb->getQuery();
        //var_dump($query);
        $result=$query->getResult();
       // var_dump($result);
        return $result;
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
