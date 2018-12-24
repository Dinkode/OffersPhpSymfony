<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageController extends Controller
{
    /**
     * @Route("/delete_image/{name}", name="delete_img", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse | JsonResponse
     */
    public function deleteImgAction($name, Request $request)
    {


        $authorId = $request->request->get('authorId');
        $currentUserId = $this->getUser()->getId();
        if($authorId==$currentUserId) {
            $img = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['name' => $name]);
            $json = ['name' => $name, 'aId' => $authorId, 'uId' => $currentUserId];
            $fs = new Filesystem();
            $fs->remove($this->getParameter('image_directory') . $name);
            $em = $this->getDoctrine()->getManager();
            $em->remove($img);
            $em->flush();

            return new JsonResponse($json);
        }
        return $this->redirectToRoute('blog_index');
    }
}
