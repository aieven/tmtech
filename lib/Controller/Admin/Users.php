<?php
    namespace Cerceau\Controller\Admin;

    class Users extends Base {
        protected static $routes = array(
            'get' => array( // method
                'users' => array(
                    'users',
                    'admin/users/',
                ),
                'userPrivileges' => array(
                    'userPrivileges',
                    'admin/users/<\d+>/privileges',
                    'params' => array(
                        'admin_id'
                    ),
                    'view' => 'Json',
                ),
            ),
            'post' => array( // method
                'addUser' => array(
                    'addUser',
                    'admin/users/',
                    'view' => 'Json',
                ),
                'editUserPrivileges' => array(
                    'editUserPrivileges',
                    'admin/users/<\d+>/privileges',
                    'params' => array(
                        'admin_id'
                    ),
                    'view' => 'Json',
                ),
            ),
        );

        protected function authorization(){
            return parent::authorization() && $this->Auth->can( \Cerceau\Data\User\Privileges::WATCH_USERS );
        }

        protected function pageUsers(){
            $UsersCatalog = new \Cerceau\Data\User\Catalog();
            $this->View->set( 'users', $UsersCatalog->selectAll());
            $this->View->template('admin/users');
            $this->View->globals( 'controller', 'Admin.Users' );
            return true;
        }

        protected function pageAddUser(){
            if(!$this->Auth->can( \Cerceau\Data\User\Privileges::ADD_USERS ))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );

            $User = new \Cerceau\Data\User\Auth();
            $User->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $User['privileges'] = array(
                \Cerceau\Data\User\Privileges::MODERATION,
            );
            $password = $User->generatePassword();
            if(!$User->create())
                return false;

            $MailQueue = \Cerceau\Data\Mail\Queue::instance( \Cerceau\Data\Mail\Types::NEW_USER );
            $MailQueue['email'] = $User['email'];
            $MailQueue['data'] = array(
                'email' => $User['email'],
                'password' => $password,
            );
            if(!$MailQueue->push())
                \Cerceau\System\Registry::instance()->Logger()->log( 'db-fail-push', 'Couldn\'t push mail' );

            $this->View->set( 'admin_id', $User->getId());
            $this->View->set( 'email', $User['email'] );
            return true;
        }

        protected function pageUserPrivileges(){
            if(!$this->Auth->can( \Cerceau\Data\User\Privileges::EDIT_USERS ))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );

            $User = new \Cerceau\Data\User\Auth();
            if(!$User->load( $this->queryParam()))
                throw new \Cerceau\Exception\Page( 'Not Found', 404 );

            $user = $User->export();
            unset( $user['password'] );
            $this->View->set( 'user', $user );
            return true;
        }

        protected function pageEditUserPrivileges(){
            if(!$this->Auth->can( \Cerceau\Data\User\Privileges::EDIT_USERS ))
                throw new \Cerceau\Exception\Page( 'Unauthorized', 401 );

            $post = \Cerceau\System\Registry::instance()->Request()->post();
            if( empty( $post['privileges'] ))
                throw new \Cerceau\Exception\Page( 'Not Found', 404 );

            $User = new \Cerceau\Data\User\Auth();
            if(!$User->load( $this->queryParam()))
                throw new \Cerceau\Exception\Page( 'Not Found', 404 );

            $User['privileges'] = $post['privileges'];
            if(!$User->update())
                return false;

            $this->View->set( 'done', 1 );
            return true;
        }
    }