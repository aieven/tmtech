<?php

    namespace Cerceau\Utilities;

    class LocalFileUploader implements I\IFileUploader {

        /**
         * @var I\IFileUrlBuilder
         */
        protected $Url;

        /**
         * @var string
         */
        protected $uploadedFile;

        /**
         * @param I\IFileUrlBuilder $Url
         */
        public function __construct( I\IFileUrlBuilder $Url ){
            $this->Url = $Url;
        }

        /**
         * @return array
         * @throws \Cerceau\Exception\FieldValidation
         * @throws \InvalidArgumentException
         */
        public function fetch(){
            $args = func_get_args();
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: field name ' );
            $fieldName = array_shift( $args );
            if(!isset( $_FILES[$fieldName] ))
                throw new \Cerceau\Exception\FieldValidation('notEmpty');

            $field = $_FILES[$fieldName];

            $tmp   = $field['tmp_name'];
            $type  = $field['type'];
            $error = $field['error'];
            $size  = $field['size'];

            try {
                while( $key = array_shift( $args )){
                    $tmp   = $tmp[$key];
                    $type  = $type[$key];
                    $error = $error[$key];
                    $size  = $size[$key];
                }
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                throw new \Cerceau\Exception\FieldValidation('uploadError');
            }
            if( $error || !$size )
                throw new \Cerceau\Exception\FieldValidation('uploadError');

            $this->uploadedFile = $tmp;
            return $type;
        }

        /**
         * @return string
         */
        public function getTempPath(){
            return $this->uploadedFile;
        }

        /**
         * @param array $data
         * @throws \Cerceau\Exception\FieldValidation
         */
        public function upload( \ArrayAccess $data ){
            $path = $this->Url->path( $data );

            // create directory for uploaded file
            Directory::instance()->createRecursive( preg_replace( '#/[^/]+$#', '', $path ));

            if(!move_uploaded_file( $this->uploadedFile, $path ))
                throw new \Cerceau\Exception\FieldValidation('uploadError');
        }
    }
