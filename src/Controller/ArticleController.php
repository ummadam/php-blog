<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Article;
use App\Model\Category;
use App\Service\View;

class ArticleController
{
    public function show(string $slug): void
    {
        $articleModel = new Article();
        $categoryModel = new Category();
        $view = new View();

        // Получаем текущую статью
        $article = $articleModel->findBySlug($slug);

        // Проверяем существование статьи
        if ($article === null) {
            http_response_code(404);

            $view->render('404.tpl', [
                'title' => 'Статья не найдена',
            ]);

            return;
        }

        $articleId = (int) $article['id'];

        // Получаем категории статьи
        $categories = $categoryModel->getByArticle(
            $articleId
        );

        // Получаем три похожие статьи
        $relatedArticles = $articleModel->getRelatedArticles(
            $articleId,
            3
        );

        // Передаём данные в шаблон
        $view->render('article.tpl', [
            'title' => $article['title'],
            'article' => $article,
            'categories' => $categories,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}