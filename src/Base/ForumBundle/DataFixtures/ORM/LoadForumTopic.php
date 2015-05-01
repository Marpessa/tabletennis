<?php

// src/Base/ForumBundle/DataFixtures/ORM/LoadForumTopicData.php
namespace Base\ForumBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\ForumBundle\Entity\Topic;

class LoadForumTopicData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_forum_topic.php');

        

        foreach( $dataList as $data )
        {
            if( !is_null( $data["user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["user_id"] );
            }
            /*if( !is_null( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }*/

            if( !is_null( $data["forum_id"] ) ) {
                $forumCategory = $manager->getRepository('Base\ForumBundle\Entity\Category')->find( $data["forum_id"] );
            }

            $forumTopic = new \Base\ForumBundle\Entity\Topic();

            $forumTopic->setId( $data["id"] );

            $forumTopic->setCategory( $forumCategory );
            $forumTopic->setSubject( $data["title"] );
            $forumTopic->setNumViews( $data["nb_views"] );
            $forumTopic->setNumPosts( $data["nb_posts"] );
            $forumTopic->setIsClosed( $data["is_locked"] );
            $forumTopic->setIsPinned( $data["is_sticked"] );
            
            if( isset( $creationUser ) ) {
                $forumTopic->setCreationUserId( $creationUser );
            }
            /*if( isset( $modificationUser ) ) {
                $forumCategory->setModificationUserId( $modificationUser );
            }*/
            // $forumCategory->setSlug();
            $forumTopic->setCreatedAt( new \DateTime( $data["created_at"] ) );
            $forumTopic->setPulledAt( new \DateTime( $data["updated_at"] ) );


            $manager->persist($forumTopic);

            // Pour forcer l'id
            $metadata = $manager->getClassMetaData(get_class($forumTopic));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 4;
    }
}

?>