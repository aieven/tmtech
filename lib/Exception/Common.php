<?php

namespace Cerceau\Exception {

    /**
     * Message will be shown to user
     */
    class Client extends \Exception{}

    /**
     * 404 Page not found and others
     * Specific message will be shown to user
     */
    class Page extends \Exception{}

    /**
     * Logging user ip
     */
    class HackAttempt extends \InvalidArgumentException {}

    /**
     * Invalid request
     * Logging as HackAttempt
     */
    class Request extends HackAttempt{}

    /**
     * Unauthorized access attempt
     * Maybe in cause of automatic logout
     * Redirect
     */
    class Authorize extends \Exception{}

    /**
     * Validation error
     * Error message looks like "validationType"
     */
    class FieldValidation extends \Exception{}

    /**
     * Validation error
     * Error message is a json array of validation errors
     * array (
     *     'fieldName' => '{prefix}fieldName.validationType',
     *     ...
     * )
     */
    class RowValidation extends \Exception{}

    /**
     * Error on webdav action
     */
    class WebDav extends \Exception{}

    /**
     * Exception on wrong format
     */
    class WrongFormat extends \Exception{}
}

namespace Cerceau\Exception\Database {

    /**
     * Error in query logic
     * Wrong arguments, error in template, etc.
     */
    class QueryLogicError extends \ErrorException {}

    /**
     * Connection error
     */
    class Connection extends \Exception {}

    /**
     * Error in SQL query
     */
    class SQLQuery extends \Exception {}

    /**
     * Slow query signal
     */
    class SlowQuery extends \Exception {}
}
