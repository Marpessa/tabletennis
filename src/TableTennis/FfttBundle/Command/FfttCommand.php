<?php

namespace TableTennis\FfttBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use TableTennis\FfttBundle\Manager\ServiceManager;
use TableTennis\LicenseeBundle\Entity\Licensee;
use TableTennis\LicenseeBundle\Entity\LicenseePoint;
use TableTennis\LicenseeBundle\Entity\LicenseeMatch;

use TableTennis\FfttBundle\Entity\Parameter;

class FfttCommand extends ContainerAwareCommand
{
    private $entityManager;
    private $output;

    protected function configure() {
        $this
            ->setName('cron:fftt')
            ->setDescription('Import data')
            //->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            //->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;

        /*
        http://www.fftt.com/mobile/xml/xml_liste_joueur_o.php?club=04850028
        http://www.fftt.com/mobile/xml/xml_joueur.php?licence=8513023
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $logger = $this->getContainer()->get('logger');
        $logger->info('CRON CPFAIZENAY START');

        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
        $this->output = $output;
        $this->service = new ServiceManager();

        $this->output->writeln( ">>> START - IMPORT" );

        $clubNumber = (string) "04850028";
        $parameter = $this->getParameter( 'licensee' );

        // GET Licensee FROM BD
        $licensee_list = $this->entityManager->getRepository('TableTennisLicenseeBundle:Licensee')
                                             ->getLicensees()
                                             ->getArrayResult();

        // Get and Save Licensee if update if necessary
        if( $parameter->getNextUpdate()->getTimestamp() <= time() ) {

            $this->output->writeln( "START - GET > licenseesClub_fftt" );
            $this->output->writeln( ">>>>>>>>>>>>>>" );
            $this->output->writeln( "" );

            // GET Licensee FROM FFTT API
            $licenseesClub_fftt = $this->service->getLicencesByClub( $clubNumber );

            foreach( $licenseesClub_fftt as $licensee ) {
                $this->output->writeln( $licensee[ 'club' ] . ' (' . $licensee[ 'nclub' ] . ') > [' . $licensee[ 'licence' ] . '] ' . $licensee[ 'nom' ] . ' ' . $licensee[ 'prenom' ] );
                $this->output->writeln( '-----------------------' );
            }

            $this->output->writeln( "" );
            $this->output->writeln( "<<<<<<<<<<<<<<<<" );
            $this->output->writeln( "END - GET > licenseesClub_fftt" );

            // SAVE Licensee
            $this->output->writeln( "START - SAVE > saveLicensees" );
            $this->saveLicensees( $licensee_list, $licenseesClub_fftt );
            $this->output->writeln( "END - SAVE > saveLicensees" );

            // UPDATE parameter FOR NEXT CRON CALL // DO NOT CALL AGAIN FFTT API
            $this->saveParameter( $parameter );
        } else {
            
        }

        /*$this->output->writeln( "START - SAVE > saveLicensees" );
        $this->saveLicensees( $licenseesClub_fftt );
        $this->output->writeln( "END - SAVE > saveLicensees" );*/

        /*$this->output->writeln( "START - SAVE > saveLicenseesMatchs" );
        $this->saveLicenseesMatchs( $licenseesClub_fftt );
        $this->output->writeln( "END - SAVE > saveLicenseesMatchs" );*/

        $this->output->writeln( "END - IMPORT <<<" );

        $this->entityManager->flush();
    }

    private function getParameter( $type ) {

        $parameter = $this->entityManager->getRepository('TableTennisFfttBundle:Parameter')
                                         ->findOneBy(array('type' => $type));

        return $parameter;
    }

    private function saveParameter( $parameter ) {
        switch( $parameter->getType() ) {
            case 'licensee':
                $nexUpdate = new \DateTime();
                $nexUpdate->modify('+1 day');
                //$nexUpdate = clone( $parameter->getNextUpdate()->modify('+1 day') );
                break;
            case 'licensee_point':
                $nexUpdate = new \DateTime();
                $nexUpdate->modify('+1 day');
                //$nexUpdate = clone( $parameter->getNextUpdate()->modify('+1 day') );
                break;
        }

        $parameter->setNextUpdate( $nexUpdate );

        $this->entityManager->persist($parameter);
    }

    private function saveLicensees( $licensee_list, $licenseesClub_fftt ) {

        foreach( $licenseesClub_fftt as $licenseeClub ) {

            $this->saveLicensee( $licensee_list, $licenseeClub );
        }
    }

    private function saveLicensee( $licensee_list, $licenseeClub ) {

        $licenseeNumber = (string) $licenseeClub['licence'];

        $licenseeClub_fftt = $this->service->getJoueur( $licenseeNumber );

        $lastname = $licenseeClub['nom'];
        $firstname = $licenseeClub['prenom'];
        $club = $licenseeClub['club'];
        $clubNumber = $licenseeClub['nclub'];

        $globalClassement = $licenseeClub_fftt['clglob'];
        $nbCurrentPoints = $licenseeClub_fftt['point'];
        //$??? = $licenseeClub_fftt['aclglob'];
        $lastMonthPoint = $licenseeClub_fftt['apoint'];
        //$??? = $licenseeClub_fftt['clast'];
        //$nationalClassement = $licenseeClub_fftt['clnat'];
        $category = $licenseeClub_fftt['categ'];
        //$regionalPosition = $licenseeClub_fftt['rangreg'];
        //$departmentPosition = $licenseeClub_fftt['rangdep'];
        //$??? = $licenseeClub_fftt['valcla'];
        $ranking = $licenseeClub_fftt['clpro'];
        $initSeasonPoints = $licenseeClub_fftt['valinit'];

        //$this->output->writeln( $licenseeClub_fftt );

        $licensee = $this->entityManager->getRepository('TableTennisLicenseeBundle:Licensee')
                                        ->findOneBy(array('licensee_number' => $licenseeNumber));

        // Ajout du licencié
        $isNewLicensee = FALSE;
        if( !$licensee ) {
            $licensee = new Licensee();
            $isNewLicensee = TRUE;
        }

        // MAJ du licencié
        $licensee->setLicenseeNumber( $licenseeNumber );
        $licensee->setLastname( $lastname );
        $licensee->setFirstname( $firstname );
        $licensee->setRanking( $ranking );
        $licensee->setNbCurrentPoints( $nbCurrentPoints );
        $licensee->setCategory( $category );
        $licensee->setMonthlyIncrease( $nbCurrentPoints - $lastMonthPoint );


        // Licenciés à désactiver
        $findLicensee = FALSE;
        foreach( $licensee_list as $licensee_checking ) {
            if( ( $licensee_checking["licensee_number"] == $licenseeNumber ) || $isNewLicensee ) {
                $findLicensee = TRUE;
            }
        }

        if( !$findLicensee ) {
            $this->output->writeln( " TO_DELETE - " . $lastname . " " . $firstname . " - " . $licenseeNumber );
            $licensee->setIsDeleted(true);
        } else {
            $licensee->setIsDeleted(false);
        }

        // Save Licensee
        $this->entityManager->persist($licensee);

        if( empty( $lastname ) ) {
            $this->output->writeln( $lastname . " " . $firstname . " - " . $licenseeNumber );
        }

        // Mise à jour de l'historique des points
        /*$currentDate = new \DateTime();
        $currentDate->setDate( date('Y'), date('m'), '01'); // Premier jour du mois en cours
        $currentDate->setTime(0, 0, 0);

        $licenseePoint = $em->getRepository('TableTennisLicenseeBundle:LicenseePoint')
                            ->findBy(array('licensee_id' => $licensee->getId(),
                                           'datetime_points' => $currentDate ));

        if( empty( $licenseePoint ) ){
            $licenseePoint = new LicenseePoint();
            
            $licenseePoint->setLicenseeId( $licensee );
            $licenseePoint->setDatetimePoints( $currentDate );
        }else{
            $licenseePoint = $licenseePoint[0];
        }
        
        $licenseePoint->setNbPointsFftt( $nbCurrentPoints );

        // Save Licensee Point
        $em->persist($licenseePoint);*/
    }

    private function saveLicenseesPoints( $licenseesClub_fftt ) {

    }

    private function saveLicenseesMatchs( $licenseesClub_fftt ) {

        /*foreach( $licenseesClub_fftt as $licenseeClub ) {
            $licenseeNumber = (string) $licenseeClub['licence'];

            $licenseeMatchs_fftt = $service->getJoueurParties( $licenseeNumber );

            var_dump( $licenseeNumber );
            die();
        }*/
    }
}