<?php

declare(strict_types=1);

namespace App\Model;

use App\Service\DB;
use PDO;

class Article
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::get();
    }

    public function getLatestByCategories(
        int $limit = 3
    ): array {
        $sql = '
            SELECT
                ranked.id,
                ranked.title,
                ranked.slug,
                ranked.image,
                ranked.description,
                ranked.views,
                ranked.published_at,
                ranked.category_id
            FROM (
                SELECT
                    a.id,
                    a.title,
                    a.slug,
                    a.image,
                    a.description,
                    a.views,
                    a.published_at,
                    ac.category_id,

                    ROW_NUMBER() OVER (
                        PARTITION BY ac.category_id
                        ORDER BY
                            a.published_at DESC,
                            a.id DESC
                    ) AS row_num

                FROM articles AS a

                INNER JOIN article_category AS ac
                    ON ac.article_id = a.id

                WHERE a.published_at <= NOW()

            ) AS ranked

            WHERE ranked.row_num <= :limit

            ORDER BY
                ranked.category_id ASC,
                ranked.row_num ASC
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $statement->execute();

        $articles = $statement->fetchAll();

        $groupedArticles = [];

        foreach ($articles as $article) {
            $categoryId = (int) $article['category_id'];

            $groupedArticles[$categoryId][] = $article;
        }

        return $groupedArticles;
    }

    public function countByCategory(int $categoryId): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM articles AS a

            INNER JOIN article_category AS ac
                ON ac.article_id = a.id

            WHERE ac.category_id = :category_id
            AND a.published_at <= NOW()
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'category_id' => $categoryId,
        ]);

        return (int) $statement->fetchColumn();
    }

    public function getByCategory(
    int $categoryId,
    string $sort = 'date',
    int $limit = 6,
    int $offset = 0
    ): array {
        $orderBy = match ($sort) {
            'views' => 'a.views DESC, a.published_at DESC, a.id DESC',
            default => 'a.published_at DESC, a.id DESC',
        };

        $sql = "
            SELECT
                a.id,
                a.title,
                a.slug,
                a.image,
                a.description,
                a.views,
                a.published_at

            FROM articles AS a

            INNER JOIN article_category AS ac
                ON ac.article_id = a.id

            WHERE ac.category_id = :category_id
            AND a.published_at <= NOW()

            ORDER BY {$orderBy}

            LIMIT :limit
            OFFSET :offset
        ";

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(
            ':category_id',
            $categoryId,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':offset',
            $offset,
            PDO::PARAM_INT
        );

        $statement->execute();

        return $statement->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $sql = '
            SELECT
                id,
                title,
                slug,
                image,
                description,
                content,
                views,
                published_at,
                created_at,
                updated_at
            FROM articles
            WHERE slug = :slug
            AND published_at <= NOW()
            LIMIT 1
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            'slug' => $slug,
        ]);

        $article = $statement->fetch();

        return $article ?: null;
    }

    public function getRelatedArticles(
        int $articleId,
        int $limit = 3
    ): array {
        $sql = '
            SELECT
                a.id,
                a.title,
                a.slug,
                a.image,
                a.description,
                a.views,
                a.published_at,

                related.shared_categories

            FROM articles AS a

            INNER JOIN (
                SELECT
                    ac.article_id,
                    COUNT(*) AS shared_categories

                FROM article_category AS ac

                INNER JOIN article_category AS current_ac
                    ON current_ac.category_id = ac.category_id

                WHERE current_ac.article_id = :current_article_id
                AND ac.article_id != :excluded_article_id

                GROUP BY ac.article_id

            ) AS related
                ON related.article_id = a.id

            WHERE a.published_at <= NOW()

            ORDER BY
                related.shared_categories DESC,
                a.published_at DESC,
                a.id DESC

            LIMIT :limit
        ';

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(
            ':current_article_id',
            $articleId,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':excluded_article_id',
            $articleId,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $statement->execute();

        return $statement->fetchAll();
    }
}