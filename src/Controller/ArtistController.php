<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Artist;
/**
 * @Route("/artist", name="artist")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("-list", name="_list")
     */

    public function index(ArtistRepository $artistRepository)
    {
        return $this->render('artist/list.html.twig' , [
            'artist_list' => $artistRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="_page")
     */

    public function page(Artist $artist)
    {
        return $this->render('artist/artist_page.html.twig', [
            'artist'=>$artist
        ]);
    }    

}
