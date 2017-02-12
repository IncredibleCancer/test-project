<?php

namespace TodoListBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TodoListBundle\Entity\Task;
use TodoListBundle\Entity\TaskList;
use TodoListBundle\Form\TaskListType;
use TodoListBundle\Form\TaskType;
use TodoListBundle\Services\TaskListService;
use TodoListBundle\Services\TaskService;

/**
 * @Route("tasks", service="todo_list.controller.task_controller")
 */
class TaskController extends Controller
{
    /** @var TaskListService */
    protected $taskListService;

    /** @var TaskService */
    protected $taskService;

    /**
     * TaskController constructor.
     * @param TaskListService $taskListService
     * @param TaskService $taskService
     */
    public function __construct(TaskListService $taskListService, TaskService $taskService)
    {
        $this->taskListService = $taskListService;
        $this->taskService = $taskService;
    }

    /**
     * @Route("/", name="todo.list.index")
     */
    public function indexAction()
    {
        return $this->render('@TodoList/pages/TaskList/lists.html.twig', [
            'taskLists' => $this->taskListService->getAllTaskLists()
        ]);
    }

    /**
     * @Route("/{listId}", requirements={"listId": "\d+"}, name="todo.list.show")
     * @param int $listId
     * @return Response
     */
    public function listAction($listId)
    {
        $taskList = $this->taskListService->getTaskListById($listId);

        if (null === $taskList) {
            throw $this->createNotFoundException();
        }

        return $this->render('@TodoList/pages/TaskList/task_list.html.twig', [
            'taskList' => $taskList,
            'tasks' => $taskList->getUnresolvedTasks()
        ]);
    }

    /**
     * @Route("/{listId}/{taskId}", requirements={
     *     "listId" : "\d+",
     *     "taskId" : "\d+"
     *     },
     *     name="todo.task.show")
     * @param Request $request
     * @param int $listId
     * @param int $taskId
     * @return Response
     */
    public function taskAction(Request $request, $listId, $taskId)
    {
        $taskList = $this->taskListService->getTaskListById($listId);
        $task = $this->taskService->getTaskById($taskId);

        if (null === $taskList) {
            throw $this->createNotFoundException();
        }

        if (null === $task) {
            throw $this->createNotFoundException();
        }

        if ($taskList !== $task->getTaskList()) {
            return $this->redirectToRoute('todo.list.show', [
                'id' => $task->getTaskList()->getId()
            ]);
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->updateTask($task, $form);

            return $this->redirectToRoute('todo.task.success', [
                'taskId' => $task->getId()
            ]);
        }

        return $this->render('@TodoList/pages/Task/task.html.twig',[
            'taskList' => $taskList,
            'task'     => $task,
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("/create-list", name="todo.list.create")
     * @param Request $request
     * @return Response
     */
    public function createListAction(Request $request)
    {
        $taskList = new TaskList();

        $form = $this->createForm(TaskListType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskListService->updateTaskList($taskList, $form);

            return $this->redirectToRoute('todo.list.success', [
                'listId' => $taskList->getId()
            ]);
        }

        return $this->render('@TodoList/pages/TaskList/create_list.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/create-task", name="todo.task.create")
     * @param Request $request
     * @return Response
     */
    public function createTaskAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskService->updateTask($task, $form);

            return $this->redirectToRoute('todo.task.success', [
                'taskId' => $task->getId()
            ]);
        }

        return $this->render('@TodoList/pages/Task/create_task.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{taskId}/update-status", requirements={"taskId": "\d+"}, name="todo.task.status")
     * @param Request $request
     * @param int $taskId
     * @return RedirectResponse
     */
    public function updateStatusAction(Request $request, $taskId)
    {
        $task = $this->taskService->getTaskById($taskId);

        if (null === $task) {
            throw $this->createNotFoundException();
        }

        if (!$request->query->has('status')) {
            throw new BadRequestHttpException('need status param');
        }

        $status = $request->query->get('status');

        $taskList = $task->getTaskList();

        $this->taskService->updateTaskStatus($task, $status);

        return $this->redirectToRoute('todo.list.show', [
            'listId' => $taskList->getId()
        ]);
    }

    /**
     * @Route("/{taskId}/remove-task", requirements={"taskId": "\d+"}, name="todo.task.remove")
     * @param int $taskId
     * @return RedirectResponse
     */
    public function removeTaskAction($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);

        if (null === $task) {
            throw $this->createNotFoundException();
        }

        $taskList = $task->getTaskList();

        $this->taskService->removeTask($task);

        return $this->redirectToRoute('todo.list.show', [
            'listId' => $taskList->getId()
        ]);
    }

    /**
     * @Route("/{listId}/remove-list", requirements={"listId": "\d+"}, name="todo.list.remove")
     * @param int $listId
     * @return RedirectResponse
     */
    public function removeListAction($listId)
    {
        $taskList = $this->taskListService->getTaskListById($listId);

        if (null === $taskList) {
            throw $this->createNotFoundException();
        }

        $this->taskListService->removeTaskList($taskList);

        return $this->redirectToRoute('todo.list.index');
    }

    /**
     * @Route("/{listId}/success-list", requirements={"listId": "\d+"}, name="todo.list.success")
     * @param int $listId
     * @return Response
     */
    public function taskListSuccessAction($listId)
    {
        $taskList = $this->taskListService->getTaskListById($listId);

        return $this->render('@TodoList/pages/TaskList/success.html.twig', [
            'taskList' => $taskList
        ]);
    }

    /**
     * @Route("/{taskId}/success-task", requirements={"taskId": "\d+"}, name="todo.task.success")
     * @param int $taskId
     * @return Response
     */
    public function taskSuccessAction($taskId)
    {
        $task = $this->taskService->getTaskById($taskId);

        return $this->render('@TodoList/pages/Task/success.html.twig', [
            'task' => $task
        ]);
    }
}
