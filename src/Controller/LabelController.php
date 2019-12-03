<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/label", name="label_")
 */
class LabelController extends AbstractController
{
    /**
     * @Route("/{id}", name="page")
     */
    public function index($id)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/LabelController.php',
            'id'=> $id
        ]);
    }
}
