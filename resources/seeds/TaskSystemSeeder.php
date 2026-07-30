<?php

namespace Vokuro\Seeds;

use Phinx\Seed\AbstractSeed;

class TaskSystemSeeder extends AbstractSeed
{
    public function run(): void
    {
        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->table('task_comments')->truncate();
        $this->table('task_members')->truncate();
        $this->table('tasks')->truncate();
        $this->table('task_priorities')->truncate();
        $this->table('task_statuses')->truncate();
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');

        // default status
        $statusData = [
            ['id' => 1, 'name' => 'Pending'],
            ['id' => 2, 'name' => 'In Progress'],
            ['id' => 3, 'name' => 'Completed'],
            ['id' => 4, 'name' => 'Cancelled'],
        ];
        $this->table('task_statuses')->insert($statusData)->saveData();

        // default priorities
        $priorityData = [
            ['id' => 1, 'name' => 'High', 'level' => 1],
            ['id' => 2, 'name' => 'Medium', 'level' => 2],
            ['id' => 3, 'name' => 'Low', 'level' => 3],
        ];
        $this->table('task_priorities')->insert($priorityData)->saveData();

        // user existente ou fallback pra id i
        $user = $this->fetchRow('SELECT id FROM users LIMIT 1');
        $userId = $user ? (int) $user['id'] : 1;

        // tasks de exemplo
        $now = date('Y-m-d H:i:s');
        $tasksData = [
            [
                'id'          => 1,
                'title'       => 'Configurar ambiente de desenvolvimento',
                'description' => 'Instalar dependências, validar migrations e seeders.',
                'user_id'     => $userId,
                'status_id'   => 1, // Pending
                'priority_id' => 1, // High
                'created_at'  => $now,
            ],
            [
                'id'          => 2,
                'title'       => 'Criar Repositories e Services',
                'description' => 'Estruturar o padrão Service & Repository no Phalcon.',
                'user_id'     => $userId,
                'status_id'   => 2, // In Progress
                'priority_id' => 2, // Medium
                'created_at'  => $now,
            ],
            [
                'id'          => 3,
                'title'       => 'Criar estrutura do banco de dados',
                'description' => 'Migrations executadas com sucesso no Phinx.',
                'user_id'     => $userId,
                'status_id'   => 3, // Completed
                'priority_id' => 3, // Low
                'created_at'  => $now,
            ],
            [
                'id'          => 4,
                'title'       => 'Aplicar patter Service & Repository',
                'description' => 'Ideia descartada. Vamos manter no Phalcon.',
                'user_id'     => $userId,
                'status_id'   => 4, // Cancelled
                'priority_id' => 3, // Low
                'created_at'  => $now,
            ],
        ];
        $this->table('tasks')->insert($tasksData)->saveData();

        // membros vinculados na task 1 (exemplo)
        $this->table('task_members')->insert([
            ['task_id' => 1, 'user_id' => $userId, 'type' => 'assignee'],
            ['task_id' => 1, 'user_id' => $userId, 'type' => 'follower'],
        ])->saveData();

        // comentário exemplo
        $this->table('task_comments')->insert([
            ['task_id' => 1, 'user_id' => $userId, 'comment' => 'Seeder rodado com sucesso!', 'created_at' => $now]
        ])->saveData();
    }
}