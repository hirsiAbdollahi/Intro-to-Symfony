<?php

namespace App\Controller;

use App\Entity\Artist;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecordRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(RecordRepository $recordRepository)
    {
        $top = $recordRepository->getBestRatedOYear();
      

        return $this->render('index.html.twig', [
            'top'=> $top,
        ]);
    }
}
