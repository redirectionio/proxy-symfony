<?php

namespace RedirectionIO\Client\SymfonyBundle;

use RedirectionIO\Client\SymfonyBundle\DependencyInjection\RedirectionIOClientSymfonyExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RedirectionIOClientSymfonyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new RedirectionIOClientSymfonyExtension();
        }

        return $this->extension;
    }
}
