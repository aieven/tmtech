<?php
    namespace Cerceau\Data\Admin\PeopleAndBrands;

    class Category extends \Cerceau\Data\Base\DbRow {

        protected static $fieldsOptions = array(
            'partition' => array(
                'Scalar',
            ),
            'subcat_id' => array(
                'Int',
            ),
            'cat_id' => array(
                'Int',
            ),
            'subcat_name' => array(
                'Scalar',
            ),
            'subcat_icon' => array(
                'Scalar',
            ),
        );

        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Category
         */
        protected $Model;

        public function initialize(){
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Category( 'main' );
        }

        public function selectSubcatInfo(){
            $info = $this->Model->selectSubcatInfo( $this->export());
            $info[0]['name'] = $info[0]['subcat_name'];

            return $info[0];
        }

        public function selectCatInfo(){
            $info = $this->Model->selectCatInfo( $this->export());
            $info[0]['name'] = $info[0]['cat_name'];
            return $info[0];
        }

        public function addSubcat(){
            return $this->Model->addSubcat( $this->export());
        }

        public function editSubcat(){
            return $this->Model->updateSubcat( $this->export());
        }

        public function deleteSubcat(){
            return $this->Model->deleteSubcat( $this->export());
        }
    }
