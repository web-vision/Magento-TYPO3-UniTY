<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use TYPO3\CMS\Core\DependencyInjection\SingletonPass;
use WebVision\WvT3unity\UserFunc\ContentJson;

return function (ContainerConfigurator $containerConfigurator, ContainerBuilder $containerBuilder): void {
    $containerBuilder
        ->registerForAutoconfiguration(ContentJson::class)
        ->addTag('deepl.ContentJson');

    $containerBuilder
        ->addCompilerPass(new SingletonPass('deepl.ContentJson'));
};
