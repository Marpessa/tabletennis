<?php

// src/Base/ForumBundle/DataFixtures/ORM/LoadForumCategoryData.php
namespace Base\ForumBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\ForumBundle\Entity\Category;

class LoadForumCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_forum_forum.php');

        

        foreach( $dataList as $data )
        {
            /*if( !is_null( $data["creation_user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["creation_user_id"] );
            }
            if( !is_null( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }*/

            $forumCategory = new \Base\ForumBundle\Entity\Category();

            $forumCategory->setId( $data["id"] );

            $forumCategory->setName( $data["name"] );
            $forumCategory->setDescription( $data["description"] );
            $forumCategory->setPosition( $data["rank"] );
            $forumCategory->setNumTopics( $data["nb_topics"] );
            $forumCategory->setNumPosts( $data["nb_posts"] );
            
            /*if( isset( $creationUser ) ) {
                $forumCategory->setCreationUserId( $creationUser );
            }
            if( isset( $modificationUser ) ) {
                $forumCategory->setModificationUserId( $modificationUser );
            }*/
            // $announcement->setSlug();
            //$forumCategory->setCreatedAt( new \DateTime($data["created_at"]) );
            //$forumCategory->setUpdatedAt( new \DateTime($data["updated_at"]) );


            $manager->persist($forumCategory);

            // Pour forcer l'id
            $metadata = $manager->getClassMetaData(get_class($forumCategory));
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