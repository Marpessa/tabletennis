<?php

// src/TableTennis/TeamBundle/DataFixtures/ORM/LoadTeamData.php
namespace TableTennis\TeamBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use TableTennis\TeamBundle\Entity\Team;

class LoadTeamData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $basePathProject = __DIR__. '/../../../../../';

        require_once( $basePathProject . 'external_scripts/DB.php');
        require_once( $basePathProject . 'external_scripts/load_team.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }
            if( LOADIMAGES && !empty( $data["asset_id"] ) && !empty( $data['asset']['updated_at'] ) ) {
                $media = new \Application\Sonata\MediaBundle\Entity\Media;
                $media->setProviderName('sonata.media.provider.image');
                $media->setContext('team');

                $updated_at = new \DateTime( $data['asset']['updated_at'] );

                $uriImg = 'http://www.cpfaizenay.com/media/equipes/' . $updated_at->format('Y-m') . '/thumbnail/large_' . $data['asset']['filename'];
                $uriImgTmp = $basePathProject . 'web/tmp/' . $data["id"] . '.jpg';
                
                $file_content = @file_get_contents( $uriImg );

                if( $file_content ) {
                    $img = file_put_contents( $uriImgTmp , $file_content );

                    $media->setBinaryContent( $uriImgTmp );
                    $media->setName( $data["name"] );
                    $media->setEnabled( TRUE );

                    $mediaManager = $this->container->get('sonata.media.manager.media');
                    $mediaManager->save($media);
                }else{
                    $media = NULL;
                }

                @unlink( $uriImgTmp );
            }

            $team = new Team();
            $team->setId( $data["id"] );
            
            if( isset( $creationUser ) ) {
                $team->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $team->setModificationUserId( $modificationUser );
            }
            if( isset( $media ) ) {
                $team->setMediaId($media);
            }
            
            $team->setName( $data["name"] );
            $team->setContent( $data["content"] );
            $team->setTeamCategory( $data["team_category"] );
            $team->setNumOrder( $data["num_order"] );
            // $announcement->setSlug();
            $team->setCreatedAt( new \DateTime($data["created_at"]) );
            $team->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($team);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($team));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 3;
    }
}

?>