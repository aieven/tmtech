<?php
    namespace Cerceau\Controller\Admin;

    abstract class Base extends \Cerceau\Controller\Base {

        protected function initialize(){
            \Cerceau\Config\Special::instance()->set( 'locale', 'ru_RU' );
            parent::initialize();
        }

        protected function authorization(){
            $this->unauthorizedPath = \Cerceau\System\Registry::instance()->Url()->page( 'main', 'admin' );

            return $this->Auth && $this->Auth->isReal();
        }

        protected function deinitializeNative(){
            parent::deinitializeNative();
            $this->View->page( 'auth', $this->Auth );
            $this->View->layout( 'admin' );
        }
    }
