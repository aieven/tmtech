<?php
    namespace Cerceau\Controller\Api;

    use \Cerceau\Exception\Factory as ExceptionFactory;

    abstract class Base extends \Cerceau\Controller\Base {
        protected $viewType = 'Json';

        protected function showError( $code ){
            $this->View->set( 'result', 'error');
            $this->View->set( 'code', $code );
            if(!$code )
                $code = ExceptionFactory::UNKNOWN_ERROR;

            $I18n = \Cerceau\System\Registry::instance()->I18n();
            $message = $I18n->pick( 'errors', $code, 'message' );
            $this->View->set( 'message', $message );
            $this->View->set( 'moreinfo', $I18n->pick( 'errors', $code, 'moreinfo' ));

            \Cerceau\System\Registry::instance()->Logger()->log( 'debug', $message );
        }

        public function run(){
            try {
                $this->Session = \Cerceau\System\Registry::instance()->Session();
                $this->initialize();

                $method = $this->page;
                if(!$this->$method())
                    ExceptionFactory::throwError( ExceptionFactory::NOT_FOUND );

                $this->deinitialize();
                $this->Session->save();
                $this->View->set( 'result', 'ok' );
            }
            catch( \Cerceau\Exception\RowValidation $E ){
                $this->View->set( 'fields_errors', json_decode( $E->getMessage()));
                $this->showError( ExceptionFactory::VALIDATION_ERROR );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                $this->showError( $E->getCode());
            }
            return $this->View->render();
        }
    }