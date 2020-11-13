<?php

namespace Prestashop\Module\OhMyRepository;

class GitHubApi
{

    public static function getCommit()
    {
        $github_repository_name = \Configuration::get('github_repository_name');
        $github_account_name = \Configuration::get('github_account_name');
        $number_of_commits = \Configuration::get('number_of_commits');

        $client = new \Github\Client();
        
        try {
            $commits = $client->api('repo')->commits()->all($github_account_name, $github_repository_name, ['path' => ""]);

            $filter_commits = array();
            $count = 0;
            foreach ($commits as $commit) {
                if ($count > $number_of_commits) {
                    break;
                }

                $commit = array(
                    'message' => $commit['commit']['message'], 
                    'author' => $commit['commit']['author']['name'],
                    'date' => $commit['commit']['author']['date']
                );
                array_push($filter_commits, $commit);
                
                $count++;
            }
            $params = array(
                'repository' => $github_repository_name,
                'commits' => $filter_commits
            );
            return $params;
        } catch (\RuntimeException $e) {
            echo "Github API Access Error.";
        }
    }
}