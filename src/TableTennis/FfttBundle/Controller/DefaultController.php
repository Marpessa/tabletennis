<?php

namespace TableTennis\FfttBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

use TableTennis\FfttBundle\Command\FfttCommandTest;

class DefaultController extends Controller
{
    public function cronImportFfttDataAction()
    {
        $ffttCommandTest = new FfttCommandTest();
        $display = $ffttCommandTest->testExecute();

        var_dump( $display );
        die();

        $url = $this->container->get('router')->generate('_homepage');
        $response = new RedirectResponse($url);

        return $response;
    }    
    
}