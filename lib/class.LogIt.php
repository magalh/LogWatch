<?php
class LogIt
{

    public function __construct(){
        define('ERROR', 256);       // Before E_USER_ERROR
        define('INFO',  512);       // Before E_USER_WARNING
        define('DEBUG', 1024);      // Before E_USER_NOTICE
        define('NOTICE', 2048);     // Custom Notice level
        define('WARNING', 4096);    // Custom Warning level
        define('CRITICAL', 8192);   // Custom Critical level
    
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        set_error_handler(array($this, 'errorHandler'), E_ALL);
    }
    

    public function errorHandler ($errType, $errStr, $errFile, $errLine, $errContext = null) {

        $displayErrors = ini_get( 'display_errors' );
        $logErrors     = ini_get( 'log_errors' );
        $errorLog      = ini_get( 'error_log' );

        if( $displayErrors ) echo $errStr.PHP_EOL;

        if( $logErrors ) {
            $line = $errLine;
            switch ($errType) {
                case DEBUG:
                    $type = 'DEBUG';
                    break;
                case INFO:
                    $type = 'INFO';
                    break;
                case NOTICE:
                    $type = 'NOTICE';
                    break;
                case WARNING:
                    $type = 'WARNING';
                    break;
                case CRITICAL:
                    $type = 'CRITICAL';
                    break;
                case ERROR:
                    $type = 'ERROR';
                    break;
                default:
                    $type = 'WARN';
                    break;
            }

            //echo $message = date('Y-m-d H:i:s')."|".$type."|".$errStr."|".$file."|".$line;
            //file_put_contents($errorLog, $message.PHP_EOL, FILE_APPEND);

            $mod = cms_utils::get_module('LogWatch');

            $logitem = new LogItem();
            $logitem->name = $mod->GetName();
            $logitem->type = $type;
            $logitem->file = $errFile;
            $logitem->line = $errLine;
            $logitem->description = $errStr;
            $logitem->save();

        }
    }

    public function triggerPhpErrors($errorType)
    {
        switch ($errorType) {
            case 1:
                // Trigger PHP notice
                $uninitialized_variable = $undefinedVariable; // This will trigger a notice: "Undefined variable"
                break;
            case 2:
                // Trigger PHP Fatal error
                $obj = new ReadOnlyProperty();
                $obj->readOnly = 'attempt to modify'; // This will trigger a warning: "Attempt to modify a read-only property"
                break;
            case 3:
                // Trigger PHP error
                $result = 1 / 0; // This will trigger an error: "Division by zero"
                break;
            case 4:
                // Trigger PHP fatal error
                $nonExistentFunction(); // This will trigger a fatal error: "Call to undefined function"
                break;
            case 5:
                // Trigger PHP deprecated warning
                $filename = '/path/to/file.txt';
                $file = new File($filename); // This will trigger a deprecated warning if the File class is deprecated
                break;
            case 6:
                // Trigger PHP user error
                trigger_error('This is a user error', E_USER_ERROR);
                break;
            case 7:
                // Trigger PHP user warning
                trigger_error('This is a user warning', E_USER_WARNING);
                break;
            case 8:
                // Trigger PHP user notice
                trigger_error('This is a user notice', E_USER_NOTICE);
                break;
            default:
                echo 'Invalid error type specified.';
                break;
        }
    }

}
?>