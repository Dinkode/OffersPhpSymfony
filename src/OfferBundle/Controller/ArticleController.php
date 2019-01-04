<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\City;
use OfferBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use OfferBundle\Entity\Article;
use OfferBundle\Entity\Image;
use OfferBundle\Entity\User;
use OfferBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/article_create", name="article_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function articleCreate(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $file */
            $files = $request->files->all();
            $currentUser = $this->getUser();
            $article->setAuthor($currentUser);
            $imgName = $request->request->get('featured');

            foreach ($files as $file) {
                if($file!=null) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    if($imgName === $file->getClientOriginalName()){
                        $article->setFeaturedImage($fileName);
                    }
                    $image = new Image();
                    $image->setName($fileName);
                    $image->setArticle($article);

                    $article->addImage($image);
                    try {
                        $file->move($this->getParameter('image_directory'), $fileName);
                    } catch (\Exception $exception) {

                    }
                }
            }
            if(count($article->getImages())>0 && $article->getFeaturedImage() == null){
                $article->setFeaturedImage($article->getImages()[0]->getName());
            }
            $article->setViews(0);
            $cityName = $request->request->get('city');
            $city =  $this->getDoctrine()->getRepository(City::class)->findOneBy(['name'=>$cityName]);
            $article->setCity($city);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute("offers_index");
        }
        return $this->render("article/create.html.twig", ['form'=>$form->createView()]);

    }

    /**
     * @Route("/article/{id}", name="article_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticle($id){
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/details.html.twig', ['article'=>$article]);

    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function articleEdit(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        /** @var User $currentUser */
        $currentUser=$this->getUser();
        $article->setCity($article->getCity());

        if($article === null){
            return $this->redirectToRoute('offers_index');
        }

        if(!($currentUser->isAuthor($article) || $currentUser->isAdmin())){
            return $this->redirectToRoute('offers_index');
        }



        if($form->isSubmitted() && $form->isValid()){

            /** @var UploadedFile $file */
            $files = $request->files->all();
            $imgName = $request->request->get('featured');
            $article->setFeaturedImage($article->getFeaturedImage());
            foreach ($files as $file) {
                if($file!=null) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    if($imgName === $file->getClientOriginalName()){
                        $article->setFeaturedImage($fileName);
                    }
                    $image = new Image();
                    $image->setName($fileName);
                    $image->setArticle($article);
                    $article->addImage($image);
                    try {
                        $file->move($this->getParameter('image_directory'), $fileName);
                    } catch (\Exception $exception) {

                    }
                }
            }
            if(count($article->getImages())>0 && $article->getFeaturedImage() == null){
                $article->setFeaturedImage($article->getImages()[0]->getName());
            }
            $article->setViews(0);
            $cityName = $request->request->get('city');
            $city =  $this->getDoctrine()->getRepository(City::class)->findOneBy(['name'=>$cityName]);
            $article->setCity($city);
            $currentUser = $this->getUser();
            $article->setAuthor($currentUser);
            $em = $this->getDoctrine()->getManager();
            $em->merge($article);
            $em->flush();
            return $this->redirectToRoute("offers_index");
        }
        return $this->render("article/edit.html.twig", ['form'=>$form->createView(), 'article'=>$article]);

    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function articleDelete(Request $request, $id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);


        /** @var User $currentUser */
        $currentUser=$this->getUser();

        if($article === null){
            return $this->redirectToRoute('offers_index');
        }
        if(!$currentUser->isAuthor($article) && !$currentUser->isAdmin()){
            return $this->redirectToRoute('offers_index');
        }


        if($form->isSubmitted() && $form->isValid()){
            $fs = new Filesystem();
            foreach ($article->getImages() as $image) {
                $this->getDoctrine()->getRepository(Image::class)->deleteImage($image->getId());
                $fs->remove($this->getParameter('image_directory') . $image->getName());
            }
            $currentUser = $this->getUser();
            $this->getDoctrine()->getRepository(Comment::class)->deleteComment($article);
            $article->setAuthor($currentUser);

            $this->getDoctrine()->getRepository(Article::class)->deleteArticle($article->getId());

            return $this->redirectToRoute("offers_index");
        }
        return $this->render("article/delete.html.twig", ['form'=>$form->createView(), 'article'=>$article]);

    }
}
