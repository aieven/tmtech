<?php

    namespace Cerceau\Utilities;

    use Cerceau\Data\Image\Types as ImageTypes;

    class SimpleImageUploader extends LocalFileUploader {

        /**
         * @param \ArrayAccess $data
         * @throws \Cerceau\Exception\FieldValidation
         */
        public function upload( \ArrayAccess $data ){
            $path = $this->Url->path( $data );
            // create directory for uploaded file
            Directory::instance()->createRecursive( preg_replace( '#/[^/]+$#', '', $path ));

            try {
                $Image = new \Imagick( $this->uploadedFile );
                $Image->writeImage( $path );
            }
            catch( \Exception $E ){
                throw new \Cerceau\Exception\Client( 'forms.image.upload.uploadError' );
            }
        }
    }
