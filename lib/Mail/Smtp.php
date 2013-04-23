<?php
    namespace Cerceau\Mail;

    require_once EXTERNAL_DIR . 'sendmail/class.phpmailer.php';

    class Smtp extends Mailer {
        private $keepAlive	= false;

        /**
         * @var \PHPMailer $Mailer
         */
        private $Mailer = null;

        /**
         * @var string|null
         */
        private $lastError = null;

        public function send( $to, $subject, $content ){
            if(!$this->Mailer ){
                $this->Mailer = new \PHPMailer(true);
                $this->Mailer->IsSMTP(); // telling the class to use SMTP
                $this->Mailer->SMTPDebug     = 1;
                $this->Mailer->SMTPAuth      = true; // enable SMTP authentication
                $this->Mailer->SMTPSecure    = 'ssl'; // sets the prefix to the servier
                $this->Mailer->SMTPKeepAlive = $this->keepAlive;	// SMTP connection will not close after each email sent
                $this->Mailer->Host			 = $this->Config->host;			// sets the SMTP server
                $this->Mailer->Port			 = $this->Config->port;			// set the SMTP port for the GMAIL server
                $this->Mailer->Username		 = $this->Config->user;			// SMTP account username
                $this->Mailer->Password		 = $this->Config->pwd;			// SMTP account password
                $this->Mailer->Hostname  = $this->Config->site;
                $this->Mailer->PluginDir = EXTERNAL_DIR .'sendmail/';
                $this->Mailer->CharSet   = 'UTF-8';
                $this->Mailer->From      = $this->Config->sender;
                $this->Mailer->Sender    = $this->Config->sender;
                $this->Mailer->SetFrom( $this->Config->sender, $this->Config->from );
                $this->Mailer->AddReplyTo( $this->Config->sender, $this->Config->from );
            }

            $this->Mailer->ContentType = $this->Config->content_type;
            $this->Mailer->Subject = $subject;
            $this->Mailer->MsgHTML( $content );
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