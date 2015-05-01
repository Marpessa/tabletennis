<?php

// src/Base/NewsBundle/DataFixtures/ORM/LoadNewsData.php
namespace Base\NewsBundle\DataFixtures\ORM;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use Base\NewsBundle\Entity\News;

class LoadNewsData implements FixtureInterface, ContainerAwareInterface
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
        require_once( $basePathProject . 'external_scripts/load_news.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }
            if( !empty( $data["category_id"] ) ) {
                $category = $manager->getRepository('Base\CategoryBundle\Entity\Category')->find( $data["category_id"] );
            }
            if( LOADIMAGES && !empty( $data["asset_id"] ) && !empty( $data['asset']['updated_at'] ) ) {
                $media = new \Application\Sonata\MediaBundle\Entity\Media;
                $media->setProviderName('sonata.media.provider.image');
                $media->setContext('news');

                $updated_at = new \DateTime( $data['asset']['updated_at'] );

                $uriImg = 'http://www.cpfaizenay.com/media/actualites/' . $updated_at->format('Y-m') . '/thumbnail/large_' . $data['asset']['filename'];
                $uriImgTmp = $basePathProject . 'web/tmp/' . $data["id"] . '.jpg';

                $file_content = @file_get_contents( $uriImg );

                if( $file_content ) {
                    $img = file_put_contents( $uriImgTmp , $file_content );

                    $media->setBinaryContent( $uriImgTmp );
                    $media->setName( $data["title"] );
                    $media->setEnabled( TRUE );

                    $mediaManager = $this->container->get('sonata.media.manager.media');
                    $mediaManager->save($media);
                }else{
                    $media = NULL;
                }


                @unlink( $uriImgTmp );
                
            }

            $news = new News();
            $news->setId( $data["id"] );
            if( isset( $category ) ) {
                $news->setCategoryId( $category );
            }
            // $news->setMediaId($mediaId);
            if( isset( $creationUser ) ) {
                $news->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $news->setModificationUserId( $modificationUser );
            }
            if( isset( $media ) ) {
                $news->setMediaId($media);
            }
            $news->setTitle( $data["title"] );
            $news->setContent( $data["text"] );
            $news->setType( $data["kind"] );
            $news->setIsPublished( $data["publication"] == "published" ? TRUE : FALSE );
            // $club->setSlug();
            $news->setCreatedAt( new \DateTime($data["created_at"]) );
            $news->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($news);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($news));
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