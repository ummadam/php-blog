<article class="article-card">

    <a href="/article/{$article.slug|escape:'url'}"
       class="article-image">

        <img
            src="{$article.image|escape}"
            alt="{$article.title|escape}"
            loading="lazy"
        >

    </a>

    <div class="article-card-body">

        <div class="article-meta">

            <time>
                {$article.published_at|date_format:"%d.%m.%Y"}
            </time>

            <span>
                {$article.views|escape} просмотров
            </span>

        </div>

        <h3 class="article-title">

            <a href="/article/{$article.slug|escape:'url'}">
                {$article.title|escape}
            </a>

        </h3>

        <p class="article-description">
            {$article.description|escape}
        </p>

        <a href="/article/{$article.slug|escape:'url'}"
           class="article-link">
            Читать статью &rarr;
        </a>

    </div>

</article>