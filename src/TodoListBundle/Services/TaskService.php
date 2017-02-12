<?php

namespace TodoListBundle\Services;

use Symfony\Component\Form\Form;
use TodoListBundle\Entity\Task;
use TodoListBundle\Entity\TaskList;
use TodoListBundle\Entity\TaskRepository;

class TaskService
{
    /** @var TaskRepository */
    protected $taskRepository;

    /**
     * TaskService constructor.
     * @param TaskRepository $taskRepository
     * @internal param TaskRepository $taskListRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @param Task $task
     * @param Form $form
     */
    public function updateTask(Task $task, Form $form)
    {
        $task
            ->setTitle($form->get('title')->getData())
            ->setDescription($form->get('description')->getData())
            ->setStatus(0)
            ->setTaskList($form->get('taskList')->getData());

        $this->taskRepository
            ->persist($task)
            ->flush($task);
    }


    /**
     * @param Task $task
     * @param int $status
     */
    public function updateTaskStatus(Task $task, $status)
    {
        $task->setStatus($status);

        $this->taskRepository
            ->persist($task)
            ->flush($task);
    }

    /**
     * @return Task[]|null
     */
    public function getAllTasks()
    {
        return $this->taskRepository->findAll();
    }

    /**
     * @param int $status
     * @return null|Task[]
     */
    public function getTasksByStatus($status)
    {
        return $this->taskRepository->findByStatus($status);
    }

    /**
     * @param TaskList $taskList
     * @return array
     */
    public function getUnresolvedTasksByTaskList(TaskList $taskList)
    {
        return $this->taskRepository->findUnresolvedByTaskList($taskList);
    }

    /**
     * @param int $taskId
     * @return null|Task|object
     */
    public function getTaskById($taskId)
    {
        return $this->taskRepository->find($taskId);
    }

    /**
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->taskRepository
            ->remove($task)
            ->flush($task);
    }
}
