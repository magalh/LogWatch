<?php
class FileItem
{
    private $lastError;
    private $_data = array('row'=>null, 'name'=>null, 'type'=>null, 'description'=>null, 'stacktrace'=>null, 'file'=>null, 'line'=>null, 'created'=>null);

    public function __construct()
    {
        $this->lastError = '';
        $this->mod = \cms_utils::get_module("LogWatch");
    }

    public function __get($key)
    {
        switch ($key) {
            case 'row':
            case 'name':
            case 'type':
            case 'line':
            case 'description':
            case 'stacktrace':
            case 'created':
            case 'file':
                return $this->_data[$key];
        }
    }

    public function __set($key, $val)
    {
        switch ($key) {
            case 'name':
            case 'type':
            case 'file':
            case 'description':
            case 'stacktrace':
                $this->_data[$key] = trim($val);
                break;
            case 'line':
            case 'row':
            case 'created':
                $this->_data[$key] = (int) $val;
                break;
        }
    }

    public function save()
    {
        if (!$this->is_valid()) return FALSE;

        $createdTimestamp = strtotime($this->created ?: date('Y-m-d H:i:s'));
        // Create a log line with the format: created|name|type|file|line|description|stacktrace
        $logLine = sprintf(
            "%s|%s|%s|%s|%d|%s|%s",
            $createdTimestamp,
            $this->name,
            $this->type,
            $this->file,
            $this->line,
            $this->description,
            $this->stacktrace
        );

        $filePath = LogWatch::LOGWATCH_FILE;
        if ($this->lineExists($this->file, $this->line, $filePath)) {
            $this->lastError = "Already exists:".$this->file. " " .$this->line;
            //error_log($this->lastError,E_NOTICE);
            return FALSE;
        }

        // Log the line
        $this->debug_to_log($logLine);
        return TRUE;
    }

    private function lineExists($file, $line, $filePath)
    {
        if (!file_exists($filePath)) {
            return FALSE;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $logLine) {
            $logParts = explode('|', $logLine);
            if (count($logParts) > 4 && $logParts[3] == $file && $logParts[4] == $line) {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function is_valid()
    {
        if (!$this->name) return false;
        if (!$this->description) return false;
        return TRUE;
    }

    private function debug_to_log($logLine)
    {
        if ($filename == '') {
            $filename = LogWatch::LOGWATCH_FILE;
            $x = (is_file($filename)) ? @filemtime($filename) : time();
            if ($x !== FALSE && $x < (time() - 24 * 3600)) unlink($filename);
        }
        error_log($logLine . "\n", 3, $filename);
    }

    public function removeLine($lineNumber)
    {

        try {

            $mod = \cms_utils::get_module("LogWatch");
            // Read the contents of the log file into an array
            $filePath = LogWatch::LOGWATCH_FILE;
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Remove the line at the specified index ($lineNumber - 1) from the array
            if ($lineNumber >= 0 && $lineNumber < count($lines)) {
                unset($lines[$lineNumber]);
            } else {
                throw new LogicException($mod->Lang('log_line_delete_invalid_line_error'));
            }

            // Open the log file for writing
            $file = fopen($filePath, 'w');

            // Write the updated array of lines back to the log file
            if ($file) {
                fwrite($file, implode("\n", $lines));
                fclose($file);
                audit('', 'LogWatch', $mod->Lang('log_line_deleted'));
                return true;
            } else {
                throw new LogicException($mod->Lang('log_line_delete_write_error'));
            }

        } catch (LogicException $e) {
            $message = $e->getMessage();
            $this->lastError = $message;
            audit('', 'LogWatch', $message);
            return false; // Ensure the method returns false on error
        }
    }

    public function getLastError()
    {
        return $this->lastError;
    }

}
?>
