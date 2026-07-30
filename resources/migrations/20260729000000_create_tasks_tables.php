<?php

namespace Vokuro\Migrations;

use Phinx\Migration\AbstractMigration;

class CreateTasksTables extends AbstractMigration
{
    public function change(): void
    {
        // status
        $statuses = $this->table('task_statuses');
        $statuses->addColumn('name', 'string', ['limit' => 50])
                 ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                 ->create();

        // priorities
        $priorities = $this->table('task_priorities');
        $priorities->addColumn('name', 'string', ['limit' => 50])
                   ->addColumn('level', 'integer', ['limit' => 2])
                   ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                   ->create();

        // tasks
        $tasks = $this->table('tasks');
        $tasks->addColumn('title', 'string', ['limit' => 255])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('user_id', 'integer', ['signed' => false]) // Owner
              ->addColumn('status_id', 'integer', ['signed' => false])
              ->addColumn('priority_id', 'integer', ['signed' => false])
              ->addColumn('deleted_at', 'datetime', ['null' => true])
              ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'datetime', ['null' => true, 'default' => null])
              ->addIndex(['user_id'])
              ->addIndex(['status_id'])
              ->addIndex(['priority_id'])
              ->create();

        // members
        $members = $this->table('task_members', ['id' => false, 'primary_key' => ['task_id', 'user_id', 'type']]);
        $members->addColumn('task_id', 'integer', ['signed' => false, 'null' => false])
                ->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
                ->addColumn('type', 'enum', ['values' => ['assignee', 'follower'], 'null' => false])
                ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->addIndex(['task_id'])
                ->addIndex(['user_id'])
                ->create();

        // comments
        $comments = $this->table('task_comments');
        $comments->addColumn('task_id', 'integer', ['signed' => false])
                 ->addColumn('user_id', 'integer', ['signed' => false])
                 ->addColumn('comment', 'text')
                 ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                 ->addIndex(['task_id'])
                 ->create();
    }
}