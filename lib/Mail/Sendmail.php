<?php
	namespace Cerceau\Mail;

    require_once EXTERNAL_DIR . 'sendmail/class.phpmailer.php';

    class Sendmail extends Mailer {
        /**
         * @var \PHPMailer $Mailer
         */
        private $Mailer = null;
        
        /**
         * @var string|null
         */
        private $lastError = null;

        public function send( $to, $subject, $content ){
            if(!$this->Mailer){
                $this->Mailer	= new \PHPMailer(true);
                $this->Mailer->IsSendmail();
                $this->Mailer->Hostname  = $this->Config->site;
                $this->Mailer->PluginDir = EXTERNAL_DIR .'sendmail/';
                $this->Mailer->CharSet   = 'KOI8-R';
                $this->Mailer->From      = $this->Config->sender;
                $this->Mailer->Sender    = $this->Config->sender;
                $this->Mailer->SetFrom( $this->Config->sender, iconv( 'UTF-8', 'KOI8-R', $this->Config->from ));
                $this->Mailer->AddReplyTo( $this->Config->sender, iconv( 'UTF-8', 'KOI8-R', $this->Config->from ));
            }

            $this->Mailer->ContentType   = $this->Config->content_type;
            $this->Mailer->Subject       = iconv( 'UTF-8', 'KOI8-R', $subject );
            $this->Mailer->MsgHTML( iconv( 'UTF-8', 'KOI8-R', $content ));
            $this->Mailer->AddAddress( $to, $to );
//            $this->Mailer->AddStringAttachment($row["photo"], "YourPhoto.jpg");

            try{
                $this->lastError = null;
                $result = $this->Mailer->Send();
            }
            catch(\phpmailerException $e){
                $this->lastError = $e->errorMessage();
                $result = false;
            }
            catch(\Exception $e){
                $this->lastError = $e->getMessage();
                $result = false;
            }

            $this->Mailer->ClearAddresses();
//            $this->Mailer->ClearAttachments();
            return $result;
        }

        public function lastError(){
            return $this->lastError;
        }
    }
	