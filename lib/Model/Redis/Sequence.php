<?php
    namespace Cerceau\Model\Redis;

	class Sequence extends Base implements \Cerceau\Model\I\ISequence {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_sequence_'. $name;
        }

        public function allocateId(){
            try {
                $id = $this->client()->incr( $this->name );
                if( $id )
                    return intval( $id );

                throw new \RuntimeException( 'Couldn\'t allocate id for '. $this->name );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function lastId(){
            try {
                $id = $this->client()->get( $this->name );
                if( $id )
                    return intval( $id );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return false;
        }
    }
