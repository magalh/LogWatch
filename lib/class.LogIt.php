<?php
class LogIt
{

    public function __construct (){
        define('ERROR', 256);   // Before E_USER_ERROR
        define('INFO',  512);   // Before E_USER_WARNING
        define('DEBUG', 1024);  // Before E_USER_NOTICE
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
		//set_error_handler( array( $this, 'errorHandler' ) );
        register_shutdown_function(array( $this, 'logFatalError'));
	}

    public function errorHandler ($errType, $errStr, $errFile, $errLine) {

        $displayErrors = ini_get( 'display_errors' );
        $logErrors     = ini_get( 'log_errors' );
        $errorLog      = ini_get( 'error_log' );
    
        if( $displayErrors ) echo $errStr.PHP_EOL;

        if( $logErrors ) {
            $line = "";
            switch ($errType) {
                case DEBUG: 
                    $type = 'DEBUG'; 
                    $line = $errLine;
                    break;
                case INFO: 
                    $type = 'INFO'; 
                    $line = $errLine;
                    break;
                case ERROR: 
                    $type = 'ERROR'; 
                    $file = $errFile;
                    $line = $errLine;
                    break;
                default: 
                    $type = 'WARN'; 
                    $line = $errLine;
                    $file = $errFile;
                    break;
            }

            //$message = date('Y-m-d H:i:s')."|".$type."|".$errStr."|".$file."|".$line;
            //file_put_contents($errorLog, $message.PHP_EOL, FILE_APPEND);

            $mod = cms_utils::get_module('LogWatch');

            $logitem = new LogItem();
            $logitem->name = $mod->GetName();
            $logitem->type = $type;
            $logitem->file = $file;
            $logitem->line = $line;
            $logitem->description = $errStr;
            $logitem->save();

        }
    }

    public function logFatalError() {
        $error = error_get_last();
        if ($error !== null && $error['type'] === E_ERROR) {
            $errorLog = "Fatal Error: [{$error['type']}] {$error['message']} in {$error['file']} on line {$error['line']}";

            $mod = cms_utils::get_module('LogWatch');

            $logitem = new LogItem();
            $logitem->name = $mod->GetName();
            $logitem->type = $error['type'];
            $logitem->file = $error['file'];
            $logitem->line = $error['line'];
            $logitem->description = $error['message'];
            $logitem->save();

            //error_log($errorLog, 3, '/path/to/error.log');  // Replace with the actual path to your error log file
        }
    }

}
?>