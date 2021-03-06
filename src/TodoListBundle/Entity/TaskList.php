<?php

namespace TodoListBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TaskListRepository")
 * @ORM\Table(name="task_list")
 */
class TaskList
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="taskList", cascade={"persist"})
     */
    private $tasks;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return TaskList
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add task
     *
     * @param Task $task
     *
     * @return TaskList
     */
    public function addTask(Task $task)
    {
        $task->setTaskList($this);
        $this->tasks->add($task);

        return $this;
    }

    /**
     * Remove task
     *
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @return Collection|Task[]
     */
    public function getUnresolvedTasks()
    {
        return $this->getTasks()
            ->filter(function (Task $task) {
                return $task->isUnresolved();
            });
    }

    function __toString()
    {
        return $this->getTitle();
    }
}

