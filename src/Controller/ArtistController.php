<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artist", name="artist_")
 */
class ArtistController extends AbstractController
{
    /**
     * URI: /artist-list
     * Nom: artist_list
     * @Route("-list", name="list")
     */
    public function index()
    {
        return $this->render('list.html.twig');


    }
}
