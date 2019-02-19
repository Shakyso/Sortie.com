<?php

namespace App\Command;

use App\Entity\EtatSortie;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class FixturesCommand extends Command
{
    protected static $defaultName = 'app:fixtures';

    protected $em = null;
    protected $encoder = null;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, ?string $name = null)
    {
        $this->encoder = $encoder;
        $this->em = $em;
        $this->EtatSortieAll=$em->getRepository(EtatSortie::class)->findAll();

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Load dummy data in our database')
            ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Faker\Provider\fr_FR\Address($faker));



        $conn = $this->em->getConnection();
        //désactive la vérification des clefs étrangère
        $conn->query('SET FOREIGN_KEY_CHECKS = 0');
        //vide toutes les tables
        $conn->query('TRUNCATE sortie');
        $conn->query('TRUNCATE lieu');
        $conn->query('TRUNCATE user');
        $conn->query('TRUNCATE ville');
        $conn->query('TRUNCATE etat_sortie');
        $conn->query('TRUNCATE sortie_user');
        $conn->query('TRUNCATE site');
        //réactive
        $conn->query('SET FOREIGN_KEY_CHECKS = 1');

        $io->text("Tables are now empty. I hope you are not in prod. Too late.");

        $etatSortie = ["Créée", "Ouverte", "Clôturée", "Activité en cours", "Passée", "Annulée"];
        $io->text("Now loading fixtures...");

        $io->text("Etat Sortie...");
        $allEtatSortie = [];

        foreach ($etatSortie as $label) {
            $etatSortie = new EtatSortie();
            $etatSortie->setLibelle($label);
            $this->em->persist($etatSortie);
            //on ajoute ce sujet à notre tableau d'objets
            $allEtatSortie[] = $etatSortie;
        }

        $this->em->flush();

        $io->text("Site...");
        $allSiteList = [];
        $siteList = ["Angers", "Bordeaux", "Rennes", "Lorient", "Ancenis", "Pornic"];
        foreach ($siteList as $label) {
            $siteList = new Site();
            $siteList->setNom($label);
            $this->em->persist($siteList);
            //on ajoute ce sujet à notre tableau d'objets
            $allSiteList[] = $siteList;
        }
        $this->em->flush();

        $io->text("Mon user...");
        //je me crée mon petit user à moi
        $me = new User();
        $siteMe= new Site();
        $siteMe->setNom("Nantes");
        $this->em->persist($siteMe);

        $me->setUsername("yo");
        $me->setMail("yo@yo.com");
        $hash = $this->encoder->encodePassword($me, "yo");
        $me->setPassword($hash);
        $me->setTelephone("0123456789");
        $me->setSite($siteMe);
        $this->em->persist($me);
        $this->em->flush();


        //créer d'autres users
        $allUsers = [];
        $io->text("Creating users...");

        for($i=0; $i<40; $i++){

            $user = new User();
            //hydrate toutes les propriétés...
            $user->setUsername($faker->unique()->username);

            //je fais comme si tous les users choisissaient leur username
            //comme password... comme ça je les connais
            $password = $user->getUsername();
            $hash = $this->encoder->encodePassword($user, $password);
            $user->setPassword($hash);

            $user->setMail($faker->unique()->email);
            $user->setTelephone($faker->phoneNumber. $faker->numberBetween(10,99));

            $this->em->persist($user);

            $allUsers[] = $user;
        }
        $this->em->flush();

        //créer des villes
        $allVilles = [];
        $io->text("Creating Villes...");

        for($i=0; $i<10; $i++){

            $ville = new Ville();
            //hydrate toutes les propriétés...
            $ville->setNom($faker->unique()->city);
            $ville->setCodePostal($faker->unique()->postcode);
            $this->em->persist($ville);

            $allVilles[] = $ville;
        }
        $this->em->flush();

        //créer des lieux
        $allLieux = [];
        $io->text("Creating Lieux...");

        for($i=0; $i<10; $i++){

            $lieu = new Lieu();
            //hydrate toutes les propriétés...
            $lieu->setNom($faker->unique()->city);
            $lieu->setRue($faker->unique()->address);
            $lieu->setLatitude($faker->unique()->latitude(8.000000,51.000000));
            $lieu->setLongitude($faker->unique()->longitude(-5.000000,2.000000));
            $lieu->setVille($faker->randomElement($allVilles));
            $this->em->persist($lieu);

            $allLieux[] = $lieu;
        }
        $this->em->flush();

        //créer des sorties
        $alSorties = [];
        $io->text("Creating Sorties...");

        for($i=0; $i<150; $i++){

            $sortie = new Sortie();
            //hydrate toutes les propriétés...
            $sortie->setNom($faker->unique()->realText(50));


            $sortie->setEtat($faker->randomElement($allEtatSortie));
            $statutSortie=$sortie->getEtat();
            $io->text("Statut de la sortie...".$statutSortie);
            if($statutSortie=='Passée') {
                $sortie->setDateLimiteInscription($faker->dateTimeBetween('-3 month', 'now'));
            }
            else{
                $sortie->setDateLimiteInscription($faker->dateTimeBetween('now', '+3 month'));
            }
            $dateSortie=$sortie->getDateLimiteInscription();
            $date = $dateSortie->format('Y-m-d H:i:s');
            //$io->text($date);
            //$dateLimite= new \DateTime();
            $dateSortieFormat = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $dateFinale = $dateSortieFormat->modify('+4 hour');
            $sortie->setDateHeureDebut($dateFinale);
            $duration = new \DateInterval('P0Y0M0DT4H0M');
            $sortie->setDuree($duration);
            $sortie->setNbInscriptionMax($faker->numberBetween(1,20));
            $num=mt_rand(1,$sortie->getNbInscriptionMax());
            for($b=0;$b<$num;$b++){
                $participants=$faker->randomElement($allUsers);
                $sortie->addUser($participants);
            }
            $sortie->setInfosSortie($faker->realText(2500));
            $sortie->setLieu($faker->randomElement($allLieux));
            //TODO etat en fonction de la date
            $maintenant=new \DateTime();
            $maintenantString = $maintenant->format('Y-m-d H:i:s');


            $sortie->setOrganisateur($faker->randomElement($allUsers));

            $sortie->setSiteOrganisateur($faker->randomElement($allSiteList));

            $this->em->persist($sortie);



        }
        $this->em->flush();


        $io->success('OK');
    }

}
