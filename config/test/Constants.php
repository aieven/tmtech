<?php
    namespace Cerceau\Config;

    abstract class Constants {
        const
            DATABASE_SPOT_SIZE = 10000,
            REDIS_SPOT_SIZE = 100000,

            ROUTES_CONFIG_DIR = 'config/routes/',
            DYNAMIC_CONFIG_PATH = 'config/dynamic/test/',
            NGINX_CONFIGS = 'config/dynamic/test/nginx/',
            REDIS_CONFIGS = 'config/dynamic/test/redis/',

            INSTAGRAM_SCOPE = 'likes comments relationships',
            INSTAGRAM_REDIRECT_URL = 'https://classygram.com/api/auth/instagram/',
            INSTAGRAM_CLIENT_ID = 'ec3bf6ed70884639a56cd44c1d9662fa',
            INSTAGRAM_CLIENT_SECRET = 'a740b9c13f994c888c4ef2591bd61b19'
        ;
    }
