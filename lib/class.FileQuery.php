<?php
#--------------------------------------------------
# See doc/LICENSE for full license information.
#--------------------------------------------------
class FileQuery
{
    private $logfilepath;

    public function __construct($logfilepath = null)
    {
        $this->logfilepath = $logfilepath;
    }

    public function parseLogFile()
    {
        if (!$this->logfilepath || !file_exists($this->logfilepath) || !is_readable($this->logfilepath)) {
            return [];
        }

        // Detect log format by reading first few lines
        $handle = fopen($this->logfilepath, 'r');
        $sampleLines = [];
        for ($i = 0; $i < 5 && !feof($handle); $i++) {
            $line = trim(fgets($handle));
            if (!empty($line)) {
                $sampleLines[] = $line;
            }
        }
        fclose($handle);
        
        // Check if it's PHP error log format
        foreach ($sampleLines as $line) {
            if (preg_match('/^\[\d{2}-\w{3}-\d{4}\s+\d{2}:\d{2}:\d{2}\s+[^\]]+\]\s+PHP\s+(Fatal error|Warning|Notice|Parse error|Deprecated):/', $line)) {
                return PhpLogParser::parseLogFile($this->logfilepath);
            }
        }
        
        // Default to Apache log parser
        return ApacheLogParser::parseLogFile($this->logfilepath);
    }
}
?>