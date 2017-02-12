<?php

namespace TodoListBundle\Controller;

use TodoListBundle\Entity\Task;
use TodoListBundle\Services\TaskListService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TodoListBundle\Services\TaskService;

/**
 * @Route("/", service="todo_list.controller.main_controller")
 */
class MainController extends Controller
{
    /** @var TaskListService */
    protected $taskListService;

    /** @var TaskService */
    protected $taskService;

    /**
     * MainController constructor.
     * @param TaskListService $taskListService
     * @param TaskService $taskService
     */
    public function __construct(TaskListService $taskListService, TaskService $taskService)
    {
        $this->taskListService = $taskListService;
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('@TodoList/pages/Main/index.html.twig', [
            'taskLists' => $this->taskListService->getAllTaskLists(),
            'tasksWait' => $this->taskService->getTasksByStatus(Task::STATUS_WAIT),
            'tasksInProgress' => $this->taskService->getTasksByStatus(Task::STATUS_IN_PROGRESS),
            'tasksResolved' => $this->taskService->getTasksByStatus(Task::STATUS_RESOLVED)
        ]);
    }
}
