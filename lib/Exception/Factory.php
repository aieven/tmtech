<?php

    namespace Cerceau\Exception;

    abstract class Factory {

        const
            VALIDATION_ERROR = 1,
            REQUEST_ERROR = 2,
            SESSION_ERROR = 3,

            REGISTER_ERROR = 100,

            UPLOAD_FAILED = 110,
            UPLOAD_ERROR = 111,
            UPLOAD_WEBDAV_ERROR = 112,
            UPLOAD_ALLOCATE_RESOURCE_ERROR = 113,
            UPLOAD_FORMAT_ERROR = 114,

            DATABASE_ERROR = 120,

            REDIS_ERROR = 130,

            UNAUTHORIZED = 401,
            AUTHORIZE_FAILED = 402,
            FORBIDDEN = 403,
            NOT_FOUND = 404,

            FACEBOOK_AUTH_ERROR = 601,
            FACEBOOK_STATE_MISSING = 602,
            FACEBOOK_STATE_WRONG = 603,

            INSTAGRAM_AUTH_ERROR = 801,
            INSTAGRAM_STATE_MISSING = 802,
            INSTAGRAM_STATE_WRONG = 803,

            UNKNOWN_ERROR = 666
        ;

        public static function throwError( $code, $hack = false ){
            if( $hack )
                throw new HackAttempt( 'Error', $code );
            else
                throw new Page( 'Error', $code );
        }
    }