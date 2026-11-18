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
}