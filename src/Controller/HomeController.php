<?php

namespace App\Controller;

use App\Entity\Artist;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em)
    {
        //Creation d'une nouvelle instance d'artiste
        $artist =(new Artist())
            ->setName('Hirsi')
            ->setDescription('Pas un vrai artiste...');

        //INSERT/UPDATE
        $em->persist($artist);

        //DELETE
        //$em->remove($entity);

        //Execution des requetes SQL
        $em->flush();

        return $this->render('index.html.twig', [

        ]);
    }
}
