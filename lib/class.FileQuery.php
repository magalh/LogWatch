<?php
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

        $logs = [];
        $content = file_get_contents($this->logfilepath);
        if ($content === false) {
            return [];
        }

        // Split by log entry pattern (timestamp at start of line)
        $entries = preg_split('/(?=\[\w+\s+\w+\s+\d+\s+[\d:.]+\s+\d{4}\])/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        $i = 0;
        foreach ($entries as $entry) {
            $parsedLog = ServerLogParser::parseEntry(trim($entry), $i);
            if ($parsedLog !== null) {
                // Handle multiple logs from single entry
                if (is_array($parsedLog)) {
                    foreach ($parsedLog as $log) {
                        $logs[] = $log;
                        $i++;
                    }
                } else {
                    $logs[] = $parsedLog;
                    $i++;
                }
            }
        }

        // Sort by timestamp descending (newest first)
        usort($logs, function($a, $b) {
            return $b->created - $a->created;
        });
        
        return $logs;
    }
}
?>