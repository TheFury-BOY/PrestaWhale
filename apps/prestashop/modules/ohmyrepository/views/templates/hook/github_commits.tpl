<div id="ohmyrepository" class="row">
    <div class="col-12">
        <h1>{$repository}</h1>
        {foreach $commits as $commit}
        <div class="card bg-secondary text-white col-sm-12 col-md-4 col-lg-3 m-2">
            <div class="card-body m-2">
                <h2 class="card-title h3 text-center">Commit</h2>
                <h3 class="card-subtitle mb-2 text-muted h4">{$commit.author}</h3>
                <p class="card-text text-justify">{$commit.message}</p>
                <p class="card-text text-muted text-right">{$commit.date}</p>
            </div>
        </div>
        {/foreach}
    </div>
</div>