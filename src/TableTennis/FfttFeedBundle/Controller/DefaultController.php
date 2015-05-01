<?php

namespace TableTennis\FfttFeedBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use TableTennis\FfttFeedBundle\Entity\FfttFeed;

class DefaultController extends Controller
{
    /**
     * @Cache(expires="tomorrow")
     */
    public function homeAction()
    {
        $ffttFeed_list = $this->getDoctrine()
                              ->getRepository('TableTennisFfttFeedBundle:FfttFeed')
                              ->getFfttFeeds()
                              ->getArrayResult();

        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }

        $feed_fftt_tv = array();
        $feed_fftt_com = array();
        
        $em = $this->getDoctrine()->getManager();

        $connection = $em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('fftt_feed', true )); /* whether to cascade */

        try
        {
            /* Récupérer du flux rss fftt.com */
            $error = FALSE;

            $array_postvars = array();

            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, $this->container->getParameter('feeds_fftt_com_xml') );
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $array_postvars));
            $feeds_fftt_com_result = curl_exec($ch); // run the whole process

            if ( curl_errno($ch) != 0 ) // CURL error
             $error = TRUE;

            curl_close($ch);

            if( !$error )
            {
                $feeds_fftt_com_xml = simplexml_load_string($feeds_fftt_com_result);

                foreach( $feeds_fftt_com_xml->channel->item as $node ) {
                    if( count($feed_fftt_com) < 2 ){
                        $feed_fftt_com[] = $node;

                        $datetimePublication = new \DateTime();
                        $datetimePublication->setTimestamp( strtotime( (string) $node->pubDate ) );

                        $ffttFeed = new FfttFeed();
                        $ffttFeed->setTitle( (string) $node->title );
                        $ffttFeed->setDescription( (string) $node->description );
                        $ffttFeed->setDatetimePublication( $datetimePublication );
                        $ffttFeed->setWebsite( 'http://www.fftt.com' );

                        $em->persist($ffttFeed);
                    }
                }
            }
            else
            {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
            }


            /* Récupérer du flux rss fftt.tv */
            $ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, $this->container->getParameter('feeds_fftt_tv_xml') );
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $array_postvars));
            $feeds_fftt_tv_result = curl_exec($ch); // run the whole process

            if ( curl_errno($ch) != 0 ) // CURL error
             $error = TRUE;

            curl_close($ch);

            if( !$error )
            {
                $feeds_fftt_tv_xml = simplexml_load_string($feeds_fftt_tv_result);

                foreach( $feeds_fftt_tv_xml->channel->item as $node ) {
                    if( count($feed_fftt_tv) < 1 ){
                        $feed_fftt_tv[] = $node;

                        $datetimePublication = new \DateTime();
                        $datetimePublication->setTimestamp( strtotime( (string) $node->pubDate ) );

                        $ffttFeed = new FfttFeed();
                        $ffttFeed->setTitle( (string) $node->title );
                        $ffttFeed->setDescription( (string) $node->description );
                        $ffttFeed->setDatetimePublication( $datetimePublication );
                        $ffttFeed->setWebsite( 'http://www.fftt.tv' );

                        $em->persist($ffttFeed);
                    }
                }
            }
            else
            {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
            }
        }catch(Exception $e){

        }

        $em->flush();

        // Render
        return $this->render('TableTennisFfttFeedBundle:Default:home.html.twig',
                             array( 'feed_fftt_tv' => $feed_fftt_tv,
                                    'feed_fftt_com' => $feed_fftt_com ),
                             $response);
    }
}