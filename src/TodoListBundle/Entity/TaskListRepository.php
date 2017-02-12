<?php

namespace TodoListBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TaskListRepository extends EntityRepository
{
    /**
     * @param TaskList $taskList
     * @return $this
     */
    public function remove(TaskList $taskList)
    {
        $this->getEntityManager()->remove($taskList);

        return $this;
    }

    /**
     * @param TaskList $taskList
     * @return $this
     */
    public function persist(TaskList $taskList)
    {
        $this->getEntityManager()->persist($taskList);
        return $this;
    }

    /**
     * @param TaskList|null $taskList
     * @return $this
     */
    public function flush(TaskList $taskList = null)
    {
        $this->getEntityManager()->flush($taskList);
        return $this;
    }

    public function findTask($taskId)
    {
        return
            $this->createQueryBuilder('tl')
                ->where('tl.tasks = :status')
                ->setParameter('taskId', $taskId)
                ->getQuery()
                ->getOneOrNullResult();
    }
}