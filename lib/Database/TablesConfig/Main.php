<?php
    namespace Cerceau\Database\TablesConfig;

	class Main extends Base {
        protected static $tables = array(
            'admins'            => 'admins',
            'bots'              => 'bots',
            'users'             => 'users',
            'categories'        => 'categories',
            'repost'            => 'reposts',
            'tags'              => 'tags',
            'bot_queries'       => 'bot_queries',
            'bot_likes'         => 'bot_likes',
            'bot_likes_index'   => 'bot_likes_index',

            'snapshots'         => 'snapshots',
            'gal_categories'    => 'gallery_categories',

            'publics'           => 'publics',
            'statistics'    => 'statistics',
            'stats'         => 'stats',
            'all_media'     => 'all_media',
            'admins_tokens' => 'admins_instagram_tokens',
            'feedback_messages' => 'feedback_messages',
            'events'        => 'events',

            'people_categories'     => 'people_categories',
            'people_subcategories'  => 'people_subcategories',
            'brands_categories'     => 'brands_categories',
            'brands_subcategories'  => 'brands_subcategories',
            'people_publics'        => 'people_publics',
            'brands_publics'        => 'brands_publics',
            'gallery_publics'       => 'gallery_publics',
            'people'                => 'people',
            'brands'                => 'brands',
            'people_statistics'     => 'people_statistics',
            'media'                 => 'media',

        );
	}