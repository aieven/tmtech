<?php
    namespace Cerceau\Data\Base;

	class TestSubRow extends \Cerceau\Data\Base\Row {
        protected static $fieldsOptions = array(
            'some_set' => array(
                'Set',
                'types' => array(
                    1 => 'no matter what name 1',
                    2 => 'no matter what name 2',
                    3 => 'no matter what name 3',
                ),
            ),
            'field_array_decimal' => array(
                'FieldArray',
                'fieldsOptions' => array(
                    'Decimal',
                    'precision' => 3,
                ),
            ),
            'field_array_validation' => array(
                'FieldArray',
                'fieldsOptions' => array(
                    'String',
                    'validation' => array(
                        'match' => '^\w+$',
                    ),
                ),
                'validation' => array(
                    'fields',
                    'keys' => array(
                        'first',
                        'second',
                    ),
                ),
            ),
            'row_array_1' => array(
                'RowArray',
                'class' => 'Base\\TestSubRow1'
            ),
            'row_array_2' => array(
                'RowArray',
                'classes' => array(
                    'row1' => 'Base\\TestSubRow1',
                    'row2' => 'Base\\TestSubRow2',
                )
            ),
        );
	}
