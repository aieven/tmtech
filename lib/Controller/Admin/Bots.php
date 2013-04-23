<?php
    namespace Cerceau\Controller\Admin;

    class Bots extends Base {
        protected static $routes = array(
            'get' => array( // method
                'bots' => array(
                    'bots',
                    'admin/bots/<\d*>',
                    'params' => array(
                        'type'
                    ),
                ),
                'authorizeBot' => array(
                    'authorizeBot',
                    'admin/bots/auth/',
                    'view' => 'Redirect',
                ),
            ),
            'post' => array(
                'editBot' => array(
                    'editBot',
                    'admin/bot/edit/<\d*>',
                    'params' => array(
                        'instagram_id'
                    ),
                    'view' => 'Json',
                ),
            ),
        );

        protected function authorization(){
            return parent::authorization() && $this->Auth->can( \Cerceau\Data\User\Privileges::WATCH_BOTS );
        }

        protected function pageBots(){
            if(( $this->queryParam('type') == 2 ) && (!$this->Auth->can( \Cerceau\Data\User\Privileges::WATCH_BOTS )))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );
            $Catalog = new \Cerceau\Data\User\CatalogBot();
            $bots = $Catalog->selectBots( $this->queryParam());
            // prepare view
            $this->View->set( 'bots', $bots );
            $this->View->template( 'admin/bots' );
            return true;
        }

        protected function pageAuthorizeBot(){
            if(!$this->Auth->can( \Cerceau\Data\User\Privileges::ADD_BOTS ))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );

            $this->Session['state'] = sha1(uniqid());
            $this->Session['auth_action'] = 'create_bot';

            $this->View->set( 'location', \Cerceau\Data\User\InstagramUser::DIALOG_LOCATION );
            $this->View->set( 'client_id', \Cerceau\Config\Constants::INSTAGRAM_BOT_CLIENT_ID );
            $this->View->set( 'redirect_uri', \Cerceau\Config\Constants::INSTAGRAM_BOT_REDIRECT_URL );
            $this->View->set( 'response_type', 'code' );
            $this->View->set( 'scope', \Cerceau\Config\Constants::INSTAGRAM_SCOPE );
            $this->View->set( 'state', $this->Session['state'] );
            return true;
        }

        protected function pageEditBot(){
            if(!$this->Auth->can( \Cerceau\Data\User\Privileges::EDIT_BOTS ))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );
            $Bot = new \Cerceau\Data\User\Bot();
            if(!$Bot->load( $this->queryParam()))
                throw new \Cerceau\Exception\Page( 'Not Found', 404 );

            $Bot->merge( \Cerceau\System\Registry::instance()->Request()->post());
            $Bot->update();
            // prepare view
            $bot = $Bot->export();
            $bot['types'] = implode( ',', $bot['types'] );
            $this->View->set( 'bot', $bot);
            $this->View->set( 'done' , 1 );
            return true;
        }
    }
