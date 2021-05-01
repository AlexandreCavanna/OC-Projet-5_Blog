<?php


namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Service\Authentication;
use FireStorm\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class AdminController extends AbstractController
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
     * @var \App\Service\Authentication
     */
    private Authentication $authentication;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private Session $session;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->postRepository = new PostRepository();
        $this->commentRepository = new CommentRepository();
        $this->authentication = new Authentication();
        $this->session = new Session();
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function index(): Response
    {
        $posts = $this->postRepository->getPosts();
        $comments = $this->commentRepository->getComments();
        $countReview = $this->commentRepository->countNeedReview();

        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        return new Response($this->render('admin/index.html.twig', [
                'posts' => $posts,
                'comments' => $comments,
                'countReview' => $countReview
            ]));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function showCommentsByPost($id): Response
    {
        $comments = $this->commentRepository->getCommentsByPost($id);

        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        return new Response($this->render('admin/showCommentsByPost.html.twig', [
            'comments' => $comments,
            'postId' => $id,
        ]));
    }

    /**
     * @param $idPost
     * @param $idComment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveComment($idPost, $idComment): Response
    {
        $this->commentRepository->approveComment($idComment);

        return new RedirectResponse('/admin/post/'.$idPost.'/comments');
    }

    /**
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function editPost($id, Request $request)
    {
        $post = $this->postRepository->getPost($id);

        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        $requestMethod = $request->server->get('REQUEST_METHOD');

        $title = $request->request->get('title');
        $chapo = nl2br($request->request->get('chapo'));
        $author = $request->request->get('author');
        $content = nl2br($request->request->get('content'));
        $validFields = [];
        if ($requestMethod === "POST") {

            if (!empty($title) && !empty($chapo) && !empty($author) && !empty($content) && strlen($chapo) <= 255 && strlen($content) <= 1600) {
                $this->postRepository->updatePost(
                    $id,
                    $title,
                    $chapo,
                    $author,
                    $content
                );

                $this->session->getFlashBag()->add('success', 'Votre post est désormais modifié !');
                return new RedirectResponse('/admin/post/'.$id.'/edit');
            }

            $postsValues =$request->request->all();
            foreach ($postsValues as $key => $item) {
                if (empty($item)) {
                    $this->session->getFlashBag()->add('error', 'Tout les champs n\'ont pas été remplis !');
                    $this->session->getFlashBag()->add('error'.ucfirst($key), 'Le champ "'.ucfirst($key).'" n\'a pas été remplis');
                } elseif (strlen($postsValues['chapo']) > 255) {
                    $this->session->getFlashBag()->add('errorLengthChapo', 'Le champ "Chapô" fait plus de 255 caractères !');
                } elseif (strlen($postsValues['content']) > 1600) {
                    $this->session->getFlashBag()->add('errorLengthContent', 'Le champ "Contenu" fait plus de 1600 caractères !');
                } else {
                    $validFields[$key] = $item;

                    $this->session->getFlashBag()->add('success'.ucfirst($key), 'Le champ'.ucfirst($key).' est valide !');
                }

            }
        }

        $flashs = $this->session->getFlashBag()->all();

        return new Response($this->render('admin/editPost.html.twig', [
                'post' => $post,
                'flashs' => $flashs,
                'validFields' => $validFields,
        ]));
    }

    /**
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePost($id, Request $request): RedirectResponse
    {
        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        $this->postRepository->deletePost($id);
        return new RedirectResponse('/admin');
    }

    /**
     * @param $id
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteComment($id, Request $request): RedirectResponse
    {
        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        $comment = $this->commentRepository->getComment($id);
        $idPost = $comment->getPostId();
        $this->commentRepository->deleteComment($id);


        return new RedirectResponse('/admin/post/'.$idPost.'/comments');
    }
}
