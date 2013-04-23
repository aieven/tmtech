<?php
    namespace Cerceau\Data\Base;

	class TestRow extends \Cerceau\Data\Base\DbRow {
        protected static $modelName = 'MySQL\\Base';
        protected static $table = 'test';
        protected static $fieldsOptions = array(
            'id' => array(
                'Int',
                'load',
                'autoIncrement',
            ),
            'name' => array(
                'String',
                'validation' => array( 'notEmpty' ),
            ),
            'other_row' => array(
                'Row',
                'class' => 'Base\\TestSubRow',
            ),
            'some_filtered' => array(
                'String',
            ),
            'some_prefetched' => array(
                'String',
            ),
            'some_default' => array(
                'String',
                'default' => 'DEFAULT',
            ),
            'some_readonly' => array(
                'String',
                'readonly',
            ),
            'some_decimal' => array(
                'Decimal',
                'precision' => 2
            ),
            'some_enum' => array(
                'Enum',
                'types' => array(
                    1 => 'test_1',
                    2 => 'test_2',
                ),
            ),
            'some_datetime' => array(
                'DateTime',
            ),
            'some_serialized' => array(
                'Serialized',
            ),
            'some_serialized_array' => array(
                'SerializedArray',
            ),
        );

        protected function filterSomeFiltered( $value ){
            return preg_replace( '/^\d+/', '', $value );
        }

        protected function preFetch( $a ){
            if(is_array( $a )){
                if(!array_key_exists('some_prefetched', $a ) &&
                    array_key_exists('some_key', $a ) &&
                    array_key_exists('some_value', $a )
                )
                    $a['some_prefetched'] = trim( $a['some_key'] .': '. $a['some_value'] );
            }
            return $a;
        }
	}
