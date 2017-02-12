<?php

namespace TodoListBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="TodoListBundle\Entity\TaskRepository")
 * @ORM\Table(name="task")
 */

class Task
{
    const STATUS_WAIT = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_RESOLVED = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="TaskList", inversedBy="tasks")
     * @ORM\JoinColumn(name="task_list_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $taskList;

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
     * @return Task
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
     * Set description
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set taskList
     *
     * @param TaskList $taskList
     *
     * @return Task
     */
    public function setTaskList(TaskList $taskList = null)
    {
        $this->taskList = $taskList;

        return $this;
    }

    /**
     * Get taskList
     *
     * @return TaskList
     */
    public function getTaskList()
    {
        return $this->taskList;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isUnresolved()
    {
        return $this->getStatus() != self::STATUS_RESOLVED;
    }
}
