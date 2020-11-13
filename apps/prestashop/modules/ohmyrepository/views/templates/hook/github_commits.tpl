<div id="ohmyrepository">
    <h1>{$repository}Test</h1>
    <ul class="list-group list-group-flush">
        {foreach $commits as $commit}
        <li class="list-group-item">{$commit}</li>
        {/foreach}
    </ul>
</div>