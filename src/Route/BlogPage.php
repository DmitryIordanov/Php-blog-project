<?php

namespace Blog\Route;

use Blog\PostMapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Twig\Environment;
class BlogPage{

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
     * @param $args
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args = []): ResponseInterface{
        $page = isset($args['page']) ? (int) $args['page'] : 1;
        $limit = 6;

        $posts = $this->postMapper->getList($page, $limit, 'DESC');

        $totalCount = $this->postMapper->getTotalCount();
        $body = $this->view->render("blog.twig", [
            'posts' => $posts,
            'pagination' => [
                'current' => $page,
                'paging' => ceil($totalCount / $limit),
            ]
        ]);
        $response->getBody()->write($body);
        return $response;
    }
}