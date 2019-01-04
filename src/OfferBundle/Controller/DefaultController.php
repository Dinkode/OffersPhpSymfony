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
     * @Route("/offers/{category}/{page}", name="page")
     * @param $category
     * @param $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction($category, $page, Request $request)
    {

        if ($page<=0){
            $page=1;
        }
        $limit = $request->query->get('show') ? $request->query->get('show') : 3;
        $offset = $page*$limit-$limit;
        $shipping = $request->query->get('shipping') ? 1 : 0;
        $new = $request->query->get('new') ? 1 : 0;
        $image = $request->query->get('images') ? 'NOT NULL' : 'NULL';
        $orderBy = 'dateAdded';
        $orderType = 'DESC';
        $order = $request->query->get('order');
        if($order == 'new'){
            $orderBy = 'dateAdded';
            $orderType = 'DESC';
        } elseif ($order == 'last'){
            $orderBy = 'dateAdded';
            $orderType = 'ASC';
        } elseif ($order == 'view') {
            $orderBy = 'views';
            $orderType = 'DESC';
        } elseif ($order == 'priceasc') {
            $orderBy = 'price';
            $orderType = 'ASC';
        } elseif ($order == 'pricedesc') {
            $orderBy = 'price';
            $orderType = 'DESC';
        }



        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->paginationArticles($offset, $limit, $category, $shipping, $new, $image, $orderBy, $orderType);
        $count = count($this->getDoctrine()->getRepository(Article::class)
            ->paginationArticles(0, PHP_INT_MAX, $category, $shipping, $new, $image, $orderBy, $orderType));
        return $this->render('list/category.twig', ['articles'=>$articles, 'count'=>$count, 'page'=>$page, 'category'=>$category, 'show'=>$limit]);
    }

    /**
     * @Route("/offers/search/{page}", name="article_search")
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function searchAction(Request $request, $page)
    {
        $search = $request->query->get('key');
        if ($page<=0){
            $page=1;
        }
        $limit = $request->query->get('show') ? $request->query->get('show') : 3;
        $offset = $page*$limit-$limit;
        $shipping = $request->query->get('shipping') ? 1 : 0;
        $new = $request->query->get('new') ? 1 : 0;
        $image = $request->query->get('images') ? 'NOT NULL' : 'NULL';
        $orderBy = 'dateAdded';
        $orderType = 'DESC';
        $order = $request->query->get('order');
        if($order == 'new'){
            $orderBy = 'dateAdded';
            $orderType = 'DESC';
        } elseif ($order == 'last'){
            $orderBy = 'dateAdded';
            $orderType = 'ASC';
        } elseif ($order == 'view') {
            $orderBy = 'views';
            $orderType = 'DESC';
        } elseif ($order == 'priceasc') {
            $orderBy = 'price';
            $orderType = 'ASC';
        } elseif ($order == 'pricedesc') {
            $orderBy = 'price';
            $orderType = 'DESC';
        }



        $articles = $this->getDoctrine()->getRepository(Article::class)
            ->searchArticles($offset, $limit, $shipping, $new, $image, $orderBy, $orderType, $search);

        $count = count($this->getDoctrine()->getRepository(Article::class)
            ->searchArticles(0, PHP_INT_MAX, $shipping, $new, $image, $orderBy, $orderType, $search));
        var_dump($articles);
        exit();
        return $this->render('list/search.twig', ['articles'=>$articles, 'count'=>$count, 'page'=>$page, 'show'=>$limit]);

    }

}
