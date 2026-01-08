<?php
use Kassner\LogParser\LogParser;

class ServerLogParser
{
    public static function parseEntry($logEntry, $rowIndex)
    {
        // Parse Apache error log format: [timestamp] [module:level] [pid] [client] message
        $pattern = '/^\[([^\]]+)\]\s+\[([^:]+):([^\]]+)\]\s+(?:\[pid\s+\d+\]\s+)?(?:\[client\s+[^\]]+\]\s+)?(.+)$/s';
        
        if (!preg_match($pattern, trim($logEntry), $matches)) {
            return null;
        }
        
        $timestamp_str = $matches[1];
        $module = $matches[2];
        $level = ucfirst($matches[3]);
        $message = $matches[4];
        
        // Parse timestamp
        $timestamp_str = preg_replace('/\.(\d+)/', '', $timestamp_str);
        $created = strtotime($timestamp_str);
        if ($created === false) {
            if (preg_match('/\w+\s+(\w+)\s+(\d+)\s+([\d:]+)\s+(\d+)/', $timestamp_str, $dateMatches)) {
                $created = strtotime("{$dateMatches[1]} {$dateMatches[2]} {$dateMatches[4]} {$dateMatches[3]}");
            } else {
                $created = time();
            }
        }
        
        $logs = [];
        
        // Check if this contains PHP messages
        if (preg_match('/Got error \'(.+)\'(?:,\s*referer:.*)?$/s', $message, $errorMatch)) {
            $errorContent = $errorMatch[1];
            
            // Split by PHP message boundaries
            $parts = preg_split('/;\s*PHP message:\s*/i', $errorContent);
            
            foreach ($parts as $index => $part) {
                if (empty(trim($part))) continue;
                
                // Clean up the part
                $part = preg_replace('/^PHP message:\s*/i', '', $part);
                
                $logitem = new stdClass();
                $logitem->row = $rowIndex + $index;
                $logitem->created = $created;
                $logitem->name = 'Server Log';
                $logitem->type = self::extractErrorType($part);
                $logitem->description = self::extractShortDescription($part);
                $logitem->file = self::extractFile($part);
                $logitem->line = self::extractLine($part);
                $logitem->stacktrace = htmlspecialchars(str_replace('\n', '<br>', trim($part)), ENT_QUOTES);
                
                $logs[] = $logitem;
            }
        } else {
            // Non-PHP error (Apache/proxy errors)
            $logitem = new stdClass();
            $logitem->row = $rowIndex;
            $logitem->created = $created;
            $logitem->name = 'Server Log';
            $logitem->type = $level;
            $logitem->description = trim($message);
            $logitem->file = '';
            $logitem->line = 0;
            $logitem->stacktrace = htmlspecialchars(str_replace('\n', '<br>', trim($message)), ENT_QUOTES);
            $logs[] = $logitem;
        }
        
        return count($logs) === 1 ? $logs[0] : $logs;
    }
    
    private static function extractErrorType($part)
    {
        // Extract error type from PHP messages
        if (preg_match('/^PHP\s+(\w+(?:\s+\w+)*):/i', $part, $match)) {
            return trim($match[1]);
        }
        if (preg_match('/^(Fatal error|Warning|Notice|Deprecated|Error):/i', $part, $match)) {
            return trim($match[1]);
        }
        return 'Error';
    }
    
    private static function extractShortDescription($part)
    {
        // Extract just the error message without file/line info
        if (preg_match('/^PHP\s+\w+(?:\s+\w+)*:\s*(.+?)\s+in\s+/', $part, $match)) {
            return trim($match[1]);
        }
        if (preg_match('/^\w+(?:\s+\w+)*:\s*(.+?)\s+in\s+/', $part, $match)) {
            return trim($match[1]);
        }
        // Fallback - take first 100 characters
        return substr(trim($part), 0, 100) . (strlen(trim($part)) > 100 ? '...' : '');
    }
    
    private static function extractFile($part)
    {
        // Try various patterns for file extraction - get only the first file mentioned
        if (preg_match('/\s+in\s+([^\s]+)\s+on\s+line\s+\d+/', $part, $match)) {
            $fullPath = trim($match[1]);
            // Remove document root prefix if present
            $fullPath = preg_replace('/^\/mnt\/f\/cmsms\.com\/public_html/', '', $fullPath);
            return $fullPath;
        }
        if (preg_match('/\s+in\s+template\s+"([^"]+)"/', $part, $match)) {
            return trim($match[1]);
        }
        return '';
    }
    
    private static function extractLine($part)
    {
        // Extract line number
        if (preg_match('/\s+on\s+line\s+(\d+)/', $part, $match)) {
            return (int)$match[1];
        }
        return 0;
    }
}
?>