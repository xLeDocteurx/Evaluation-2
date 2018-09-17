<?php

namespace App\Controller;

use App\Entity\Objects;
use App\Form\ObjectsType;
use App\Repository\ObjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * @Route("/objects")
 */
class ObjectsController extends AbstractController
{
    /**
     * @Route("/", name="objects_index", methods="GET|POST")
     */
    public function index(ObjectsRepository $objectsRepository): Response
    {
        return $this->render('objects/index.html.twig', [
            'objects' => $objectsRepository->findBy(
                array('received' => false),
                array('id' => 'DESC')
            ),
        ]);
    }

    /**
     * @Route("/categorie/{category}", name="objects_byCategory", methods="GET|POST")
     */
    public function byCategory($category, ObjectsRepository $objectsRepository) {

        $objectsFrom = $objectsRepository->findBy(
            ['categorie' => $category]
        );

        return $this->render('objects/category.html.twig', [
            'objects' => $objectsFrom,
        ]);  

        // return $this->render('objects/category.html.twig', [
        //     'objects' => $objectsRepository->findBy(
        //         array('received' => false),
        //         array('categorie' => $category),
        //         array('id' => 'DESC')
        //     ),
        // ]);  
    }

    /**
     * @Route("/mine", name="objects_mine", methods="GET")
     */
    public function mine() {

        $user = $this->getUser();
        $userObjects = $user->getGivedObjects(
            array('id' => 'DESC')
        );

        return $this->render('objects/mine.html.twig', [
            'objects' => $userObjects,
        ]);
    }

    /**
     * @Route("/wanted", name="objects_wanted", methods="GET")
     */
    public function wanted() {

        $user = $this->getUser();
        $userObjects = $user->getPretendingObjects(
            array('id' => 'DESC')
        );

        return $this->render('objects/wanted.html.twig', [
            'objects' => $userObjects,
        ]);
    }

    /**
     * @Route("/new", name="objects_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {

        $user = $this->getUser();

        $object = new Objects();
        $object->setGiver($user);
        $object->setReceived(false);

        $form = $this->createForm(ObjectsType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->redirectToRoute('objects_index');
        }

        return $this->render('objects/new.html.twig', [
            'object' => $object,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pretend" , name="objects_pretend" , methods="GET|POST")
     */
    public function pretend(Request $request): Response 
    {
            $user = $this->getUser();
        // if (isset($_POST['user'])) {
            
            $object = $this->getDoctrine()
            ->getRepository(Objects::class)
            ->findOneById($_POST['objectId']);

            $object->addPretender($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
        // }

        // return $this->redirectToRoute('objects_show', ['id' => $objectId]);
        return $this->redirectToRoute('objects_show', ['id' => $object->getId()]);
    }

    /**
     * @Route("/dontpretend" , name="objects_dontpretend" , methods="GET|POST")
     */
    public function dontpretend(Request $request): Response 
    {
            $user = $this->getUser();
        // if (isset($_POST['user'])) {
            
            $object = $this->getDoctrine()
            ->getRepository(Objects::class)
            ->findOneById($_POST['objectId']);

            $object->removePretender($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();
        // }

        // return $this->redirectToRoute('objects_show', ['id' => $objectId]);
        return $this->redirectToRoute('objects_show', ['id' => $object->getId()]);
    }

    /**
     * @Route("/{id}", name="objects_show", methods="GET|POST")
     */
    public function show(Objects $object): Response
    {
        return $this->render('objects/show.html.twig', ['object' => $object]);
    }

    /**
     * @Route("/{id}/edit", name="objects_edit", methods="GET|POST")
     */
    public function edit(Request $request, Objects $object): Response
    {
        $form = $this->createForm(ObjectsType::class, $object);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('objects_edit', ['id' => $object->getId()]);
        }

        return $this->render('objects/edit.html.twig', [
            'object' => $object,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="objects_delete", methods="DELETE")
     */
    public function delete(Request $request, Objects $object): Response
    {
        if ($this->isCsrfTokenValid('delete'.$object->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($object);
            $em->flush();
        }

        return $this->redirectToRoute('objects_index');
    }
}
