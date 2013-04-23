<?php
    namespace Cerceau\Script\Cron\Queues;

    abstract class QueueBase extends \Cerceau\Script\Base {

        protected static $queueName;
        protected static $pullLength = 1;

        private $master;
        private $masterPid;

        /**
         * @var \Cerceau\Data\Base\QueueRow $Queue
         */
        protected $Queue;

        public function __construct(){
            $className = '\\Cerceau\\Data\\'. static::$queueName;
            $this->Queue = new $className();
            $this->initialize();
        }

        protected function initialize(){}

        /**
         * @abstract
         * @param array $item
         */
        abstract protected function runQueue( array $item );

        final public function run( $once = false ){
            try {
                $Lock = new \Cerceau\Model\Redis\Lock( static::$queueName );
                $this->masterPid = \Cerceau\System\Registry::instance()->Date()->timestamp();
                $this->master = $Lock->set( $this->masterPid );

                if(!$this->master ){
                    if(!$this->Queue->len())
                        return;
                }
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return;
            }
            if( $this->master && extension_loaded( 'xhprof' )){
                include_once '/usr/local/www/munin/xhprof/xhprof_lib/utils/xhprof_lib.php';
                include_once '/usr/local/www/munin/xhprof/xhprof_lib/utils/xhprof_runs.php';
                \xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
            }
            while( 1 ){
                if(!$this->cycle( $Lock ) || $once )
                    break;

                sleep(1);
            }
            if( $this->master && extension_loaded( 'xhprof' )){
                $XhprofRuns = new \XHProfRuns_Default();
                $XhprofRuns->save_run( \xhprof_disable(), 'classygram.com' );
            }
        }

        final public function cycle( \Cerceau\Model\Redis\Lock $Lock ){
            try {
                while( $this->Queue->pull( static::$pullLength ))
                    $this->runQueue( $this->Queue->export());

                if(!$this->master || $this->masterPid != $Lock->get())
                    return false;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return true;
        }
    }
