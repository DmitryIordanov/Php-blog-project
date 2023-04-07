<?php

namespace Blog\Route;

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
class PostPage {

    /**
     * @var Environment
     */
    private Environment $view;

    /**
     * @var PostMapper
     */
    private PostMapper $postMapper;

    /**
     * @param Environment $view
     * @param PostMapper $postMapper
     */
    public function __construct(Environment $view, PostMapper $postMapper){
        $this->view = $view;
        $this->postMapper = $postMapper;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface{
        $post = $this->postMapper->getByUrlKey((string) $args['url_key']);

        if (empty($post)) {
            $body = $this->view->render("404.twig");
        } else {
            $body = $this->view->render("post.twig", [
                'post' => $post
            ]);
        }
        $response->getBody()->write($body);
        return $response;
    }
}