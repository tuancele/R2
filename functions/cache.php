<?php
use Phpfastcache\CacheManager;
use Phpfastcache\Config\Config;
use Phpfastcache\Core\phpFastCache;

function nou_leopard_offload_media_instance_cache(){

    CacheManager::setDefaultConfig(new Config([
        "path" => LEOPARD_WORDPRESS_OFFLOAD_MEDIA_CACHE_PATH,
        "itemDetailedDate" => false
    ]));
    
    $InstanceCache = CacheManager::getInstance('files');
    
    return $InstanceCache;
}