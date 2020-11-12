<?php

namespace Prestashop\Module\OhMyRepository;

class GitHubApi
{

    public function getCommit()
    {
        $github_repository_name = strval(\Tools::getValue('github_repository_name'));
        $github_account = strval(\Tools::getValue('githubhub_account'));

        $client = new \Github\Client();
        try {
            $repository = $client->api('user')->repositories($github_repository_name);
            $commits = $client->api('repo')->commits()->all($github_account, $github_repository_name, ['path' => ""]);

            $params = array(
                'repository' => $repository,
                'commit' => $commits
            );

            return $params;
        } catch (\RuntimeException $e) {
            echo "Github API Access Error.";
        }
    }
}