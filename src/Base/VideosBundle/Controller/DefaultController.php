<?php

namespace Base\VideosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function showAction($id_playlist, $slug)
    {
        // Cache
        /*$response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }*/
        
        $videos_list = array();
        $videos_list_json = array();

        try
        {
            $uri = 'https://gdata.youtube.com/feeds/api/playlists/' . $id_playlist . '?v=2';
            $videos_list = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

            $json = json_encode( $videos_list );
            $videos_list = json_decode($json, true);

            foreach($videos_list["entry"] as $video ){
                $videos_list_json[] =  array( "title" => $video["title"],
                                              "video_uri" => $video["content"]["@attributes"]["src"] );
            }

        }catch(Exception $e){

        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Vidéos', $this->get("router")->generate( '_baseVideosIndex' ));
        $breadcrumbs->addItem( $videos_list["title"] );

        return $this->render('BaseVideosBundle:Default:show.html.twig', array( 'playlist_info' => $videos_list, 'videos_list' => $videos_list_json )/*, $response*/);
    }


    public function indexAction()
    {
        // Cache
        /*$response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();

        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }*/
        
        $playlist_list = array();
        $playlist_list_array = array();

        try
        {
            $user_id = $this->container->getParameter('youtube_api.user_id');

            $uri = 'https://gdata.youtube.com/feeds/api/users/' . $user_id . '/playlists?v=2';
            $playlist_list = simplexml_load_file( $uri, "SimpleXMLElement", LIBXML_NOCDATA );

            foreach($playlist_list->entry as $playlist ){
                $uri_playlist = (string) $playlist->content["src"];                

                $playlist_content = simplexml_load_file( $uri_playlist );

                $group = $playlist_content->children('http://www.w3.org/2005/Atom')
                                          ->entry[0]
                                          ->children('http://search.yahoo.com/mrss/')
                                          ->group
                                          ->children('http://search.yahoo.com/mrss/');

                $thumbs_playlist = array();
                foreach( $group->thumbnail as $thumb) {
                    $thumbs_playlist[] = $thumb->attributes()->url;
                }

                $id_playlist = explode( ":", $playlist_content->id );
                $id_playlist = end( $id_playlist );

                $playlist_list_array[] = array( "id" => $id_playlist,
                                                "title" => $playlist_content->title,
                                                "slug" => $this->slugify( $playlist_content->title ),
                                                "totalResults" => $playlist_content->children('openSearch', true)->totalResults,
                                                "thumb_uri" => $thumbs_playlist[1] );
            }

        }catch(Exception $e){

        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Vidéos' );

        return $this->render('BaseVideosBundle:Default:index.html.twig', array( 'playlist_list' => $playlist_list_array )/*, $response*/);
    }

    private function slugify($text) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}

?>