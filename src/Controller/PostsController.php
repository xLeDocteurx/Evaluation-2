<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Entity\Comments;
use App\Repository\PostsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/posts")
 */
class PostsController extends AbstractController
{
    /**
     * @Route("/", name="posts_index", methods="GET")
     */
    public function index(PostsRepository $postsRepository): Response
    {
        $thisPosts = array_reverse($postsRepository->findAll());

        return $this->render('posts/index.html.twig', ['posts' => $thisPosts]);
    }

    /**
     * @Route("/new", name="posts_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $post = new Posts();
        $post->setDate(new \DateTime('now'));
        $post->setAuthor($user);

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('posts_index');
        }

        return $this->render('posts/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="posts_show", methods="GET|POST")
     */
    public function show(Request $request, Posts $post): Response
    {
        $thisUser = $this->getUser();

        $comment = new Comments();
        $comment->setDate(new \DateTime('now'));
        $comment->setAuthor($thisUser);
        $comment->setPost($post);

        $form = $this->createFormBuilder($comment)
            ->add('content', TextareaType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('posts_show', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('posts/show.html.twig', [
            'post' => $post,
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="posts_edit", methods="GET|POST")
     */
    public function edit(Request $request, Posts $post): Response
    {
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('posts_edit', ['id' => $post->getId()]);
        }

        return $this->render('posts/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="posts_delete", methods="DELETE")
     */
    public function delete(Request $request, Posts $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('posts_index');
    }
}
