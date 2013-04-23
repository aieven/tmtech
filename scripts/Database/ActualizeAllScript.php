<?php
    namespace Cerceau\Script\Database;

    /**
     * Only for dev platform and dev purposes
     */
    class ActualizeAllScript extends \Cerceau\Script\Base {

        public function run(){
            // main
            $ActualizeMain = new ActualizeMainScript();
            $ActualizeMain->run();

        }
    }
