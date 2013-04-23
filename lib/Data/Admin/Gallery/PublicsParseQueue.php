<?php
    namespace Cerceau\Data\Admin\Gallery;

    class PublicsParseQueue extends \Cerceau\Data\Base\QueueRow {
        protected static $queueName = 'gpp'; // Gallery publics parse

        protected static $fieldsOptions = array(
            'public_id' => array(
                'Int',
            ),
            'username' => array(
                'Scalar',
            ),
        );
    }
