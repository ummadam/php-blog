<?php

declare(strict_types=1);

namespace Database\Seeders;

use PDO;
use RuntimeException;

class ArticleSeeder
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(): void
    {
        $topics = $this->getTopics();

        $categoryIds = $this->getCategoryIds();

        $articleStatement = $this->pdo->prepare(
            'INSERT INTO articles (
                title,
                slug,
                image,
                description,
                content,
                views,
                published_at
            ) VALUES (
                :title,
                :slug,
                :image,
                :description,
                :content,
                :views,
                :published_at
            )
            ON DUPLICATE KEY UPDATE
                id = LAST_INSERT_ID(id),
                title = :updated_title,
                image = :updated_image,
                description = :updated_description,
                content = :updated_content'
        );

        $relationStatement = $this->pdo->prepare(
            'INSERT INTO article_category (
                article_id,
                category_id
            ) VALUES (
                :article_id,
                :category_id
            )
            ON DUPLICATE KEY UPDATE
                article_id = article_category.article_id'
        );

        $count = 0;

        foreach ($topics as $categorySlug => $articles) {
            foreach ($articles as $topic) {
                $count++;

                $title = $topic[0];

                $slug = $topic[1];

                $description = $topic[2];

                $additionalCategories = $topic[3] ?? [];

                $image = '/assets/images/' . $categorySlug . '.svg';

                $content = $this->generateContent(
                    $title,
                    $description
                );

                $publishedAt = date(
                    'Y-m-d H:i:s',
                    time() - ($count * 86400)
                );

                $views = (($count * 137) % 5000) + 20;

                $articleStatement->execute([
                    'title' => $title,
                    'slug' => $slug,
                    'image' => $image,
                    'description' => $description,
                    'content' => $content,
                    'views' => $views,
                    'published_at' => $publishedAt,

                    'updated_title' => $title,
                    'updated_image' => $image,
                    'updated_description' => $description,
                    'updated_content' => $content,
                ]);

                $articleId = (int) $this->pdo->lastInsertId();

                $articleCategories = array_unique(
                    array_merge(
                        [$categorySlug],
                        $additionalCategories
                    )
                );

                foreach ($articleCategories as $slug) {
                    if (!isset($categoryIds[$slug])) {
                        throw new RuntimeException(
                            'Category not found: ' . $slug
                        );
                    }

                    $relationStatement->execute([
                        'article_id' => $articleId,
                        'category_id' => $categoryIds[$slug],
                    ]);
                }
            }
        }

        echo 'Articles processed: ' . $count . PHP_EOL;
    }

    private function getCategoryIds(): array
    {
        $statement = $this->pdo->query(
            'SELECT slug, id FROM categories'
        );

        return $statement->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    private function generateContent(
        string $title,
        string $description
    ): string {
        return implode("\n\n", [
            $description,

            'Введение',

            'В этой статье рассмотрим основные принципы темы «'
                . $title
                . '» и её применение при разработке современных приложений.',

            'Основные принципы',

            'При разработке программного обеспечения важно учитывать
            читаемость кода, производительность, безопасность и
            возможность дальнейшего сопровождения проекта.',

            'Практическое применение',

            'Для закрепления материала рекомендуется создать небольшой
            тестовый проект, изучить документацию и проверить различные
            варианты реализации на практике.',

            'Заключение',

            'Понимание основных принципов и регулярная практика
            помогают улучшать профессиональные навыки разработчика.',
        ]);
    }

    private function getTopics(): array
    {
        return [
            'php' => [
                [
                    'Введение в PHP',
                    'introduction-to-php',
                    'Основы языка PHP и создание первого приложения.',
                    ['web-development'],
                ],
                [
                    'Что нового в PHP 8.3',
                    'whats-new-in-php-83',
                    'Знакомство с возможностями PHP 8.3.',
                ],
                [
                    'Работа с PDO в PHP',
                    'php-pdo-guide',
                    'Безопасное подключение к базе данных через PDO.',
                    ['databases'],
                ],
                [
                    'Объектно-ориентированное программирование в PHP',
                    'php-oop',
                    'Классы, объекты, наследование и инкапсуляция.',
                ],
                [
                    'Принципы SOLID в PHP',
                    'solid-in-php',
                    'Основные принципы проектирования приложений.',
                ],
                [
                    'Composer и автозагрузка классов',
                    'php-composer',
                    'Управление зависимостями и стандарт PSR-4.',
                ],
                [
                    'Работа с Smarty',
                    'smarty-template-engine',
                    'Использование шаблонизатора Smarty.',
                    ['web-development'],
                ],
                [
                    'Маршрутизация на чистом PHP',
                    'php-routing',
                    'Организация маршрутов без использования фреймворков.',
                    ['web-development'],
                ],
                [
                    'Обработка исключений в PHP',
                    'php-exceptions',
                    'Обработка ошибок с помощью try и catch.',
                ],
                [
                    'Безопасность PHP-приложений',
                    'php-security',
                    'Защита веб-приложений от распространённых уязвимостей.',
                    ['web-development'],
                ],
                [
                    'Работа с массивами в PHP',
                    'php-arrays',
                    'Основные функции для обработки массивов.',
                ],
                [
                    'Создание REST API на PHP',
                    'php-rest-api',
                    'Основы разработки программных интерфейсов.',
                    ['web-development'],
                ],
            ],

            'javascript' => [
                [
                    'Основы JavaScript',
                    'javascript-basics',
                    'Переменные, функции и основные конструкции языка.',
                ],
                [
                    'Работа с DOM',
                    'javascript-dom',
                    'Управление элементами HTML-страницы.',
                    ['web-development'],
                ],
                [
                    'Асинхронный JavaScript',
                    'async-javascript',
                    'Асинхронные операции и обработка результатов.',
                ],
                [
                    'Promises в JavaScript',
                    'javascript-promises',
                    'Работа с объектами Promise.',
                ],
                [
                    'Async и Await',
                    'javascript-async-await',
                    'Удобная организация асинхронного кода.',
                ],
                [
                    'Fetch API',
                    'javascript-fetch-api',
                    'Выполнение HTTP-запросов из браузера.',
                    ['web-development'],
                ],
                [
                    'Массивы и методы JavaScript',
                    'javascript-arrays',
                    'Использование map, filter и reduce.',
                ],
                [
                    'Замыкания в JavaScript',
                    'javascript-closures',
                    'Область видимости и замыкания.',
                ],
                [
                    'Модули JavaScript',
                    'javascript-modules',
                    'Организация кода с помощью import и export.',
                ],
                [
                    'Обработка событий',
                    'javascript-events',
                    'Взаимодействие пользователя с интерфейсом.',
                    ['web-development'],
                ],
            ],

            'databases' => [
                [
                    'Введение в MySQL',
                    'introduction-to-mysql',
                    'Основы реляционных баз данных.',
                ],
                [
                    'Проектирование базы данных',
                    'database-design',
                    'Создание таблиц и организация связей между ними.',
                ],
                [
                    'SQL JOIN',
                    'sql-joins',
                    'Объединение данных из нескольких таблиц.',
                ],
                [
                    'Индексы в MySQL',
                    'mysql-indexes',
                    'Использование индексов для оптимизации запросов.',
                ],
                [
                    'Транзакции в MySQL',
                    'mysql-transactions',
                    'Целостность данных и управление транзакциями.',
                    ['php'],
                ],
                [
                    'Нормализация базы данных',
                    'database-normalization',
                    'Устранение избыточности данных.',
                ],
                [
                    'Подготовленные SQL-запросы',
                    'sql-prepared-statements',
                    'Безопасное выполнение параметризованных запросов.',
                    ['php'],
                ],
                [
                    'Оптимизация SQL-запросов',
                    'sql-query-optimization',
                    'Анализ и повышение производительности запросов.',
                ],
            ],

            'web-development' => [
                [
                    'Основы HTML5',
                    'html5-basics',
                    'Семантическая структура веб-страницы.',
                ],
                [
                    'Современный CSS',
                    'modern-css',
                    'Современные возможности оформления сайтов.',
                ],
                [
                    'SCSS для веб-разработчиков',
                    'scss-guide',
                    'Переменные, вложенность и организация стилей.',
                ],
                [
                    'Адаптивный веб-дизайн',
                    'responsive-web-design',
                    'Создание интерфейсов для разных устройств.',
                ],
                [
                    'Архитектура MVC',
                    'mvc-architecture',
                    'Разделение логики, данных и представления.',
                    ['php'],
                ],
                [
                    'Принципы REST',
                    'rest-principles',
                    'Основы архитектуры веб-сервисов.',
                    ['php', 'javascript'],
                ],
                [
                    'HTTP и HTTPS',
                    'http-and-https',
                    'Принципы взаимодействия клиента и сервера.',
                    ['devops'],
                ],
            ],

            'devops' => [
                [
                    'Основы Linux для разработчика',
                    'linux-basics',
                    'Работа с терминалом и файловой системой.',
                ],
                [
                    'Настройка Nginx',
                    'nginx-configuration',
                    'Настройка веб-сервера для PHP-приложений.',
                    ['php'],
                ],
                [
                    'Работа PHP-FPM',
                    'php-fpm-guide',
                    'Взаимодействие PHP-FPM и веб-сервера.',
                    ['php'],
                ],
                [
                    'Git для разработчиков',
                    'git-guide',
                    'Основы управления версиями исходного кода.',
                ],
                [
                    'Развёртывание веб-приложений',
                    'web-application-deployment',
                    'Подготовка приложения к размещению на сервере.',
                    ['web-development'],
                ],
                [
                    'Права доступа в Linux',
                    'linux-file-permissions',
                    'Владельцы, группы и разрешения файлов.',
                ],
                [
                    'Работа с логами сервера',
                    'server-logs',
                    'Диагностика ошибок веб-приложений.',
                ],
            ],

            'artificial-intelligence' => [
                [
                    'Введение в искусственный интеллект',
                    'introduction-to-ai',
                    'Основные направления искусственного интеллекта.',
                ],
                [
                    'Что такое машинное обучение',
                    'machine-learning-basics',
                    'Основные принципы машинного обучения.',
                ],
                [
                    'Нейронные сети',
                    'neural-networks',
                    'Общие принципы работы нейронных сетей.',
                ],
                [
                    'Большие языковые модели',
                    'large-language-models',
                    'Принципы применения языковых моделей.',
                ],
                [
                    'Искусственный интеллект в программировании',
                    'ai-in-programming',
                    'Использование ИИ при разработке приложений.',
                    ['php', 'web-development'],
                ],
                [
                    'Интеграция AI API',
                    'ai-api-integration',
                    'Интеграция сервисов искусственного интеллекта.',
                    ['php', 'javascript'],
                ],
            ],
        ];
    }
}