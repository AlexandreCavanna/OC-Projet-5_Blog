<?php


namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    /**
     * @var \App\Repository\PostRepository
     */
    private PostRepository $postRepository;
    /**
     * @var \App\Repository\CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->commentRepository = new CommentRepository();
    }

    /**
     * @throws \Exception
     */
    public function indexPosts(): Response
    {
        $posts = $this->postRepository->getPosts();

        return new Response($this->render('post/index.html.twig', [
            'posts' => $posts,
        ]));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return Response
     * @throws \Exception
     */
    public function showPost(Request $request, $id): Response
    {
        $post = $this->postRepository->getPost($id);

        if (!empty($request->request->all())) {
            $this->commentRepository->createComment($request->request->get('content'), $id);
        }
        
        $comments = $this->commentRepository->getCommentsByPost($id);

        return new Response($this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
        ]));
    }
}
