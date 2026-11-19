{extends file="layouts/main.tpl"}

{block name="content"}

    <nav class="breadcrumbs" aria-label="Хлебные крошки">

        <a href="/">Главная</a>

        <span class="breadcrumbs-separator">/</span>

        <span class="breadcrumbs-current">
            {$article.title|escape}
        </span>

    </nav>


    <article class="article-detail">

        <header class="article-detail-header">

            {if $categories}

                <div class="article-categories">

                    {foreach $categories as $category}

                        <a
                            href="/category/{$category.slug|escape:'url'}"
                            class="article-category-badge"
                        >
                            {$category.name|escape}
                        </a>

                    {/foreach}

                </div>

            {/if}


            <h1 class="article-detail-title">
                {$article.title|escape}
            </h1>


            <p class="article-detail-description">
                {$article.description|escape}
            </p>


            <div class="article-detail-meta">

                <div class="article-meta-item">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        aria-hidden="true"
                    >
                        <rect
                            x="3"
                            y="5"
                            width="18"
                            height="16"
                            rx="2"
                        />
                        <path d="M16 3v4M8 3v4M3 10h18"/>
                    </svg>

                    <time>
                        {$article.published_at|date_format:"%d.%m.%Y"}
                    </time>

                </div>


                <div class="article-meta-item">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        aria-hidden="true"
                    >
                        <path
                            d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"
                        />
                        <circle cx="12" cy="12" r="3"/>
                    </svg>

                    <span>
                        {$article.views|escape} просмотров
                    </span>

                </div>

            </div>

        </header>


        <div class="article-detail-image">

            <img
                src="{$article.image|escape}"
                alt="{$article.title|escape}"
            >

        </div>


        <div class="article-detail-content">

            {$article.content|escape|nl2br}

        </div>


        <footer class="article-detail-footer">

            <a href="/" class="btn btn-outline">
                &larr; Вернуться на главную
            </a>

        </footer>

    </article>


    {if $relatedArticles}

        <section class="related-articles">

            <div class="related-articles-header">

                <h2>Похожие статьи</h2>

                <p>
                    Другие публикации по этой тематике
                </p>

            </div>


            <div class="articles-grid">

                {foreach $relatedArticles as $relatedArticle}

                    {include
                        file="partials/article-card.tpl"
                        article=$relatedArticle
                    }

                {/foreach}

            </div>

        </section>

    {/if}

{/block}