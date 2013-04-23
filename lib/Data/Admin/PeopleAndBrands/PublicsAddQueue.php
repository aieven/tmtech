<?php
    namespace Cerceau\Data\Admin\PeopleAndBrands;

    class PublicsAddQueue extends \Cerceau\Data\Base\QueueRow {
        protected static $queueName = 'pa'; // Publics Add

        protected static $fieldsOptions = array(
            'partition' => array(
                'Scalar',
            ),
            'public_id' => array(
                'Int',
            ),
            'username' => array(
                'Scalar',
            ),
        );
    }
