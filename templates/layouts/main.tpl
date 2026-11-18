<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>{$title|escape} — PHP Blog</title>

    <link rel="stylesheet"
          href="/assets/css/main.css">
</head>

<body>

    {include file="partials/header.tpl"}

    <main class="container main-content">

        {block name="content"}
        {/block}

    </main>

    {include file="partials/footer.tpl"}

</body>
</html>