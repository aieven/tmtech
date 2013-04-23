<?php
    namespace Cerceau\Test\User;

    class AuthTestCase extends Base {

        /**
         * @var array
         */
        protected $userData = array(
            'email' => 'qwerty@mail.ru',
            'password' => '123456',
        );

        public function testAdmin(){
            $Auth = new \Cerceau\Data\User\Auth();
            $this->assertFalse( $Auth->load( $this->userData ));
            $this->assertIdentical( null, $Auth->getId());
            $this->assertIdentical( false, $Auth->isReal());

            $Auth->fetch( $this->userData );
            $this->assertTrue( $Auth->create());
            $this->assertIdentical( true, $Auth->isReal());

            // check main db record
            $auth = $this->Database->selectRecord( <<<SQL
    -- SQL_TEST_USER_CREATE
    SELECT * FROM {{ t("admins") }}
SQL
            );
            $auth = reset( $auth );
            $this->assertTrue( is_array( $auth ));

            // double creation for one email must fail
            $Auth2 = new \Cerceau\Data\User\Auth();
            $Auth2->fetch( $this->userData );
            $this->assertFalse( $Auth2->create());

            // load created user
            $Auth3 = new \Cerceau\Data\User\Auth();
            $this->assertTrue( $Auth3->load( $this->userData ));
            $this->assertEqual( $Auth->getId(), $Auth3->getId());
        }
    }