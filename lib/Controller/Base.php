<?php
    namespace Cerceau\Controller;

    abstract class Base extends \Cerceau\System\Controller {
        /**
         * @var \Cerceau\Data\User\IAuth
         */
        protected $Auth;

        protected function initialize(){
            if(!$this->Auth ){
                try {
                    $this->Auth = new \Cerceau\Data\User\Auth();
                    if( isset( $this->Session['user'] ))
                        $this->Auth->fetch( $this->Session['user'] );
                }
                catch( \Cerceau\Exception\Authorize $E ){
                    if( isset( $this->Session['user'] ))
                        unset( $this->Session['user'] );
                }
                catch( \Exception $E ){
                    throw $E;
                }
            }
            if(!$this->authorization())
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );
        }

        protected function authorization(){
            return true;
        }

        protected function deinitialize(){
            if( $this->Auth && $this->Auth->isReal())
                $this->Session['user'] = $this->Auth->export();
            else
                unset( $this->Session['user'] );
        }

        protected function deinitializeNative(){
            $this->View->globals( 'domain_url', \Cerceau\System\Registry::instance()->DomainConfig()->main());
            $this->View->globals( 'base_url', \Cerceau\System\Registry::instance()->Router()->getUrl());
        }
    }
