<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Record;


/**
 * @Route("/record", name="record_")
 */
class RecordController extends AbstractController
{
    /**
     * exemple:/record/42
     * @Route("/{id}", name="page")
     */
    public function index(Record $record)
    {
       return $this->render('record/record_page.html.twig', [
           'record' => $record
       ]);
    }
}
