<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;

use FOS\UserBundle\Controller\RegistrationController as FOSRegistrationController;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;


/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends FOSRegistrationController
{
    public function registerAction()
    {
        /*$user = $this->container->get('security.context')->getToken()->getUser();
        if (is_object($user) && $user instanceof \Application\Sonata\UserBundle\Entity\User ) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }*/

        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);

        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = 'fos_user_registration_confirmed';
            }

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

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);
            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        /* Breadcrumbs */
        $breadcrumbs = $this->container->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->container->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Inscription' );

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Receive the confirmation token from user email provider, login the user
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);
        $response = new RedirectResponse($this->container->get('router')->generate('fos_user_registration_confirmed'));
        $this->authenticateUser($user, $response);

        /* Breadcrumbs */
        $breadcrumbs = $this->container->get( 'white_october_breadcrumbs' );
        $breadcrumbs->addItem( 'Accueil', $this->container->get("router")->generate( '_homepage' ));
        $breadcrumbs->addItem( 'Inscription validée' );

        return $response;
    }
}
