<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\LogInType;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    /**
     * @Route("/", name="users_index", methods="GET")
     */
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', ['users' => $usersRepository->findAll()]);
    }

    /**
     * @Route("/login", name="users_login", methods="GET|POST")
     */
    public function login()
    {
        return $this->render("users/login.html.twig");
    }
    
    /**
     * @Route("/logout", name="users_logout")
     */
    function logout(){
    }

    /**
     * @Route("/new", name="users_new", methods="GET|POST")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $user->setConfirmed(false);
        $user->setCountry('');
        $user->setAuthor(0);
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('acceuil');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_show", methods="GET")
     */
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/{id}/edit", name="users_edit", methods="GET|POST")
     */
    public function edit(Request $request, Users $user): Response
    {

        $form = $this->createFormBuilder($user)
            ->add('avatar')
            ->add('country')
            ->add('email')
            ->add('password')
            ->add('bio')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_edit', ['id' => $user->getId()]);
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="users_delete", methods="DELETE")
     */
    public function delete(Request $request, Users $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('users_index');
    }
}
