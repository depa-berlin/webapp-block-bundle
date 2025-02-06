<?php

declare(strict_types=1);

namespace Depa\WebappBlockBundle;

use Nijens\ProtocolStream\Stream\Stream;
use Nijens\ProtocolStream\StreamManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class WebappBlockBundle extends AbstractBundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new SettingsFormPass());
    }
    /*public function build(ContainerBuilder $container): void
    {
        $rootDirectory = $container->getParameter('kernel.project_dir');
        $this->registerStream($rootDirectory);
    }*/
    public function boot(): void
    {
        $rootDirectory = $this->container->get('kernel')->getProjectDir();
        $this->registerStream($rootDirectory);

        parent::boot();
    }

    /**
     * Register the stream for the given $rootDirectory.
     *
     * @param string $rootDirectory
     */
    private function registerStream($rootDirectory): void
    {
        $streamManager = new StreamManager();
        if (is_null($streamManager->getStream('webapp-block-bundle'))) {
            $stream = new Stream('webapp-block-bundle', [
                'blocks' => __DIR__ . '/config/templates/blocks/',
                'pages' => __DIR__ . '/config/templates/pages/',
                /*'forms' => __DIR__ . '/Resources/forms/',
                'properties' => __DIR__ . '/Resources/templates/properties/',
                'app-properties' => $rootDirectory . '/config/templates/PixelSuluBlockBundle/properties/',
                'app-property-params' => $rootDirectory . '/config/templates/PixelSuluBlockBundle/params/',
    */        ]);

            StreamManager::create()->registerStream($stream);
        }
    }

    public function loadExtension(
        array                                                                                                  $config,
        ContainerConfigurator $container,
        ContainerBuilder                                                                                       $builder,
    ): void
    {
        $container->import('../config/services.yaml');
    }
}
