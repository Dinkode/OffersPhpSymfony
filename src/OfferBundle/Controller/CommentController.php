<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\Article;
use OfferBundle\Entity\Comment;
use OfferBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends Controller
{

    /**
     * @Route("/article/{id}/comment", name="add_comment")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $id)
    {
        $user = $this->getUser();
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        $author = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($user->getId());
        $comment = new Comment();
        $content = $request->request->get('comment');
        $comment->setContent($content);
        $comment->setUser($author);
        $comment->setArticle($article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute("article_view", ['id'=>$id]);
    }

    /**
     * @Route("/delete_comment/{id}", name="delete_comment", methods={"POST"})
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | JsonResponse
     */
    public function deleteCommentAction($id, Request $request)
    {


        $authorId = $request->request->get('authorId');
        $currentUserId = $this->getUser()->getId();
        if($authorId==$currentUserId) {
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($id);
            $json = ['name' => 5];
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return new JsonResponse($json);
        }
        return $this->redirectToRoute('offers_index');
    }

}
