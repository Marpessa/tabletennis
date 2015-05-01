<?php
# src/Application/Sonata/MediaBundle/Provider/FileProvider.php
# Ne fonctionne pas ... Le service n'est pas configuré : sonata.media.provider.file.class: Application\Sonata\MediaBundle\Provider\FileProvider

namespace Application\Sonata\MediaBundle\Provider;

use Sonata\MediaBundle\Provider\FileProvider as BaseFileProvider;
use Symfony\Component\Form\FormBuilder;

class FileProvider extends BaseFileProvider
{
    /**
     * {@inheritdoc}
     */
    public function buildMediaType(FormBuilder $formBuilder)
    {
        $formBuilder->add('binaryContent', 'file', array(
            'label' => false,
        ));
    }
}

?>