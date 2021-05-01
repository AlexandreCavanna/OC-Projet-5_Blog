<?php
namespace App\Controller;

use FireStorm\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ErrorController
 * @package App\Controller
 */
class ErrorController extends AbstractController
{
    /**
     * @param FlattenException $exception
     * @return Response
     * @throws \Exception
     */
    public function exception(FlattenException $exception): Response
    {
        $msg = $exception->getMessage();
        $statusCode = $exception->getStatusCode();
        return new Response($this->render('error/error.html.twig', [
            'msg' => $msg,
            'statusCode' => $statusCode
        ]));
    }
}
