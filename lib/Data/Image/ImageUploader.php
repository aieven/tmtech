<?php
    namespace Cerceau\Data\Image;

    abstract class ImageUploader extends \Cerceau\Data\Base\Row {

        protected static $imageField = 'img';
        protected static $iconDir;
        protected static $widthSize;
        protected static $heightSize;
        protected static $validationPrefix = 'forms.image.upload.';

        final public static function getIconDir(){
            return static::$iconDir;
        }

        public function isNotEmpty(){
            return isset( $_FILES[static::$imageField] );
        }

        public function upload(){
            try {
                $FileUploader = new \Cerceau\Utilities\SimpleImageUploader( \Cerceau\Utilities\ImageUrlBuilder::instance());
                $type = $FileUploader->fetch( static::$imageField );
                $this['type'] = \Cerceau\Data\Image\Types::getIdByMimetype( $type );
                if(!$this['type'] )
                    throw new \Cerceau\Exception\Client( static::$validationPrefix.'unexpectedType' );

                $sizes = getimagesize( $FileUploader->getTempPath());
                if( $sizes[0] != static::$widthSize || $sizes[1] != static::$heightSize  )
                    throw new \Cerceau\Exception\Client( static::$validationPrefix.'wrongDimensions' );

                $Sequence = new \Cerceau\Model\PostgreSQL\Sequence( 'images', 'main' );

                $this['name'] = $Sequence->allocateId();
                if(!$this['name'] || !$this['type'])
                    throw new \Cerceau\Exception\Client( static::$validationPrefix.'uploadError' );

                $this['image_path'] = static::$iconDir . $this['name'] . '.' . \Cerceau\Data\Image\Types::getExtension( $this['type'] );

                $FileUploader->upload( $this );
                return true;
            }
            catch( \Cerceau\Exception\FieldValidation $E ){
                throw new \Cerceau\Exception\Client( static::$validationPrefix. $E->getMessage());
            }
        }
    }