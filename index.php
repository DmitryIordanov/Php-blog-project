<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Blog\PostMapper;
use Blog\LatestPosts;
use Blog\Slim\TwigMiddleware;


require __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$view = new Environment($loader);

$config = include 'config/database.php';
$dns = $config['dsn'];
$username = $config['username'];    
$password = $config['password'];

try {
    $connection = new PDO($dns, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $extension){
    echo 'Database error: ' . $extension->getMessage();
    exit;
}

$app = AppFactory::create();

$app->add(new TwigMiddleware($view));

$app->get('/', function (Request $request, Response $response) use ($view, $connection) {
    $LatestPosts = new LatestPosts($connection);
    $posts = $LatestPosts->getPostNum(3);

    $body = $view->render("index.twig", [
        'posts' => $posts
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/about', function (Request $request, Response $response) use ($view) {
    $body = $view->render("about.twig", [
        'name' => 'Dimon'
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/blog[/{page}]', function (Request $request, Response $response, $args) use ($view, $connection) {
    $PostMapper = new PostMapper($connection);

    $page = isset($args['page']) ? (int) $args['page'] : 1;
    $limit = 2;

    $posts = $PostMapper->getList($page, $limit, 'DESC');

    $totalCount = $PostMapper->getTotalCount();
    $body = $view->render("blog.twig", [    
        'posts' => $posts,
        'pagination' => [
            'current' => $page,
            'paging' => ceil($totalCount / $limit),
        ]
    ]);
    $response->getBody()->write($body);
    return $response;
});

$app->get('/{url_key}', function (Request $request, Response $response, $args) use ($view, $connection) {
    $PostMapper = new PostMapper($connection);
    $post = $PostMapper->getByUrlKey((string) $args['url_key']);

    if (empty($post)) {
        $body = $view->render("404.twig");
    } else {
        $body = $view->render("post.twig", [
            'post' => $post
        ]);
    }
    $response->getBody()->write($body);
    return $response;
});

$app->run();