<?php


namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{
    private PostRepository $posts;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->posts = new PostRepository();
    }

    public function indexPosts(): Response
    {
        $posts = $this->posts->getPosts();

        return new Response($this->render('post/index.html.twig', [
            'posts' => $posts,
        ]));
    }

    /**
     * @param $id
     * @return Response
     */
    public function showPost($id): Response
    {
        $post = $this->posts->getPost($id);

        return new Response($this->render('post/show.html.twig', [
            'post' => $post,
        ]));
    }
}
