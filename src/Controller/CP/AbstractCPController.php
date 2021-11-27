<?php

namespace App\Controller\CP;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractCPController extends AbstractController
{

	protected EntityManagerInterface $entityManager;
	
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

}