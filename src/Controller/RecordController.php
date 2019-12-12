<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Record;
use App\Form\NoteFormType;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/record", name="record_")
 */
class RecordController extends AbstractController
{
    /**
     * Exemple: /record/42
     * @Route("/{id}", name="page")
     */
    public function index(
        Record $record,
        Request $request,
        EntityManagerInterface $em,
        Security $security,
        NoteRepository $noteRepository
    ) {
        // Traiter le formulaire uniquement lorsque connecté
        if ($security->isGranted('ROLE_USER')) {
            // Rechercher une Note à modifier
            $note = $noteRepository->findOneBy([
                'record' => $record,
                'user' => $this->getUser()
            ]);

            // Pas de Note existante: initialisation
            if ($note === null) {
                $note = (new Note())
                    ->setRecord($record)
                    ->setUser($this->getUser())
                ;
            }

            $noteForm = $this->createForm(NoteFormType::class, $note);
            $noteForm->handleRequest($request);

            if ($noteForm->isSubmitted() && $noteForm->isValid()) {
                $note = $noteForm->getData();

                $em->persist($note);
                $em->flush();

                $this->addFlash('success', 'Note enregistrée');
            }
        }

        return $this->render('record/record_page.html.twig', [
            'record' => $record,
            'note_form' => isset($noteForm) ? $noteForm->createView() : null
        ]);
    }
}