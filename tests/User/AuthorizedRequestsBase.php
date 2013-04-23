<?php
    namespace Cerceau\Test\User;

    abstract class AuthorizedRequestsBase extends Base {

        /**
         * @var \Cerceau\Data\User\IAuth
         */
        protected $Auth;

        /**
         * @var \Cerceau\I\ISession $Session
         */
        protected $Session;

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        public function __construct(){
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function setUp(){
            parent::setUp();

            \Cerceau\Test\Utilities\Entities::createBot( array(
                'instagram_id' => 143654695,
                'instagram_token' => '143654695.ec3bf6e.d5f2b27c0ee84020993f015f6a356e48',
            ));

            $this->Auth = \Cerceau\Test\Utilities\Entities::createAuth( array(
                'email' => 'authorized@test.mail',
            ));
            $this->Session = \Cerceau\System\Registry::instance()->Session();
            $this->Session['user'] = $this->Auth->export();
        }
    }
