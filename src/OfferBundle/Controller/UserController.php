<?php

namespace OfferBundle\Controller;

use OfferBundle\Entity\Article;
use OfferBundle\Entity\Role;
use OfferBundle\Entity\User;
use OfferBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    /**
     * @Route("/register", name="user_register")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $unique = $this->getDoctrine()->getRepository(User::class)->
            findOneBy(['email'=>$form->getData()->getEmail()]);
            if($unique!=null){
                $email = $form->getData()->getEmail();
                $this->addFlash("message", "email ${email} already exist");
                return $this->render('user/register.html.twig');
            }
            if ($form->getData()->getPassword() != $form->getData()->getConfirmPassword()){
                $this->addFlash("message", "passwords does not match");
                return $this->render('user/register.html.twig');
            }


            $phone = $request->get('phone');
            $user->setPhone($phone['phone']);
            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(['name'=>'ROLE_USER']);
            $user->setRoles($role);
            $password = $this->get('security.password_encoder')->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("security_login");
        }
        return $this->render("user/register.html.twig");
    }

    /**
     * @Route("/profile", name="user_profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function profile(Request $request){
        $userId = $this->getUser()->getId();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user->setFullName($request->request->get('fullName'));
            var_dump($request->request->get('phone'));
            $user->setPhone($request->request->get('phone'));
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user/profile.html.twig', ['user'=>$user, 'form'=>$form->createView()]);
    }

    /**
     * @Route("/articles", name="user_offers")
     */
    public function myOffers(){
        $userId = $this->getUser()->getId();
        $articles = $this->getDoctrine()->getRepository(Article::class)->findBy(['author'=>$userId]);
        return $this->render('user/articles.html.twig', ['articles'=>$articles]);
    }

    /**
     * @Route("/admin", name="admin")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function admin(Request $request){
        $currentUser=$this->getUser();
        if(!$this->getUser() || !$currentUser->isAdmin()){
            return $this->redirectToRoute('offers_index');
        }
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $roles = $this->getDoctrine()->getRepository(Role::class)->findAll();
        return $this->render('user/admin.html.twig', ['users'=>$users, 'roles'=>$roles]);
    }

    /**
     * @Route("/set_roles/", name="set_roles", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function setAdmin(Request $request)
    {
            /** @var User $user */
            $currentUser=$this->getUser();
            if ($currentUser->isAdmin()) {
                $ur = $request->request->get('ur');

                $param = explode('_', $ur);
                $user = $this->getDoctrine()->getRepository(User::class)->find($param[0]);
                $role = $this->getDoctrine()->getRepository(Role::class)->find($param[1]);
                if (in_array($role->getName(), $user->getRoles())) {

                    $user->eraseRole($role->getName());
                } else {
                    $user->setRoles($role);
                }
                $em = $this->getDoctrine()->getManager();
                $em->merge($user);
                $em->flush();

                return new JsonResponse($user->getRoles());
            }
        return new JsonResponse(['failed'=>'not admin']);

    }

}
