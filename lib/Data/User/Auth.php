<?php
    namespace Cerceau\Data\User;

	class Auth extends \Cerceau\Data\Base\DbRow implements \Cerceau\Data\User\IAuth {
        const PASSWORD_SALT = 'Euhfq9g34p5g';

        protected static $modelName = 'PostgreSQL\\Base';
        protected static $db = 'main';
        protected static $table = 'admins';

        protected function initialize(){
            self::$fieldsOptions = array(
                'admin_id' => array(
                    'Int',
                    'load',
                    'autoIncrement',
                    'const',
                ),
                'email' => array(
                    'String',
                    'load',
                    'validation' => array(
                        'email',
                        'notEmpty',
                    ),
                    'validationPrefix' => 'auth.forms.email',
                ),
                'password' => array(
                    'Scalar',
                    'load',
                    'validation' => array(
                        'notEmpty',
                    ),
                    'validationPrefix' => 'auth.forms.password',
                ),
                'privileges' => array(
                    'Set',
                    'types' => Privileges::names(),
                    'default' => array()
                ),
            );
            parent::initialize();
        }

        /**
         * @return bool
         */
        public function isReal(){
            return null !== $this->Storage['admin_id'];
        }

        /**
         * @return int
         */
        public function getId(){
            return $this->Storage['admin_id'];
        }

        /**
         * @param array $a
         * @return array
         */
        public function preFetch( $a ){
            // password hashing
            if( array_key_exists( 'password', $a ))
                $a['password'] = $this->passwordHash( $a['password'] );

            return $a;
        }

        /**
         * @param string $password
         * @return string
         */
        public static function passwordHash( $password ){
            return sha1( self::PASSWORD_SALT . sha1( $password ) );
        }

        /**
         * @return string
         */
        public function generatePassword(){
            $alphabet = 'abcdefghijklmopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789;:!@#$%&()[]{}';
            $password = '';

            $i = 10;
            $l = strlen( $alphabet ) - 1;
            while( $i-- )
                $password .= substr( $alphabet, mt_rand( 0, $l ), 1 );

            $this['password'] = self::passwordHash( $password );
            return $password;
        }

        /**
         * @param int $action
         * @return bool|string
         */
        public function can( $action ){
            $privileges = $this['privileges']->export();

            return in_array( $action, $privileges );
        }
    }
