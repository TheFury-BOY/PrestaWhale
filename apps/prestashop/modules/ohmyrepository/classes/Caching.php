<?php


namespace Prestashop\Module\OhMyRepository;

class Caching
{
    public static function cacheDisplay($tpl)
    {
        $smarty = new \Smarty;
    
        // retain current cache lifetime for each specific display call
        $smarty->setCaching(\Smarty::CACHING_LIFETIME_SAVED);
        
        // set the cache_lifetime for home.tpl to 1 hour
        $smarty->setCacheLifetime(300);

        return $smarty->display($tpl);

    }
}
