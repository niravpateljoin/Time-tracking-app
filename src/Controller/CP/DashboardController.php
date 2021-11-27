<?php

namespace App\Controller\CP;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractCPController
{
	
    /**
     * @Route("/dashboard", name="cp_dashboard")
     */
    public function index(Request $request): Response
	{

        return $this->render('dashboard/index.html.twig');
    }
	
	/**
	 * @Route("/", name="cp_main")
	 * @return Response
	 */
    public function indexRedirect(): Response
	{
		return $this->redirectToRoute('cp_app_login');
	}
}
