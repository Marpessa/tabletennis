<?php

// src/Base/CategoryBundle/DataFixtures/ORM/LoadCategoryData.php
namespace Base\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\CategoryBundle\Entity\Category;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_category.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !empty( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }

            $category = new Category();
            $category->setId( $data["id"] );
            if( isset( $creationUser ) ) {
                $category->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $category->setModificationUserId( $modificationUser );
            }
            $category->setTitle( $data["title"] );
            
            $type = NULL;
            switch( $data["type"] )
            {
                case "menu": $type = "menu"; break;
                case "footer": $type = "footer"; break;
                case "news": $type = "news"; break;
                case "photo": $type = "picture"; break;
                case "video": $type = "video"; break;

            }
            $category->setType( $type );
            $category->setLink( $data["link"] );
            $category->setNumOrder( $data["num_order"] );
            $category->setIsPublished( $data["publication"] == "published" ? 1 : 0 );
            $category->setCategoryParentId( $data["category_parent_id"] );
            // $category->setSlug();
            $category->setCreatedAt( new \DateTime($data["created_at"]) );
            $category->setUpdatedAt( new \DateTime($data["updated_at"]) );

            $manager->persist($category);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($category));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            //$manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 2;
    }
}

?>