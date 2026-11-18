<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use App\Model\Article;
use App\Service\View;

class CategoryController
{
    private const PER_PAGE = 6;

    public function show(string $slug): void
    {
        $categoryModel = new Category();
        $articleModel = new Article();
        $view = new View();

        // Получаем категорию
        $category = $categoryModel->findBySlug($slug);

        if ($category === null) {
            http_response_code(404);

            $view->render('404.tpl', [
                'title' => 'Категория не найдена',
            ]);

            return;
        }

        // Получаем параметр сортировки
        $sort = $_GET['sort'] ?? 'date';

        if (!in_array($sort, ['date', 'views'], true)) {
            $sort = 'date';
        }

        // Получаем номер страницы
        $page = filter_var(
            $_GET['page'] ?? 1,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1,
                ],
            ]
        );

        if ($page === false) {
            $page = 1;
        }

        $categoryId = (int) $category['id'];

        // Общее количество статей
        $totalArticles = $articleModel
            ->countByCategory($categoryId);

        // Общее количество страниц
        $totalPages = max(
            1,
            (int) ceil($totalArticles / self::PER_PAGE)
        );

        // Обработка несуществующей страницы
        if ($page > $totalPages) {
            http_response_code(404);

            $view->render('404.tpl', [
                'title' => 'Страница не найдена',
            ]);

            return;
        }

        // Рассчитываем смещение
        $offset = ($page - 1) * self::PER_PAGE;

        // Получаем статьи текущей страницы
        $articles = $articleModel->getByCategory(
            $categoryId,
            $sort,
            self::PER_PAGE,
            $offset
        );

        // Формируем данные пагинации
        $pagination = [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_articles' => $totalArticles,
            'per_page' => self::PER_PAGE,

            'has_previous' => $page > 1,
            'has_next' => $page < $totalPages,

            'previous_page' => max(1, $page - 1),
            'next_page' => min($totalPages, $page + 1),

            'start_page' => max(1, $page - 2),
            'end_page' => min($totalPages, $page + 2),

            'from' => $totalArticles > 0
                ? $offset + 1
                : 0,

            'to' => min(
                $offset + self::PER_PAGE,
                $totalArticles
            ),
        ];

        $view->render('category.tpl', [
            'title' => $category['name'],
            'category' => $category,
            'articles' => $articles,
            'sort' => $sort,
            'pagination' => $pagination,
        ]);
    }
}