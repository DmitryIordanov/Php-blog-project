<?php

use Blog\Route\HomePage;
use DevCoder\DotEnv;
use DI\ContainerBuilder;
use Blog\Route\AboutPage;
use Blog\Route\BlogPage;
use Blog\Route\PostPage;
use Slim\Factory\AppFactory;
use Blog\Slim\TwigMiddleware;


require __DIR__ . '/vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions('config/di.php');
(new DotEnv(__DIR__ . '/.env'))->load();

$container = $builder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$app->add($container->get(TwigMiddleware::class));

$app->get('/', HomePage::class . ':execute');
$app->get('/about', AboutPage::class);
$app->get('/blog[/{page}]', BlogPage::class);
$app->get('/{url_key}', PostPage::class);

$app->run();