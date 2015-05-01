<?php

// src/Base/CommentBundle/DataFixtures/ORM/LoadCommentData.php
namespace Base\CommentBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Base\CommentBundle\Entity\Comment;

class LoadCommentData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        require_once(__DIR__.'/../../../../../external_scripts/DB.php');
        require_once(__DIR__.'/../../../../../external_scripts/load_comment.php');

        foreach( $dataList as $data )
        {
            if( !empty( $data["user_id"] ) ) {
                $creationUser = $manager->getRepository('Application\Sonata\UserBundle\Entity\User')->find( $data["user_id"] );
            }

            $comment = new Comment();
            $comment->setId( $data["id"] );

            $comment->setEmail( $data["author_email"] );
            $comment->setName( $data["author_name"] );
            $comment->setContent( $data["body"] );
            $comment->setIsPublished( empty( $data["is_delete"] ) ? TRUE : FALSE );

            $link = "/app_dev.php/fr/";
            switch( $data["record_model"] ){
                case "Event": $link .= "evenements/"; break;
                case "News": $link .= "actualites/"; break;
                case "sfAssetMedia": $link .= "images/"; break;
            }

            $updated_at = new \DateTime($data["updated_at"]);
            $link .= $updated_at->format( "Y" ) . "/" . $updated_at->format( "m" );

            $link .= "/" . $data["record_slug"] . ".html";

            $comment->setLink( $link );

            if( isset( $creationUser ) ) {
                $comment->setUserId( $creationUser );
            }

            $comment->setCreatedAt( new \DateTime($data["created_at"]) );
            $comment->setUpdatedAt( new \DateTime($data["updated_at"]) );

            $manager->persist($comment);

            /* Pour forcer l'id */
            $metadata = $manager->getClassMetaData(get_class($comment));
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);

            $manager->flush();

        }
    }
}

?>