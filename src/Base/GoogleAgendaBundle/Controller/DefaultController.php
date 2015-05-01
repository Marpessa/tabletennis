<?php

namespace Base\GoogleAgendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Base\GoogleAgendaBundle\Api\GoogleAgendaApi;



class DefaultController extends Controller
{
    public function homeAction() {
        
        // Cache
        $response = new Response();
        $response->setMaxAge(24*60*60);
        $response->setSharedMaxAge(24*60*60);
        $response->setPublic();
        
        if ($response->isNotModified($this->getRequest())) {
            return $response; // this will return the 304 if the cache is OK
        }
        
        list( $yearNumber, $monthNumber, $prevMonth, $prevYear, $nextMonth, $nextYear, $firstday, $nbdays ) = $this->initCalendarData( NULL, NULL );
        list( $z, $calendar ) = $this->getCalendar( $firstday, $nbdays );
        
        $daysArray = $this->getDaysArray();
        $currentMonth = $this->getCurrentMonth( $monthNumber );
        
        $gEvents = $this->getGoogleEvents( $yearNumber, $monthNumber, $nextMonth, $nextYear );
        $event_list = $this->getEvents();
        
        return $this->render('BaseGoogleAgendaBundle:Default:home.html.twig', array( 'calendar' => $calendar,
                                                                                     'daysArray' => $daysArray,
                                                                                     'monthNumber' => $monthNumber,
                                                                                     'yearNumber' => $yearNumber,
                                                                                     'prevMonth' => $prevMonth,
                                                                                     'prevYear' => $prevYear,
                                                                                     'nextMonth' => $nextMonth,
                                                                                     'nextYear' => $nextYear,
                                                                                     'currentMonth' => $currentMonth,
                                                                                     'nbdays' => $nbdays,
                                                                                     'z' => $z,
                                                                                     'gEvents' => $gEvents,
                                                                                     'event_list' => $event_list
                                                                                   ), $response );
    }
    
    public function changeMonthAction( $year, $month ) {
        
        $request = $this->container->get('request');
        
        if($request->isXmlHttpRequest())
        {
            list( $yearNumber, $monthNumber, $prevMonth, $prevYear, $nextMonth, $nextYear, $firstday, $nbdays ) = $this->initCalendarData( $year, $month );
            list( $z, $calendar ) = $this->getCalendar( $firstday, $nbdays );

            $daysArray = $this->getDaysArray();
            $currentMonth = $this->getCurrentMonth( $monthNumber );

            $gEvents = $this->getGoogleEvents( $yearNumber, $monthNumber, $nextMonth, $nextYear );
            $event_list = $this->getEvents();

            return $this->container->get('templating')->renderResponse('BaseGoogleAgendaBundle:Default:home.html.twig', array( 'calendar' => $calendar,
                                                                                                                               'daysArray' => $daysArray,
                                                                                                                               'monthNumber' => $monthNumber,
                                                                                                                               'yearNumber' => $yearNumber,
                                                                                                                               'prevMonth' => $prevMonth,
                                                                                                                               'prevYear' => $prevYear,
                                                                                                                               'nextMonth' => $nextMonth,
                                                                                                                               'nextYear' => $nextYear,
                                                                                                                               'currentMonth' => $currentMonth,
                                                                                                                               'nbdays' => $nbdays,
                                                                                                                               'z' => $z,
                                                                                                                               'gEvents' => $gEvents,
                                                                                                                               'event_list' => $event_list
                                                                                                                              ) );
        }
        else
        {
            return $this->homeAction();
        }
    }
    
    private function getGoogleEvents( $yearNumber, $monthNumber, $nextMonth, $nextYear ) {

        $dateStartMin = new \Datetime( $yearNumber . '-' . $monthNumber . '-' . '01' );
        $nbdays = \date("t", mktime( 0, 0, 0, $nextMonth, 1, $nextYear ));
        $dateStartMax = new \Datetime( $nextYear . '-' . $nextMonth . '-' . $nbdays );
        
        $url = $this->container->getParameter('google.calendar.uri');

        $oAgenda = new GoogleAgendaApi( $url, false );
        
        // Le tableau d'options suivant contient les valeurs par défaut
        $gEvents = $oAgenda->getEvents(array(
            'startmin' => $dateStartMin->format( 'Y-m-d' ),
            'startmax' => $dateStartMax->format( 'Y-m-d' ),
            'sortorder' => 'ascending',
            'orderby' => 'starttime',
            'maxresults' => '',
            'startindex' => '1',
            'search' => '',
            'singleevents' => 'true',
            'futureevents' => 'false',
            'timezone' => 'Europe/Paris',
            'showdeleted' => 'false'
        ));
        
        return $gEvents;
    }
    
    private function getEvents() {
        $event_list = $this->getDoctrine()
                           ->getRepository('BaseEventBundle:Event')
                           ->findAllOrderedByUpdatedAt(3);
        
        return $event_list;
    }
    
    private function getCalendar( $firstday, $nbdays ) {
        $calendar = array();
        
        $z = (int) $firstday;
        if($z == 0) $z =7;
        
        for($i = 1; $i <= ($nbdays/5); $i++) {
            for($j = 1; $j <= 7 && $j-$z+1+(($i*7)-7) <= $nbdays; $j++) {
                if($j < $z && ($j-$z+1+(($i*7)-7)) <= 0) {
                    $calendar[$i][$j] = null;
                } else {
                    $calendar[$i][$j] = $j-$z+1+(($i*7)-7);
                }
            }
        }
        
        return array( $z, $calendar );
    }
    
    private function getDaysArray() {
        $daysArray = array();
                
        $daysArray[] = 'Lu';
        $daysArray[] = 'Ma';
        $daysArray[] = 'Me';
        $daysArray[] = 'Je';
        $daysArray[] = 'Ve';
        $daysArray[] = 'Sa';
        $daysArray[] = 'Di';
        
        return $daysArray;
    }
    
    private function getCurrentMonth( $monthnb ) {
        $currentMonth = "";
        
        switch( $monthnb ) {
            case 1: $currentMonth = 'Janvier'; break;
            case 2: $currentMonth = 'Fevrier'; break;
            case 3: $currentMonth = 'Mars'; break;
            case 4: $currentMonth = 'Avril'; break;
            case 5: $currentMonth = 'Mai'; break;
            case 6: $currentMonth = 'Juin'; break;
            case 7: $currentMonth = 'Juillet'; break;
            case 8: $currentMonth = 'Août'; break;
            case 9: $currentMonth = 'Septembre'; break;
            case 10: $currentMonth = 'Octobre'; break;
            case 11: $currentMonth = 'Novembre'; break;
            case 12: $currentMonth = 'Décembre'; break;
        }
        
        return $currentMonth;
    }
    
    private function initCalendarData( $yearNumber, $monthNumber ) {
        
        if( empty( $monthNumber ) ) {
            $monthNumber = date("n");
            $yearNumber = \date("Y");
        } else {            
            if($monthNumber <= 0) {
                $monthNumber = 12;
                $yearNumber = $yearNumber - 1;
            } elseif($monthNumber > 12) {
                $monthNumber = 1;
                $yearNumber = $yearNumber + 1;
            }            
        }
        
        $prevMonth = $monthNumber - 1;
        $nextMonth = $monthNumber + 1;
        $prevYear = $yearNumber;
        $nextYear = $yearNumber;
        
        if($prevMonth <= 0) {
            $prevMonth = 12;
            $prevYear = $yearNumber - 1;
        }

        if($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear = $yearNumber + 1;
        }
        
        $firstday = \date("w",mktime( 0, 0, 0, $monthNumber, 1, $yearNumber ));
        $nbdays = \date("t", mktime( 0, 0, 0, $monthNumber, 1, $yearNumber ));
        
        return array( $yearNumber, $monthNumber, $prevMonth, $prevYear, $nextMonth, $nextYear, $firstday, $nbdays );
    }
}