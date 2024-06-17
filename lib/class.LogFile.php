<?php
class LogFile
{
    
    private $filePath;
    private $lastError;

    public function __construct($filePath)
   {
      $this->filePath = $filePath;
      $this->lastError = '';
      $this->mod = cmsms()->GetModuleInstance('LogWatch');
   }

   public function removeLine($lineNumber)
    {

        try {
            // Check if the log file exists and is writable
            if (!is_writable($this->filePath)) {
                // Attempt to change the file permissions
                if (!chmod($this->filePath, 0666)) {
                    throw new LogicException($this->mod->Lang('log_line_delete_permission_error',$this->filePath));
                }
            }

            // Read the contents of the log file into an array
            $lines = file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Remove the line at the specified index ($lineNumber - 1) from the array
            if ($lineNumber >= 0 && $lineNumber < count($lines)) {
                unset($lines[$lineNumber]);
            } else {
                throw new LogicException($this->mod->Lang('log_line_delete_invalid_line_error'));
            }

            // Open the log file for writing
            $file = fopen($this->filePath, 'w');

            // Write the updated array of lines back to the log file
            if ($file) {
                fwrite($file, implode("\n", $lines));
                fclose($file);
                audit('', 'LogWatch', $this->mod->Lang('log_line_deleted'));
                return true;
            } else {
                throw new LogicException($this->mod->Lang('log_line_delete_write_error'));
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

    private function makeFileWritable($filepath)
    {
        // Attempt to make the file writable
        if (!is_writable($filepath)) {
            return chmod($filepath, 0666); // Change file mode to 0666
        }
        return true;
    }
}
?>