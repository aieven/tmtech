<?php
namespace Cerceau\Script;


class CreateRequestsFilesScript extends \Cerceau\Script\Cron\ParseBase {

    public function run(){
        if( !$this->Bot )
            return;
        $log = file_get_contents( LOGS_DIR .'/test/no-such-files.log' );
        preg_match_all( '/(.+):(.+)\n\n/Usi' , $log , $noSuchFiles , PREG_SET_ORDER );
        foreach( $noSuchFiles as $file ){
            if( file_exists( TESTS_DIR .'api/'. $file[1] ) )
                continue;
            $file[2] = json_decode( $file[2] , true );
            $file[2]['get']['access_token'] = $this->Bot['instagram_token'];
            $request = \Cerceau\System\Registry::instance()->Curl()->apiQuery( $file[2]['apiLocation'] , $file[2]['uri'] , $file[2]['get'], $file[2]['post'] );
            $data = $request['headers'] ."\r\n\r\n". json_encode( $request['body'] );
            $fh = fopen( TESTS_DIR .'api/'. $file[1] , "w" );
            if( !fwrite($fh, $data) )
                \Cerceau\Utilities\Debug::instance()->dump( 'error file write: '. $file[1] );
            fclose($fh);
        }
        unlink( LOGS_DIR .'/test/no-such-files.log' );
    }
}
