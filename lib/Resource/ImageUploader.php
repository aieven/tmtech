<?php
    namespace Cerceau\Resource;

    class ImageUploader extends \Cerceau\Utilities\LocalFileUploader {

        protected $resampleData = array(
            Types::PHOTO => array( 1024, 1024, false, ),
            Types::PHOTO_PREVIEW => array( 300, 300, true, ), // true - square
        );

        public function setResampleData( $data ){
            $this->resampleData = $data;
        }

        public function getName(){
            $File = new \SplFileInfo( $this->uploadedFile );
            return preg_replace( '#^php#', '', $File->getBasename( '.jpg' ));
        }



        /**
         * @param array $data
         * @throws \Cerceau\Exception\FieldValidation
         * @throws \Cerceau\Exception\WebDav
         * @throws \UnexpectedValueException
         */

        public function upload( array $data ){
            if(!isset( $data['resource_id'] ))
                throw new \UnexpectedValueException( 'No resource id' );

            if(!isset( $data['name'] ))
                throw new \UnexpectedValueException( 'No resource filename' );

            if( PLATFORM !== 'test' && !is_uploaded_file( $this->uploadedFile )) // todo
                throw new \Cerceau\Exception\FieldValidation('uploadError');

            $ImageConverter = new ImageConverter( $this->uploadedFile );

            $files = array();
            foreach( $this->resampleData as $type => $size ){
                $path = $this->Url->path( array( 'type' => $type ) + $data );
                $files[$path] = $ImageConverter->resample( $size[0], $size[1], $size[2] );
            }

            $Webdav = new Webdav();
            $Manager = Manager::instance();
            $backend = $Manager->getSpotBackendMaster( $Manager->getSpotId( $data['resource_id'] ));
            if(!$Webdav->putFiles( $backend, $files ))
                throw new \Cerceau\Exception\WebDav();
        }
    }
