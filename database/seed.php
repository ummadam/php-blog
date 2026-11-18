<?php

declare(strict_types=1);

use App\Service\DB;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ArticleSeeder;

require_once dirname(__DIR__) . '/vendor/autoload.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Access denied.');
}

echo 'Starting database seeding...' . PHP_EOL;

$pdo = null;

try {
    $pdo = DB::get();

    $pdo->beginTransaction();

    $categorySeeder = new CategorySeeder($pdo);
    $categorySeeder->run();

    $articleSeeder = new ArticleSeeder($pdo);
    $articleSeeder->run();

    $pdo->commit();

    echo PHP_EOL;
    echo 'Database seeded successfully!' . PHP_EOL;

} catch (Throwable $exception) {
    if ($pdo !== null && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    fwrite(
        STDERR,
        'Seeding failed: '
        . $exception->getMessage()
        . PHP_EOL
    );

    exit(1);
}