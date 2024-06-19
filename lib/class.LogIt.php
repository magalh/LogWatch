<?php
class LogIt
{

    private $error_levels;
    private $exceptions = [
        'E_ERROR' => E_ERROR,
        'E_WARNING' => E_WARNING,
        'E_PARSE' => E_PARSE,
        'E_NOTICE' => E_NOTICE,
        'E_CORE_ERROR' => E_CORE_ERROR,
        'E_CORE_WARNING' => E_CORE_WARNING,
        'E_COMPILE_ERROR' => E_COMPILE_ERROR,
        'E_COMPILE_WARNING' => E_COMPILE_WARNING,
        'E_USER_ERROR' => E_USER_ERROR,
        'E_USER_WARNING' => E_USER_WARNING,
        'E_USER_NOTICE' => E_USER_NOTICE,
        'E_STRICT' => E_STRICT,
        'E_RECOVERABLE_ERROR' => E_RECOVERABLE_ERROR,
        'E_DEPRECATED' => E_DEPRECATED,
        'E_USER_DEPRECATED' => E_USER_DEPRECATED,
        'E_ALL' => E_ALL
    ];

    public function __construct(){

        $this->set_error_levels();

        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        set_error_handler(array($this, 'errorHandler'), $this->error_levels);
        register_shutdown_function(array($this, 'shutdownHandler'));
    }
    
    private function set_error_levels()
    {
        $mod = cms_utils::get_module('LogWatch');
        $logsettings = $mod->GetPreference('logsettings', 'E_ERROR,E_NOTICE,E_WARNING');
        $selected_logsettings = explode(',', $logsettings);

        $this->error_levels = 0;

        foreach ($selected_logsettings as $setting) {
            if (isset($this->exceptions[$setting])) {
                $this->error_levels |= $this->exceptions[$setting];
            }
        }

        // If no error levels are selected, default to E_ALL
        if ($this->error_levels === 0) {
            $this->error_levels = E_ALL;
        }
    }

    public function errorHandler ($errType, $errStr, $errFile, $errLine, $errContext = null) {

        $mod = cms_utils::get_module('LogWatch');

        $type = array_search($errType, $this->exceptions) ?: 'UNKNOWN';
        $file = $errFile;
        $line = $errLine;
        $descriptionParts = $this->separateErrorDescription($errStr);

        $mod = cms_utils::get_module('LogWatch');
        
        $logitem = new FileItem();
        $logitem->name = $mod->GetName();
        $logitem->type = $type;
        $logitem->file = $file;
        $logitem->line = $line;
        $logitem->description = $descriptionParts['beforeStackTrace'];
        $logitem->stacktrace = $descriptionParts['afterStackTrace'];
        $logitem->save();

    }

    function separateErrorDescription($description)
    {
        $parts = explode('Stack trace:', $description, 2);
        $beforeStackTrace = $parts[0];
        $afterStackTrace = isset($parts[1]) ? $parts[1] : '';

        return [
            'beforeStackTrace' => $beforeStackTrace,
            'afterStackTrace' => $this->formatStackTrace($afterStackTrace),
        ];
    }

    private function formatStackTrace($description)
    {
        $formattedDescription = str_replace('\n', "\n", $description);
        $lines = explode("\n", $formattedDescription);
        return implode('<br>', $lines);
    }

    public function shutdownHandler()
    {
        $last_error = error_get_last();
        
        if ($last_error && (
            $last_error['type'] == E_ERROR ||
            $last_error['type'] == E_PARSE ||
            $last_error['type'] == E_CORE_ERROR ||
            $last_error['type'] == E_COMPILE_ERROR
        )) {
            $this->errorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
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
        }
    }

}
?>