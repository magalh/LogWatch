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
    
    public static function groupErrors($errors)
    {
        $grouped = [];
        
        foreach ($errors as $error) {
            $hash = self::getErrorHash($error);
            
            if (!isset($grouped[$hash])) {
                $grouped[$hash] = [
                    'hash' => $hash,
                    'sample_error' => $error,
                    'count' => 0,
                    'first_seen' => $error->created,
                    'last_seen' => $error->created,
                    'instances' => []
                ];
            }
            
            $grouped[$hash]['count']++;
            $grouped[$hash]['first_seen'] = min($grouped[$hash]['first_seen'], $error->created);
            $grouped[$hash]['last_seen'] = max($grouped[$hash]['last_seen'], $error->created);
            $grouped[$hash]['instances'][] = $error;
        }
        
        // Sort by count (most frequent first)
        usort($grouped, function($a, $b) {
            return $b['count'] - $a['count'];
        });
        
        return $grouped;
    }
    
    public static function getErrorHash($log)
    {
        $file = $log->file ?? '';
        $line = $log->line ?? '';
        $description = trim($log->description ?? '');
        return md5($file . ':' . $line . ':' . $description);
    }
}
?>