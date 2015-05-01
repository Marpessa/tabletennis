<?php

// src/Base/UserBundle/DataFixtures/ORM/LoadSonataUserData.php
namespace Application\Sonata\UserBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use Application\Sonata\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
        $basePathProject = __DIR__. '/../../../../../../';

        require_once( $basePathProject . 'external_scripts/DB.php');
        require_once( $basePathProject . 'external_scripts/load_user.php');

        // $userManager = $this->container->get('fos_user.user_manager');

        foreach( $dataList as $data )
        {
            if( LOADIMAGES && !empty( $data["asset_id"] ) && !empty( $data['asset']['updated_at'] ) ) {
                $media = new \Application\Sonata\MediaBundle\Entity\Media;
                $media->setProviderName('sonata.media.provider.image');
                $media->setContext('avatar');

                $updated_at = new \DateTime( $data['asset']['updated_at'] );

                $uriImg = 'http://www.cpfaizenay.com/media/avatar/' . $updated_at->format('Y-m') . '/thumbnail/large_' . $data['asset']['filename'];
                $uriImgTmp = $basePathProject . 'web/tmp/' . $data["id"] . '.jpg';

                $file_content = @file_get_contents( $uriImg );

                if( $file_content ) {
                    $img = file_put_contents( $uriImgTmp , $file_content );

                    $media->setBinaryContent( $uriImgTmp );
                    $media->setName( $data["username"] );
                    $media->setEnabled( TRUE );

                    $mediaManager = $this->container->get('sonata.media.manager.media');
                    $mediaManager->save($media);
                }else{
                    $media = NULL;
                }

                @unlink( $uriImgTmp );
            }

            $user = new User();
            //$user = $userManager->createUser();
            $user->setId( $data["id"] );
            
            $user->setLastName( $data["last_name"] );
            $user->setFirstName( $data["first_name"] );
            $user->setEmail( $data["email_address"] );
            $user->setUsername( $data["username"] );
            
            $user->setPassword( $data["password"] );

            if( !empty( $data["salt"] ) ) {
                $user->setSalt( $data["salt"] );
            }else{
                $user->setSalt( "" );
            }

            $user->setAlgorithm( $data["algorithm"] );

            $user->setGender( $data["type"] );
            $user->setEnabled( $data["is_active"] );
            $user->setLastLogin( new \DateTime( $data["last_login"] ) );

            $user->setLocked( $data["is_delete"] );
            if( $data["is_super_admin"] == 1 ) {
                $user->setRoles(array('ROLE_ADMIN'));
            }

            if( isset( $media ) ) {
                $user->setMediaId($media);
            }

            $user->setCreatedAt( new \DateTime($data["created_at"]) );
            $user->setUpdatedAt( new \DateTime($data["updated_at"]) );

            $manager->persist($user);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($user));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            //$userManager->updateUser($user);
            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 1;
    }
}

?>