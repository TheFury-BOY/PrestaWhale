<div id="ohmyrepository" class="col-12">
    <h1 class="title"><a href="{$repository_url}" target="_blank">{$repository}</a></h1>
    {foreach $commits as $commit}
    <div class="col-sm-12 col-md-4 col-lg-3">
        <div class="card">
            <a href="{$author_url}"><img class="avatar" src="{$committer_avatar}" alt="{$committer_login}" style="width:100%"></a>
            <div class="container">
                <h2 class="h3 text-center">{$commit.message}</h2>
                <p class="text-center"><time class="text-muted" datetime="{$commit.date}">{$commit.date}</time> by <a href="{$author_url}" target="_blank">{$commit.author}</a></p>
            </div>
        </div>
    </div>
    {/foreach}
</div>