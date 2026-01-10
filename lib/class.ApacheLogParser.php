<?php
use Kassner\LogParser\LogParser;

class ApacheLogParser
{
    public static function parseLogFile($logfilepath)
    {
        if (!file_exists($logfilepath) || !is_readable($logfilepath)) {
            return [];
        }

        $parser = new LogParser();
        $lines = file($logfilepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = [];
        $i = 0;
        
        foreach ($lines as $line) {
            try {
                $entry = $parser->parse($line);
                $parsedLog = self::convertToLogItem($entry, $i);
                if ($parsedLog !== null) {
                    $logs[] = $parsedLog;
                    $i++;
                }
            } catch (Exception $e) {
                // Fallback to manual parsing for non-standard formats
                $parsedLog = self::parseEntry($line, $i);
                if ($parsedLog !== null) {
                    $logs[] = $parsedLog;
                    $i++;
                }
            }
        }

        usort($logs, function($a, $b) {
            return $b->created - $a->created;
        });
        
        return $logs;
    }

    private static function convertToLogItem($entry, $rowIndex)
    {
        if (!$entry || !isset($entry->time)) {
            return null;
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $entry->time;
        $logitem->name = 'Apache Log';
        $logitem->type = isset($entry->status) ? 'HTTP ' . $entry->status : 'Info';
        $logitem->description = isset($entry->request) ? $entry->request : '';
        $logitem->file = isset($entry->file) ? $entry->file : '';
        $logitem->line = 0;
        $logitem->stacktrace = json_encode($entry, JSON_PRETTY_PRINT);
        
        return $logitem;
    }
    
    private static function parseEntry($logEntry, $rowIndex)
    {
        // Try Apache error log format: [timestamp] [module:level] [pid] [client] message
        if (preg_match('/^\[([^\]]+)\]\s+\[([^\]]+)\](?:\s+\[[^\]]+\])*\s*(.+)$/', $logEntry, $matches)) {
            return self::parseApacheError($matches, $rowIndex);
        }
        
        return null;
    }

    private static function parseApacheError($matches, $rowIndex)
    {
        $timestamp_str = $matches[1];
        $module_level = $matches[2];
        $message = $matches[3];
        
        // Extract PHP error type from message if it contains PHP errors
        $level = 'Error';
        if (preg_match('/PHP message: PHP (Fatal error|Warning|Notice|Deprecated|Error):/i', $message, $phpMatch)) {
            $level = $phpMatch[1];
        } else if (preg_match('/([^:]+:)?(emerg|alert|crit|error|warn|notice|info|debug)$/i', $module_level, $levelMatch)) {
            $level = ucfirst(strtolower($levelMatch[2]));
            if ($level === 'Warn') $level = 'Warning';
            if ($level === 'Crit') $level = 'Critical';
        }
        
        // Parse Apache timestamp: "Fri Jan 09 10:47:57.993211 2026"
        if (preg_match('/^(\w{3}\s+\w{3}\s+\d{1,2}\s+\d{2}:\d{2}:\d{2})(?:\.\d+)?\s+(\d{4})/', $timestamp_str, $timeMatch)) {
            $created = strtotime($timeMatch[1] . ' ' . $timeMatch[2]);
        } else {
            $created = strtotime($timestamp_str);
        }
        
        if ($created === false) {
            $created = time();
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $created;
        $logitem->name = 'Apache Error Log';
        $logitem->type = $level;
        $logitem->description = self::extractShortDescription($message);
        $logitem->file = self::extractFile($message);
        $logitem->line = self::extractLine($message);
        $logitem->stacktrace = htmlspecialchars($message, ENT_QUOTES);
        
        return $logitem;
    }

    private static function extractShortDescription($part)
    {
        // For Apache logs with PHP messages, extract just the PHP error message
        if (preg_match('/Got error \'PHP message: PHP (?:Fatal error|Warning|Notice|Deprecated|Error):\s*(.+?)\s+in\s+/', $part, $match)) {
            return trim($match[1]);
        }
        
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