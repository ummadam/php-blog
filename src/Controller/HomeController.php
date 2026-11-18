<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Category;
use App\Model\Article;
use App\Service\View;

class HomeController
{
    public function index(): void
    {
        $categoryModel = new Category();

        $articleModel = new Article();

        $categories = $categoryModel->getWithArticles();

        $articlesByCategory = $articleModel
            ->getLatestByCategories(3);

        foreach ($categories as &$category) {
            $categoryId = (int) $category['id'];

            $category['articles'] =
                $articlesByCategory[$categoryId] ?? [];
        }

        unset($category);

        $view = new View();

        $view->render('home.tpl', [
            'title' => 'Главная страница',
            'categories' => $categories,
        ]);
    }
}