<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170205023020 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $taskList = $schema->createTable('task_list');

        $taskList->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $taskList->addColumn('title', 'string', array('length' => 100));
        $taskList->setPrimaryKey(array('id'));
        $taskList->addUniqueIndex(array('title'));

        $schema->createSequence('task_list_seq');


        $task = $schema->createTable('task');

        $task->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $task->addColumn('title', 'string', array('length' => 100));
        $task->addColumn('description', 'string', array('length' => 255));
        $task->addColumn('task_list_id', 'integer', array('unsigned' => true));
        $task->addColumn('status', 'integer', array('unsigned' => true));
        $task->setPrimaryKey(array('id'));
        $task->addForeignKeyConstraint($taskList, array('task_list_id'), array('id'), array('onDelete' => 'CASCADE'));

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('task_list');
        $schema->dropTable('task');
        $schema->dropSequence('task_list_seq');
    }
}
