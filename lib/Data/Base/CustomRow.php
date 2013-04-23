<?php

namespace Cerceau\Data\Base;

/**
 * Class for rows with custom runtime creating fields
 */
class CustomRow extends Row {

    public function __construct( Storage $Storage, array $fieldsOptions ){
        $this->Storage = $Storage;
        $this->initialize();

        foreach( $fieldsOptions as $offset => $options ){
            $className = array_shift( $options );
            $this->fields[$offset] = \Cerceau\Data\Base\Field::create( $className, $this->Storage, $offset, $options );
        }
        $this->postCreate();
    }
}
