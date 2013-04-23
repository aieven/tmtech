<?php
    namespace Cerceau\Script\Cron\Queues;

    class MailSendScript extends \Cerceau\Script\Base {

        public function run(){
            $MailQueue = new \Cerceau\Data\Mail\Queue();
            $Mailer = \Cerceau\Mail\Mailer::get( \Cerceau\Config\Mail::get( 'default' ));
            while( $MailQueue->pull()){
                $View = new \Cerceau\View\Native();
                $View->template( $MailQueue->template());
                $View->set( $MailQueue['data']->export());
                $mail = $View->render( false );

                $Mailer->send( $MailQueue['email'], $MailQueue->subject(), $mail );
            }
        }
    }
