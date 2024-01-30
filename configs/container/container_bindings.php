<?php

// use Slim\Views\Twig;

use Slim\App;
use function DI\create;
use JR\ChefsDiary\Config;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

// use Psr\Container\ContainerInterface;

return [
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $addMiddleware = require CONFIG_PATH . '/middleware.php';
        $router = require CONFIG_PATH . '/routes/web.php';

        $app = AppFactory::create();

        $app->getRouteCollector()->setDefaultInvocationStrategy(
            new RouteEntityBindingStrategy
        );
    }
    // Config::class => create(Config::class)->constructor(require CONFIG_PATH . '/app.php'),
    // EntityManager::class => fn(Config $config) => EntityManager::create(
    //     $config->get('doctrine.connection'),
    //     ORMSetup::createAttributeMetadataConfiguration(
    //         $config->get('doctrine.entity_dir'),
    //         $config->get('doctrine.dev_mode')
    //     )
    // ),
    // TODO: Asi jen pro views
    // Twig::class                   => function (Config $config, ContainerInterface $container) {
    //     $twig = Twig::create(VIEW_PATH, [
    //         'cache'       => STORAGE_PATH . '/cache/templates',
    //         'auto_reload' => AppEnvironment::isDevelopment($config->get('app_environment')),
    //     ]);

    //     $twig->addExtension(new IntlExtension());
    //     $twig->addExtension(new EntryFilesTwigExtension($container));
    //     $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));

    //     return $twig;
    // },

    // /**
    //  * The following two bindings are needed for EntryFilesTwigExtension & AssetExtension to work for Twig
    //  */
    // 'webpack_encore.packages' => fn() => new Packages(
    //     new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
    // ),
    // 'webpack_encore.tag_renderer' => fn(ContainerInterface $container) => new TagRenderer(
    //     new EntrypointLookup(BUILD_PATH . '/entrypoints.json'),
    //     $container->get('webpack_encore.packages')
    // ),
];