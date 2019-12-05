<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Label;

/**
 * @Route("/label", name="label_")
 */
class LabelController extends AbstractController
{
    /**
     * @Route("/{id}", name="page")
     */
    public function index(Label $label)
    {
        return $this->render('label/label_page.html.twig', [
            'label'=>$label
        ]);
    }
}
