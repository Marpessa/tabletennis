<?php

// src/Base/ForumBundle/DataFixtures/ORM/LoadForumPostData.php
namespace Base\ForumBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\ForumBundle\Entity\Post;

class LoadForumPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_forum_post.php');



        foreach( $dataList as $data )
        {
            if( !is_null( $data["user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["user_id"] );
            }
            /*if( !is_null( $data["modification_user_id"] ) ) {
                $modificationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["modification_user_id"] );
            }*/

            if( !is_null( $data["forum_id"] ) ) {
                $forumTopic = $manager->getRepository('Base\ForumBundle\Entity\Topic')->find( $data["topic_id"] );
            }

            $forumPost = new \Base\ForumBundle\Entity\Post();

            $forumPost->setId( $data["id"] );

            $forumPost->setTopic( $forumTopic );

            $content = $data["content"];
            $content = str_replace( array( '/js/jquery/tiny_mce/', 'js/tiny_mce/' ), array( '/js/tiny_mce/', '/js/tiny_mce/' ), $content );
            $content = str_replace( array( '//js/tiny_mce/' ), array( '/js/tiny_mce/' ), $content );

            $forumPost->setMessage( $content );
            $forumPost->setNumber( 0 );

            if( isset( $creationUser ) ) {
                $forumPost->setCreationUserId( $creationUser );
            }
            /*if( isset( $modificationUser ) ) {
                $forumPost->setModificationUserId( $modificationUser );
            }*/
            $forumPost->setCreatedAt( new \DateTime( $data["created_at"] ) );
            $forumPost->setUpdatedAt( new \DateTime( $data["updated_at"] ) );


            $manager->persist($forumPost);

            // Pour forcer l'id
            $metadata = $manager->getClassMetaData(get_class($forumPost));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();
        }
    }

    /**
     * The order in which these fixtures will be loaded.
     */
    public function getOrder()
    {
        return 5;
    }
}

?>