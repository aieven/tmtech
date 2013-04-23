<?php
    namespace Cerceau\Resource;

    class ImageConverter {

        protected $filename;

        public function __construct( $filename ){
            $this->filename = $filename;
        }

        /**
         * @param int $width
         * @param int $height
         * @param bool $crop
         * @return string
         */
        public function resample( $width, $height, $crop = false ){
            $Image = new \Imagick( $this->filename );
            $sourceSize = $Image->getImageLength();
            if( $crop ){
                $Image->cropThumbnailImage( $width, $height );
            }
            else{
                $Image->thumbnailImage( $width, $height, true );
            }
            $Image->setImageFormat( 'jpeg' );
            $Image->setCompressionQuality( 90 );
            $result = $Image->getImageBlob();
            $finalSize = $Image->getImageLength();
            $Image->destroy();
            if(!$crop ){
                if( $sourceSize < $finalSize )
                    return file_get_contents( $this->filename );
            }
            return $result;
        }

        public function cropImage( $width ,$height, $y , $newPath ){
            $Image = new \Imagick( $this->filename );

            $Image->cropImage( $width, $height, 0, $y );

            $Image->writeimage( $newPath );
        }
    }