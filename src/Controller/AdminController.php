<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    public function dashboard()
    {
        return $this->render('Admin/dashboard.html.twig');
    }

}