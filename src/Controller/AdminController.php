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
        }

        list($validFields, $flashs) = $this->validation($request, $validFields);

        return new Response($this->render('admin/editPost.html.twig', [
                'post' => $post,
                'flashs' => $flashs,
                'validFields' => $validFields,
        ]));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePost($id): RedirectResponse
    {
        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        $this->postRepository->deletePost($id);
        return new RedirectResponse('/admin');
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteComment($id): RedirectResponse
    {
        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }

        $comment = $this->commentRepository->getComment($id);
        $idPost = $comment->getPostId();
        $this->commentRepository->deleteComment($id);


        return new RedirectResponse('/admin/post/'.$idPost.'/comments');
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     * @throws \Exception
     */
    public function createPost(Request $request)
    {
        if ($this->authentication->denyAccessAdmin() instanceof RedirectResponse) {
            return $this->authentication->denyAccessAdmin();
        }
        $title = $request->request->get('title');
        $chapo = nl2br($request->request->get('chapo'));
        $author = $request->request->get('author');
        $content = nl2br($request->request->get('content'));
        $requestMethod = $request->server->get('REQUEST_METHOD');
        $validFields = [];

        if ($requestMethod === "POST") {
            if (!empty($title) && !empty($chapo) && !empty($author) && !empty($content) && strlen($chapo) <= 255 && strlen($content) <= 1600) {
                $this->postRepository->createPost($title, $chapo, $author, $content);

                $this->session->getFlashBag()->add('success', 'Votre post est désormais créé !');

                return new RedirectResponse('/admin/post/new');
            }
        }

        list($validFields, $flashs) = $this->validation($request, $validFields);

        return new Response($this->render('admin/createPost.html.twig', [
            'flashs' => $flashs,
            'validFields' => $validFields,
        ]));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $validFields
     * @return array
     */
    private function validation(Request $request, array $validFields): array
    {
        $postsValues = $request->request->all();
        foreach ($postsValues as $key => $item) {
            if (!empty($item)) {
                $this->session->getFlashBag()->add('success' . ucfirst($key), 'Le champ' . ucfirst($key) . ' est valide !');

                if (empty($validFields[$key])) {
                    $validFields[$key] = $item;
                }

                if (strlen($postsValues['chapo']) > 255) {
                    $this->session->getFlashBag()->add('errorLengthChapo', 'Le champ "Chapô" fait plus de 255 caractères !');
                    unset($validFields['chapo']);
                }

                if (strlen($postsValues['content']) > 1600) {
                    $this->session->getFlashBag()->add('errorLengthContent', 'Le champ "Contenu" fait plus de 1600 caractères !');
                    unset($validFields['content']);
                }
            } elseif (array_key_exists($key, $validFields) === false) {
                $this->session->getFlashBag()->add('error', 'Tout les champs n\'ont pas été remplis !');
                $this->session->getFlashBag()->add('error' . ucfirst($key), 'Le champ "' . ucfirst($key) . '" n\'a pas été remplis');
            }
        }

        $flashs = $this->session->getFlashBag()->all();

        if (isset($flashs['errorLengthChapo'])) {
            $tab = array_unique($flashs['errorLengthChapo']);
            $flashs['errorLengthChapo'] = $tab;
        }

        if (isset($flashs['errorLengthContent'])) {
            $tab = array_unique($flashs['errorLengthContent']);
            $flashs['errorLengthContent'] = $tab;
        }

        if (isset($flashs['error'])) {
            $tab = array_unique($flashs['error']);
            $flashs['error'] = $tab;
        }

        return [$validFields, $flashs];
    }
}
