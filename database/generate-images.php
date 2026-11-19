<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit('Access denied.');
}

$directory = dirname(__DIR__) . '/public/assets/images';

if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

$images = [
    'php' => [
        'title' => 'PHP',
        'color' => '#777BB4',
    ],
    'javascript' => [
        'title' => 'JavaScript',
        'color' => '#B59B00',
    ],
    'databases' => [
        'title' => 'Database',
        'color' => '#00758F',
    ],
    'web-development' => [
        'title' => 'Web Development',
        'color' => '#2563EB',
    ],
    'devops' => [
        'title' => 'DevOps',
        'color' => '#0F766E',
    ],
    'artificial-intelligence' => [
        'title' => 'Artificial Intelligence',
        'color' => '#7C3AED',
    ],
];

foreach ($images as $slug => $image) {
    $title = htmlspecialchars(
        $image['title'],
        ENT_QUOTES | ENT_XML1,
        'UTF-8'
    );

    $color = $image['color'];

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg"
     width="800"
     height="450"
     viewBox="0 0 800 450">

    <rect width="800"
          height="450"
          fill="{$color}"/>

    <circle cx="750"
            cy="50"
            r="170"
            fill="#FFFFFF"
            opacity="0.08"/>

    <circle cx="50"
            cy="430"
            r="180"
            fill="#FFFFFF"
            opacity="0.08"/>

    <text x="400"
          y="225"
          text-anchor="middle"
          dominant-baseline="middle"
          fill="#FFFFFF"
          font-size="42"
          font-family="Arial, sans-serif"
          font-weight="bold">{$title}</text>

    <text x="400"
          y="290"
          text-anchor="middle"
          fill="#FFFFFF"
          opacity="0.8"
          font-size="18"
          font-family="Arial, sans-serif">PHP BLOG</text>

</svg>
SVG;

    $result = file_put_contents(
        $directory . '/' . $slug . '.svg',
        $svg
    );

    if ($result === false) {
        fwrite(
            STDERR,
            'Failed to create ' . $slug . '.svg' . PHP_EOL
        );

        exit(1);
    }

    echo 'Created: ' . $slug . '.svg' . PHP_EOL;
}

echo PHP_EOL . 'All images generated successfully!' . PHP_EOL;