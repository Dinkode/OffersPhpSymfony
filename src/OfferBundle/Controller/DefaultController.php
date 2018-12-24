<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="offers_index")
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('list/index.html.twig', ['articles'=>$articles]);
    }

    /**
     * @Route("/{category}/{page}", name="page")
     * @param $category
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction($category, $page)
    {

        if ($page<=0){
            $page=1;
        }
        $limit = 3;
        $offset = $page*$limit-$limit;
        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->paginationArticles($offset, $limit, $category);
        $count = count($this->getDoctrine()->getRepository(Article::class)->getArticlesCount());
        return $this->render('list/category.twig', ['articles'=>$articles, 'count'=>$count, 'page'=>$page, 'category'=>$category]);
    }

}
