<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    // /**
    //  * @Route("/", name="home")
    //  */
    // public function home(){
    //     return  $this->redirectToRoute('posts_index');
    // }

    /**
     * @Route("/", name="acceuil")
     */
    public function acceuil(){
        return $this->render('acceuil.html.twig');
    }

}