<?php

namespace TodoListBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    /**
     * @param Task $task
     * @return $this
     */
    public function remove(Task $task)
    {
        $this->getEntityManager()->remove($task);

        return $this;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function persist(Task $task)
    {
        $this->getEntityManager()->persist($task);

        return $this;
    }

    /**
     * @param Task|null $task
     * @return $this
     */
    public function flush(Task $task = null)
    {
        $this->getEntityManager()->flush($task);

        return $this;
    }

    /**
     * @param int $status
     * @return null|Task[]
     */
    public function findByStatus($status)
    {
        return
            $this->createQueryBuilder('t')
                ->where('t.status = :status')
                ->setParameter('status', $status)
                ->orderBy('t.id', 'DESC')
                ->getQuery()
                ->getResult();
    }

    public function findUnresolvedByTaskList(TaskList $taskList)
    {
        return
            $this->createQueryBuilder('t')
                ->where('t.taskList = :taskList')
                ->andWhere('t.status != 2')
                ->setParameter('taskList', $taskList)
                ->getQuery()
                ->getResult();
    }
}