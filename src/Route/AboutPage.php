<?php

namespace Blog\Route;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;

class AboutPage{

    /**
     * @var Environment
     */
    private Environment $view;

    /**
     * @param Environment $view
     */
    public function __construct(Environment $view){
        $this->view = $view;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface{
        $body = $this->view->render("about.twig", [
            'name' => 'Dimon'
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}