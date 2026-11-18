<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\DB;
use PDO;

class Category
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function getWithArticles(): array
    {
        $sql = '
            SELECT
                c.id,
                c.name,
                c.slug,
                c.description,
                COUNT(a.id) AS articles_count
            FROM categories AS c
            INNER JOIN article_category AS ac
                ON ac.category_id = c.id
            INNER JOIN articles AS a
                ON a.id = ac.article_id
            WHERE a.published_at <= NOW()
            GROUP BY
                c.id,
                c.name,
                c.slug,
                c.description
            ORDER BY c.id ASC
        ';

        return $this->pdo
            ->query($sql)
            ->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = '
            SELECT
                id,
                name,
                slug,
                description
            FROM categories
            WHERE slug = :slug
            LIMIT 1
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'slug' => $slug,
        ]);

        $category = $statement->fetch();

        return $category ?: null;
    }
}