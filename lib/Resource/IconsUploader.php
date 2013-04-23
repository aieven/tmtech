<?php
    namespace Cerceau\Resource;

    class IconsUploader extends \Cerceau\Resource\ImageUploader {

        public function getType(){
            switch ( $this->fetch( 'subcat_image' )){
                case 'image/jpeg':
                    $type = 1;
                    break;
                case 'image/png':
                    $type = 2;
                    break;
                default:
                    throw new \Cerceau\Exception\FieldValidation( 'forms.image.wrongFormat' );
            }
            return $type;
        }

        /**
         * @param array $data
         * @throws \Cerceau\Exception\FieldValidation
         */

        public function upload( array $data ){
            $size = getimagesize( $this->uploadedFile );

            if( $size[0] != $this->resampleData[1][0] || $size[1] != $this->resampleData[1][0] )
                throw new \Cerceau\Exception\Client( 'forms.image.wrongDimensions' );

            \Cerceau\Utilities\Directory::instance()->createRecursive( $this->Url->getFilesDir());
            try {
                $Image = new \Imagick( $this->uploadedFile );
                $Image->writeImage( $this->Url->path( $data ));

            }
            catch( \Exception $E ){
                throw new \Cerceau\Exception\Client( 'forms.image.uploadError' );
            }

        }
    }
