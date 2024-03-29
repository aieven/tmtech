<?php
/**
 *  Optional include file for SimpleTest
 *  @package    SimpleTest
 *  @subpackage UnitTester
 *  @version    $Id: default_reporter.php 2011 2011-04-29 08:22:48Z pp11 $
 */

/**#@+
 *  include other SimpleTest class files
 */
require_once(dirname(__FILE__) . '/simpletest.php');
require_once(dirname(__FILE__) . '/scorer.php');
require_once(dirname(__FILE__) . '/reporter.php');
require_once(dirname(__FILE__) . '/xml.php');
/**#@-*/

/**
 *    Parser for command line arguments. Extracts
 *    the a specific test to run and engages XML
 *    reporting when necessary.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class SimpleCommandLineParser {
    private $to_property = array(
            'case' => 'case', 'c' => 'case',
            'test' => 'test', 't' => 'test',
    );
    private $case = '';
    private $test = '';
    private $xml = false;
    private $help = false;
    private $no_skips = false;

    /**
     *    Parses raw command line arguments into object properties.
     *    @param string $arguments        Raw commend line arguments.
     */
    function __construct($arguments) {
        if (! is_array($arguments)) {
            return;
        }
        foreach ($arguments as $i => $argument) {
            if (preg_match('/^--?(test|case|t|c)=(.+)$/', $argument, $matches)) {
                $property = $this->to_property[$matches[1]];
                $this->$property = $matches[2];
            } elseif (preg_match('/^--?(test|case|t|c)$/', $argument, $matches)) {
                $property = $this->to_property[$matches[1]];
                if (isset($arguments[$i + 1])) {
                    $this->$property = $arguments[$i + 1];
                }
            } elseif (preg_match('/^--?(xml|x)$/', $argument)) {
                $this->xml = true;
            } elseif (preg_match('/^--?(no-skip|no-skips|s)$/', $argument)) {
                $this->no_skips = true;
            } elseif (preg_match('/^--?(help|h)$/', $argument)) {
                $this->help = true;
            }
        }
    }

    /**
     *    Run only this test.
     *    @return string        Test name to run.
     */
    function getTest() {
        return $this->test;
    }

    /**
     *    Run only this test suite.
     *    @return string        Test class name to run.
     */
    function getTestCase() {
        return $this->case;
    }

    /**
     *    Output should be XML or not.
     *    @return boolean        True if XML desired.
     */
    function isXml() {
        return $this->xml;
    }

    /**
     *    Output should suppress skip messages.
     *    @return boolean        True for no skips.
     */
    function noSkips() {
        return $this->no_skips;
    }

    /**
     *    Output should be a help message. Disabled during XML mode.
     *    @return boolean        True if help message desired.
     */
    function help() {
        return $this->help && ! $this->xml;
    }

    /**
     *    Returns plain-text help message for command line runner.
     *    @return string         String help message
     */
    function getHelpText() {
        return <<<HELP
SimpleTest command line default reporter (autorun)
Usage: php <test_file> [args...]

    -c <class>      Run only the test-case <class>
    -t <method>     Run only the test method <method>
    -s              Suppress skip messages
    -x              Return test results in XML
    -h              Display this help message

HELP;
    }

}

/**
 *    The default reporter used by SimpleTest's autorun
 *    feature. The actual reporters used are dependency
 *    injected and can be overridden.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class DefaultReporter extends SimpleReporterDecorator {

    /**
     *  Assembles the appropriate reporter for the environment.
     */
    function __construct() {
        if (SimpleReporter::inCli()) {
            $parser = new SimpleCommandLineParser($_SERVER['argv']);
            $interfaces = $parser->isXml() ? array('XmlReporter') : array('TextReporter');
            if ($parser->help()) {
                // I'm not sure if we should do the echo'ing here -- ezyang
                echo $parser->getHelpText();
                exit(1);
            }
            $reporter = new SelectiveReporter(
                    SimpleTest::preferred($interfaces),
                    $parser->getTestCase(),
                    $parser->getTest());
            if ($parser->noSkips()) {
                $reporter = new NoSkipsReporter($reporter);
            }
        } else {
            $reporter = new SelectiveReporter(
                SimpleTest::preferred('HtmlReporter'),
                empty( $_GET['c'] ) ? null : $_GET['c'],
                empty( $_GET['t'] ) ? null : $_GET['t']
            );
            if(
                (!empty( $_GET['skips'] ) && $_GET['skips'] == 'no' ) ||
                (!empty( $_GET['show-skips'] ) && $_GET['show-skips'] == 'no')
            ){
                $reporter = new NoSkipsReporter($reporter);
            }
        }
        parent::__construct($reporter);
    }
}
?>