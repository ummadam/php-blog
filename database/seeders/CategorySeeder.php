<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;

class CategorySeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $categories = [
            [
                'name' => 'PHP',
                'slug' => 'php',
                'description' => 'Статьи о PHP, серверной разработке,
                    архитектуре приложений и современных подходах
                    к программированию.',
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'description' => 'JavaScript, интерактивные интерфейсы,
                    работа с DOM и асинхронное программирование.',
            ],
            [
                'name' => 'Базы данных',
                'slug' => 'databases',
                'description' => 'Проектирование баз данных, SQL,
                    оптимизация запросов и работа с MySQL.',
            ],
            [
                'name' => 'Веб-разработка',
                'slug' => 'web-development',
                'description' => 'Создание современных веб-сайтов,
                    разработка интерфейсов и веб-приложений.',
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'description' => 'Linux, Nginx, автоматизация,
                    развёртывание и обслуживание приложений.',
            ],
            [
                'name' => 'Искусственный интеллект',
                'slug' => 'artificial-intelligence',
                'description' => 'Машинное обучение, нейронные сети
                    и применение искусственного интеллекта.',
            ],
            [
                'name' => 'Мобильная разработка',
                'slug' => 'mobile-development',
                'description' => 'Разработка мобильных приложений
                    для Android и iOS.',
            ],
        ];

        $statement = $this->pdo->prepare(
            'INSERT INTO categories (
                name,
                slug,
                description
            ) VALUES (
                :name,
                :slug,
                :description
            )
            ON DUPLICATE KEY UPDATE
                name = :updated_name,
                description = :updated_description'
        );

        foreach ($categories as $category) {
            $statement->execute([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'updated_name' => $category['name'],
                'updated_description' => $category['description'],
            ]);
        }

        echo 'Categories processed: '
            . count($categories)
            . PHP_EOL;
    }
}