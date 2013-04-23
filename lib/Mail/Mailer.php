<?php
	namespace Cerceau\Mail;

    abstract class Mailer {
        /**
         * @var Config $Config
         */
        protected $Config;

        private function __construct( Config $Config ){
            $this->Config = $Config;
        }

        /**
         * @abstract
         * @param string $to
         * @param string $subject
         * @param string $content
         * @return bool
         */
        abstract public function send( $to, $subject, $content );

        /**
         * @abstract
         * @return string|null
         */
        abstract public function lastError();

        /**
         * @static
         * @param Config $Config
         * @return Mailer
         * @throws \UnexpectedValueException
         */
        final public static function get( Config $Config ){
            $className = __NAMESPACE__ .'\\'. ucfirst( $Config->type );
            if(!class_exists( $className, true ))
                throw new \UnexpectedValueException( $className .'" is not implemented' );

            return new $className( $Config );
        }
    }
	