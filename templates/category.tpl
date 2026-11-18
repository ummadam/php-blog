{extends file="layouts/main.tpl"}

{block name="content"}

    <nav class="breadcrumbs" aria-label="Хлебные крошки">

        <a href="/">Главная</a>

        <span class="breadcrumbs-separator">/</span>

        <span class="breadcrumbs-current">
            {$category.name|escape}
        </span>

    </nav>


    <section class="category-hero">

        <span class="hero-label">
            КАТЕГОРИЯ
        </span>

        <h1>
            {$category.name|escape}
        </h1>

        <p>
            {$category.description|escape}
        </p>

        <span class="category-total">
            Всего статей:
            {$pagination.total_articles}
        </span>

    </section>


    <section class="category-articles">

        <div class="articles-toolbar">

            <div class="articles-result">

                {if $pagination.total_articles > 0}

                    Показано
                    {$pagination.from}–{$pagination.to}
                    из {$pagination.total_articles}

                {else}

                    Нет публикаций

                {/if}

            </div>


            <div class="sort-control">

                <span class="sort-label">
                    Сортировка:
                </span>

                <a
                    href="/category/{$category.slug|escape:'url'}?sort=date"
                    class="sort-option {if $sort == 'date'}active{/if}"
                >
                    По дате
                </a>

                <a
                    href="/category/{$category.slug|escape:'url'}?sort=views"
                    class="sort-option {if $sort == 'views'}active{/if}"
                >
                    По просмотрам
                </a>

            </div>

        </div>


        {if $articles}

            <div class="articles-grid">

                {foreach $articles as $article}

                    {include
                        file="partials/article-card.tpl"
                        article=$article
                    }

                {/foreach}

            </div>


            {include
                file="partials/pagination.tpl"
                pagination=$pagination
                sort=$sort
                category=$category
            }

        {else}

            <div class="empty-state">

                <h2>В этой категории пока нет статей</h2>

                <p>
                    Новые публикации появятся здесь.
                </p>

                <a href="/" class="btn btn-outline">
                    Вернуться на главную
                </a>

            </div>

        {/if}

    </section>

{/block}