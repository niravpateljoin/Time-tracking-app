<?php

namespace App\Controller\CP;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\Type\TaskType;
use App\Entity\Task;
use App\DBHelper\TaskHelper;
use App\Repository\TaskRepository;


class TaskController extends AbstractController
{

    
    /**
     * @Route("/task", name="cp_task")
     */
    public function index(Request $request, TaskRepository $taskRepository, PaginatorInterface $paginator): Response
    {

        $queryBuilder = $taskRepository->getQueryBuilder($this->getUser());
       
        $tasks = $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 10);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

	/**
     * @Route("/task/create", name="cp_task_create")
     */
    public function createAction(Request $request, TaskHelper $taskHelper) : Response
    {
    	$task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskdata = $form->getData();
    
            try {
                $user = $taskHelper->createNewTask($taskdata,$this->getUser());
                $this->addFlash('success', 'Task has been successfully created.');
				return $this->redirectToRoute('cp_task');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'An error occurred when creating task object.');
            }
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    

    /**
     * @Route("/task/update/{id}", name="cp_task_update")
     */
    public function updateAction(Request $request, Task $task, TaskHelper $taskHelper) : Response
    {
    	$form = $this->createForm(TaskType::class, $task);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $taskData = $form->getData();
            
            try {
                $taskHelper->updateTask($taskData);
                $this->addFlash('success', 'Task has been successfully updated.');
				return $this->redirectToRoute('cp_task');
			} catch (\Exception $e) {
                $this->addFlash('danger', 'An error occurred when saving task object.');
            }

        }

        return $this->render('task/update.html.twig', [
            'task' => $task,
            'form' => $form->createView()
        ]);
    }
}