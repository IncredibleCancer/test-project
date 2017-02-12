<?php

namespace TodoListBundle\Services;

use Symfony\Component\Form\Form;
use TodoListBundle\Entity\TaskList;
use TodoListBundle\Entity\TaskListRepository;

class TaskListService
{
    /** @var TaskListRepository */
    protected $taskListRepository;

    /**
     * TaskListService constructor.
     * @param TaskListRepository $taskListRepository
     */
    public function __construct(TaskListRepository $taskListRepository)
    {
        $this->taskListRepository = $taskListRepository;
    }

    /**
     * @param TaskList $taskList
     * @param Form $form
     */
    public function updateTaskList(TaskList $taskList, Form $form)
    {
        $taskList
            ->setTitle($form->get('title')->getData());

        $this->taskListRepository
            ->persist($taskList)
            ->flush($taskList);
    }

    /**
     * @param $listId
     * @return object|null|TaskList
     */
    public function getTaskListById($listId)
    {
        return $this->taskListRepository->find($listId);
    }

    /**
     * @return TaskList[]|null
     */
    public function getAllTaskLists()
    {
        return $this->taskListRepository->findAll();
    }

    /**
     * @param TaskList $taskList
     */
    public function removeTaskList(TaskList $taskList)
    {
        $this->taskListRepository
            ->remove($taskList)
            ->flush($taskList);
    }
}
