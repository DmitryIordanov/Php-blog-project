<?php

namespace Blog\Route;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Blog\LatestPosts;
use Twig\Environment;

class HomePage{
    private LatestPosts $latestPosts;
    private Environment $view;
    public function __construct(LatestPosts $latestPosts, Environment $view){
        $this->view = $view;
        $this->latestPosts = $latestPosts;
    }

    public function execute(Request $request, Response $response): Response{
        $posts = $this->latestPosts->getPostNum(3);

        $body = $this->view->render("index.twig", [
            'posts' => $posts
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}