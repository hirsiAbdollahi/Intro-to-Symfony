<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

 /**
     * @Route("/admin", name="admin_")
*/

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        return $this->render('admin/dashboard/index.html.twig');
    }
}
