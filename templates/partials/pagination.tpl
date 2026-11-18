{if $pagination.total_pages > 1}

    <nav class="pagination" aria-label="Пагинация">

        {if $pagination.has_previous}

            <a
                href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;page={$pagination.previous_page}"
                class="pagination-item pagination-arrow"
                aria-label="Предыдущая страница"
            >
                &larr;
            </a>

        {/if}


        {if $pagination.start_page > 1}

            <a
                href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;page=1"
                class="pagination-item"
            >
                1
            </a>

            {if $pagination.start_page > 2}

                <span class="pagination-dots">
                    ...
                </span>

            {/if}

        {/if}


        {for $pageNumber = $pagination.start_page to $pagination.end_page}

            {if $pageNumber == $pagination.current_page}

                <span
                    class="pagination-item active"
                    aria-current="page"
                >
                    {$pageNumber}
                </span>

            {else}

                <a
                    href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;page={$pageNumber}"
                    class="pagination-item"
                >
                    {$pageNumber}
                </a>

            {/if}

        {/for}


        {if $pagination.end_page < $pagination.total_pages}

            {if $pagination.end_page < $pagination.total_pages - 1}

                <span class="pagination-dots">
                    ...
                </span>

            {/if}

            <a
                href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;page={$pagination.total_pages}"
                class="pagination-item"
            >
                {$pagination.total_pages}
            </a>

        {/if}


        {if $pagination.has_next}

            <a
                href="/category/{$category.slug|escape:'url'}?sort={$sort|escape:'url'}&amp;page={$pagination.next_page}"
                class="pagination-item pagination-arrow"
                aria-label="Следующая страница"
            >
                &rarr;
            </a>

        {/if}

    </nav>

{/if}