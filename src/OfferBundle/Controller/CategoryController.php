<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/get_nav", name="get_nav", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getNavAction()
    {
        $nav = $this->getDoctrine()->getRepository(Category::class)->getNav();
        return new JsonResponse($nav);
    }
}
