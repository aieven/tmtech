<?php

    namespace Cerceau\Utilities;

    use Cerceau\Data\Image\Types as ImageTypes;

    class ImageFileUploader extends LocalFileUploader {

        protected $resampleData = array();

        public function resampleTo( $data ){
            if( is_array( $data ) || $data instanceof \ArrayAccess )
                $this->resampleData = $data;
        }

        /**
         * @param \ArrayAccess $data
         * @throws \Cerceau\Exception\FieldValidation
         */
        public function upload( \ArrayAccess $data ){
            $path = $this->Url->path( $data );
            Debug::instance()->dump($data);
            // create directory for uploaded file
            Directory::instance()->createRecursive( preg_replace( '#/[^/]+$#', '', $path ));

            try {
                $Image = new \Imagick( $this->uploadedFile );
                $Image->thumbnailImage(
                    isset( $this->resampleData['width'] ) ? $this->resampleData['width'] : 0,
                    isset( $this->resampleData['height'] ) ? $this->resampleData['height'] : 0
                );
                $Image->writeImage( $path );
            }
            catch( \Exception $E ){
                throw new \Cerceau\Exception\FieldValidation( 'uploadError' );
            }
        }
    }
