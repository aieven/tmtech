<?php
    namespace Cerceau\Config;

    abstract class Constants {
        const
            DATABASE_SPOT_SIZE = 10000,
            REDIS_SPOT_SIZE = 100000,

            ROUTES_CONFIG_DIR = 'config/routes/',
            DYNAMIC_CONFIG_PATH = 'config/redis/',
            NGINX_CONFIGS = 'config/dynamic/nginx/',
            REDIS_CONFIGS = 'config/redis/redispreprod/'
        ;
    }
