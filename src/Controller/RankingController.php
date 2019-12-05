<?php

namespace App\Controller;

use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ranking", name="ranking_")
 */
class RankingController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     */
    public function index(RecordRepository $recordRepository)
    {
        $releases = $recordRepository->getLastMonthReleases();

        return $this->render('ranking/news.html.twig', [
            'releases' => $releases
        ]);
    }
}
