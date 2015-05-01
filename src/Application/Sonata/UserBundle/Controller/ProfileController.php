<?php

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sonata\UserBundle\Controller\ProfileFOSUser1Controller as ProfileFOSUser1Controller;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends ProfileFOSUser1Controller
{

    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $url = $this->container->get('router')->generate('_sonataUserProfileDashboard');
        $response = new RedirectResponse($url);

        return $response;
       /* return $this->render('SonataUserBundle:Profile:show.html.twig', array(
            'user'   => $user,
            'blocks' => $this->container->getParameter('sonata.user.configuration.profile_blocks')
        ));*/
    }

    /**
     * Edit the user
     */
    public function editProfileGeneralInfosAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);

        if ($process) {
            $user = $form->getData();

            $licenseeData = $user->getLicensee();

            if( !empty( $licenseeData[ 'licensee_number' ] ) ) {

                $licensee = new \TableTennis\LicenseeBundle\Entity\Licensee();
                $licensee->setCategory( $licenseeData[ 'licensee_category' ] );
                $licensee->setLastname( $user->getLastname() );
                $licensee->setFirstname( $user->getFirstname() );
                $licensee->setLicenseeNumber( $licenseeData[ 'licensee_number' ] );

                $user->setLicenseeNumber( $licenseeData[ 'licensee_number' ] );

                $em = $this->container->get('doctrine.orm.entity_manager');
                $em->persist($licensee);
                $em->persist($user);
                $em->flush();

            }

            $url = $this->container->get('router')->generate('_sonataUserProfileDashboard');
            $response = new RedirectResponse($url);
            
            return $response;
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->container->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->container->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Profil de ' . $user->getLastname() . ' ' . $user->getFirstname(), $this->container->get("router")->generate( '_sonataUserProfileDashboard' ));
        $breadcrumbs->addItem( 'Modifier mes informations générales' );

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView() )
        );
    }


}
