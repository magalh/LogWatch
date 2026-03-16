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

        return ReverseReader::parseLogFile($this->logfilepath);
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
        
        // Normalize dynamic parts so identical errors group together
        $description = preg_replace('/\[client\s+[^\]]+\]/', '[client]', $description);
        $description = preg_replace('/\[pid\s+\d+\]/', '[pid]', $description);
        $description = preg_replace('/:\d{2,5}\]/', ']', $description);
        
        return md5($file . ':' . $line . ':' . $description);
    }
}
?>