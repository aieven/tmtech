<?php
    namespace Cerceau\Config;

    abstract class Constants {
        const
            DATABASE_SPOT_SIZE = 10000000,
            REDIS_SPOT_SIZE = 10000000,

            ROUTES_CONFIG_DIR = 'config/routes/',
            DYNAMIC_CONFIG_PATH = 'config/redis/',
            NGINX_CONFIGS = 'config/dynamic/nginx/',
            REDIS_CONFIGS = 'config/redis/redis/',

            INSTAGRAM_SCOPE = 'likes comments relationships',
            INSTAGRAM_REDIRECT_URL = 'https://classygram.com/',
            INSTAGRAM_CLIENT_ID = '1abba04b09e64a1fb7b10632093528d2',
            INSTAGRAM_CLIENT_SECRET = 'b6217f31e8c44851a01ab2ecbf223aff',
            
            INSTAGRAM_BOT_REDIRECT_URL = 'https://classygram.com/api/auth/instagram/',
            INSTAGRAM_BOT_CLIENT_ID = '9712319f943c4285bba7a0e91fec3bec',
            INSTAGRAM_BOT_CLIENT_SECRET = 'bd39c0878d0e4e1c9aa1d96a6bbd4316'
        ;
    }
