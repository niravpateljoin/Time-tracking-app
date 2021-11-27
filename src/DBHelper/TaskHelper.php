<?php

namespace App\DBHelper;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class TaskHelper
{
	private EntityManagerInterface $entityManager;

	public function __construct( EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function createNewTask( Task $task, $currentUser ) : Task
	{
		$task->setUser( $currentUser );
		$this->entityManager->persist($task);
		$this->entityManager->flush();
		return $task;
	}

	
	public function updateTask( Task $task ) : void
    {
    	$this->entityManager->persist($task);
		$this->entityManager->flush();
    }
}