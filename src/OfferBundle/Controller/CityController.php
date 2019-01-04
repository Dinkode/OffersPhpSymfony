<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\City;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends Controller
{
    /**
     * @Route("/offers/{category}", name="cities")
     * @param $category
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function pageAction($category)
    {
        $cities = $this->getDoctrine()->getRepository(City::class)->getCities($category);

        return $this->render('list/cities.twig', ['cities'=>$cities, 'category'=>$category]);

    }
}
