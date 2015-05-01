<?php

namespace TableTennis\PointsCalculationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use TableTennis\PointsCalculationBundle\Form\PointsCalculationForm;
use TableTennis\PointsCalculationBundle\Gain\Gain;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();
        $matchType_list = $em->getRepository('TableTennisMatchTypeBundle:MatchType')->findAll();

         
        $form = $this->createForm(new PointsCalculationForm($matchType_list));

        $request = $this->getRequest();
        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));

            if ($form->isValid()) {
                
                $em = $this->container->get('doctrine')->getManager();
                $scoringTable_list = $em->getRepository('TableTennisScoringTableBundle:ScoringTable')->findAll();

               
                $results = $request->request->get('points_calculation');

                $id_match_type = $results["match_type"];
                $mensual_points = $results["mensual_points"];

                $matchType = NULL;
                if( !empty( $id_match_type ) ){
                    $matchType = $em->getRepository('TableTennisMatchTypeBundle:MatchType')
                                    ->getCurrentMatchType($id_match_type)
                                    ->getSingleResult();
                }

                $opponents_results = array();
                $i = 0;
                foreach( $results as $key => $result ) {
                    if( $key == "opponent_point_" . $i ) {

                        $opponent_status = $results[ "opponent_status_" . $i ];
                        $opponent_point = $results[ "opponent_point_" . $i ];

                        $gain = Gain::getGain($mensual_points, $opponent_point, $scoringTable_list, $opponent_status);

                        $opponents_results[$i] = array( "opponent_point" => $opponent_point,
                                                        "opponent_status" => $opponent_status,
                                                        "gain" => $gain
                                                      );

                        $this->get('session')->set('opponents_results', $opponents_results);

                        $i++;
                    }
                }
                
                $this->get('session')->set('mensual_points', $mensual_points);
                $this->get('session')->set('id_match_type', $id_match_type);

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('_tableTennisPointsCalculationResults'));
            }
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem("Calcul de vos points sur les matchs de tennis de table");

        return $this->render('TableTennisPointsCalculationBundle:Default:index.html.twig', array( 'form' => $form->createView() ));
    }

    public function resultsAction()
    {
        $session = $this->get('session');

        $mensual_points = $session->get('mensual_points');
        $id_match_type = $session->get('id_match_type');
        $opponents_results = $session->get('opponents_results');

        $session->remove('mensual_points');
        $session->remove('id_match_type');
        $session->remove('opponents_results');

        if( empty( $mensual_points ) || empty( $id_match_type ) || empty( $opponents_results ) ) {
            return $this->redirect($this->generateUrl('_tableTennisPointsCalculationIndex'));
        }

        $em = $this->container->get('doctrine')->getManager();
        $matchType = $em->getRepository('TableTennisMatchTypeBundle:MatchType')
                        ->getCurrentMatchType($id_match_type)
                        ->getSingleResult();

        /* Breadcrumbs */
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Accueil", $this->get("router")->generate("_homepage"));
        $breadcrumbs->addItem("Calcul de vos points sur les matchs de tennis de table");

        return $this->render('TableTennisPointsCalculationBundle:Default:results.html.twig', array( 'mensual_points' => $mensual_points,
                                                                                                    'matchType' => $matchType,
                                                                                                    'opponents_results' => $opponents_results,
                                                                                                  ));

    }

}