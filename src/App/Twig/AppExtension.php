<?php

// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFunctions() {
        return array(
            'media_public_url' => new \Twig_Function_Method($this, 'getMediaPublicUrl'),
        );
    }

    public function getMediaPublicUrl($media, $format)
    {
        $provider = $this->container->get($media->getProviderName());

        return $provider->generatePublicUrl($media, $format);
    }

    public function getName()
    {
        return 'app_extension';
    }
}

?>