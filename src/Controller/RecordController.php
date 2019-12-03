<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/record", name="record_")
 */
class RecordController extends AbstractController
{
    /**
     * exemple:/record/42
     * @Route("/{id}", name="page")
     */
    public function index($id)
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RecordController.php',
            'id'=>$id,
        ]);
    }
}
