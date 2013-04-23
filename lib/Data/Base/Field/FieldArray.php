<?php
    namespace Cerceau\Data\Base\Field;

	class FieldArray extends FieldSerializedArray {
        /**
         * @return string
         */
        public function toScalar(){
            return $this->get();
        }
    }
