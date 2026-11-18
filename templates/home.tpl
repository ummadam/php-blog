{extends file="layouts/main.tpl"}

{block name="content"}

    <section class="hero">

        <span class="hero-label">
            TECHNOLOGY & DEVELOPMENT
        </span>

        <h1>Блог о технологиях</h1>

        <p>
            Программирование, веб-разработка,
            базы данных и современные технологии.
        </p>

    </section>

    {foreach $categories as $category}

        <section class="category-section">

            <div class="category-header">

                <div>

                    <h2 class="category-title">
                        {$category.name|escape}

                        <span class="category-count">
                            {$category.articles_count|escape}
                        </span>
                    </h2>

                    <p class="category-description">
                        {$category.description|escape}
                    </p>

                </div>

                <a
                    href="/category/{$category.slug|escape:'url'}"
                    class="btn btn-outline"
                >
                    Все статьи &rarr;
                </a>

            </div>

            <div class="articles-grid">

                {foreach $category.articles as $article}

                    {include
                        file="partials/article-card.tpl"
                        article=$article
                    }

                {/foreach}

            </div>

        </section>

    {foreachelse}

        <div class="empty-state">

            <h2>Пока нет статей</h2>

            <p>Новые публикации появятся здесь.</p>

        </div>

    {/foreach}

{/block}