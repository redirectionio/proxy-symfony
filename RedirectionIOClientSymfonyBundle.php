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

    /**
     * Returns the bundle parent name.
     *
     * @return string|null The Bundle parent name it overrides or null if no parent
     */
    public function getParent()
    {
    }
}
