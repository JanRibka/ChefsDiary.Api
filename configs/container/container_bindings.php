<?php

declare(strict_types=1);

use Slim\App;
use function DI\create;
use JR\ChefsDiary\Config;
use Doctrine\ORM\ORMSetup;
use Slim\Factory\AppFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineExtensions\Query\Mysql\Year;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\DateFormat;
use Psr\Http\Message\ResponseFactoryInterface;
use JR\ChefsDiary\DataObjects\Configs\TokenConfig;
use JR\ChefsDiary\Shared\RouteEntityBindingStrategy;
use JR\ChefsDiary\Services\Implementation\AuthService;
use JR\ChefsDiary\DataObjects\Configs\AuthCookieConfig;
use JR\ChefsDiary\Services\Implementation\TokenService;
use JR\ChefsDiary\Services\Implementation\CookieService;
use JR\ChefsDiary\Services\Contract\AuthServiceInterface;
use JR\ChefsDiary\Services\Contract\TokenServiceInterface;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactory;
use JR\ChefsDiary\Repositories\Implementation\UserRepository;
use JR\ChefsDiary\Services\Implementation\EntityManagerService;
use JR\ChefsDiary\Repositories\Contract\UserRepositoryInterface;
use JR\ChefsDiary\Services\Contract\EntityManagerServiceInterface;
use JR\ChefsDiary\RequestValidators\RequestValidatorFactoryInterface;

return [
        // Project config
    App::class => function (ContainerInterface $container) {
        AppFactory::setContainer($container);

        $addMiddleware = require CONFIG_PATH . '/middleware.php';
        $router = require CONFIG_PATH . '/routes/web.php';

        $app = AppFactory::create();

        $app->getRouteCollector()->setDefaultInvocationStrategy(
            new RouteEntityBindingStrategy(
                $container->get(EntityManagerService::class),
                $app->getResponseFactory()
            )
        );

        $router($app);
        $addMiddleware($app);

        return $app;
    },
    Config::class => create(Config::class)->constructor(
        require CONFIG_PATH . '/app.php'
    ),
    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),
    AuthServiceInterface::class => fn(ContainerInterface $container) => $container->get(
        AuthService::class
    ),

        // Database
    EntityManagerInterface::class => function (Config $config) {
        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $config->get('doctrine.entity_dir'),
            $config->get('doctrine.dev_mode')
        );

        // $ormConfig->addFilter('user', UserFilter::class);
    
        if (class_exists('DoctrineExtensions\Query\Mysql\Year')) {
            $ormConfig->addCustomDatetimeFunction('YEAR', Year::class);
        }

        if (class_exists('DoctrineExtensions\Query\Mysql\Month')) {
            $ormConfig->addCustomDatetimeFunction('MONTH', Month::class);
        }

        if (class_exists('DoctrineExtensions\Query\Mysql\DateFormat')) {
            $ormConfig->addCustomStringFunction('DATE_FORMAT', DateFormat::class);
        }

        return new EntityManager(
            DriverManager::getConnection($config->get('doctrine.connection'), $ormConfig),
            $ormConfig
        );
    },

        // Factories
    RequestValidatorFactoryInterface::class => fn(ContainerInterface $container) => $container->get(
        RequestValidatorFactory::class
    ),
    ResponseFactoryInterface::class => fn(App $app) => $app->getResponseFactory(),

        // Services
    AuthServiceInterface::class => fn(Config $config, ContainerInterface $container) => new AuthService(
        $container->get(
            UserRepository::class
        ),
        new TokenService(
            $container->get(
                ResponseFactoryInterface::class
            ),
            new TokenConfig(
                $config->get('token.exp_access'),
                $config->get('token.exp_refresh'),
                $config->get('token.algorithm'),
                $config->get('token.key_access'),
                $config->get('token.key_refresh')
            )
        ),
        $container->get(
            CookieService::class
        ),
        new AuthCookieConfig(
            $config->get('auth_cookie.name'),
            $config->get('auth_cookie.secure'),
            $config->get('auth_cookie.http_only'),
            $config->get('auth_cookie.same_site'),
            $config->get('auth_cookie.expires'),
            $config->get('auth_cookie.path')
        )
    ),
    EntityManagerServiceInterface::class => fn(EntityManagerInterface $entityManager) => new EntityManagerService(
        $entityManager
    ),
    TokenServiceInterface::class => fn(Config $config, ContainerInterface $container) => new TokenService(
        $container->get(
            ResponseFactoryInterface::class
        ),
        new TokenConfig(
            $config->get('token.exp_access'),
            $config->get('token.exp_refresh'),
            $config->get('token.algorithm'),
            $config->get('token.key_access'),
            $config->get('token.key_refresh')
        )
    ),

        // Repositories
    UserRepositoryInterface::class => fn(ContainerInterface $container) => $container->get(
        UserRepository::class
    ),
];