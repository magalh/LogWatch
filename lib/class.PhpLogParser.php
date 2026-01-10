<?php
class PhpLogParser
{
    public static function parseLogFile($logfilepath)
    {
        if (!file_exists($logfilepath) || !is_readable($logfilepath)) {
            return [];
        }

        $content = file_get_contents($logfilepath);
        if ($content === false) {
            return [];
        }

        // Split into multiline entries
        $entries = preg_split('/(?=\[\d{2}-\w{3}-\d{4}\s+\d{2}:\d{2}:\d{2}\s+[^\]]+\]\s+PHP)/', $content, -1, PREG_SPLIT_NO_EMPTY);
        
        $logs = [];
        $i = 0;
        foreach ($entries as $entry) {
            $entry = trim($entry);
            if (empty($entry)) continue;
            
            $parsedLog = self::parseEntry($entry, $i);
            if ($parsedLog !== null) {
                $logs[] = $parsedLog;
                $i++;
            }
        }

        usort($logs, function($a, $b) {
            return $b->created - $a->created;
        });
        
        return $logs;
    }

    private static function parseEntry($log, $rowIndex)
    {
        $lines = preg_split("/\r?\n/", trim($log));
        $error = [
            'timestamp' => null,
            'timezone'  => null,
            'level'     => null,
            'message'   => null,
            'file'      => null,
            'line'      => null,
            'stack'     => []
        ];

        // Main error line
        if (preg_match(
            '/^\[(\d{2}-\w{3}-\d{4} \d{2}:\d{2}:\d{2}) ([^\]]+)\]\s+PHP\s+([A-Za-z ]+):\s+(.*?)(?:\s+in\s+(.*?)(?:\s+on\s+line\s+|:)(\d+))?$/',
            $lines[0],
            $m
        )) {
            $error['timestamp'] = $m[1];
            $error['timezone']  = $m[2];
            $error['level']     = trim($m[3]);
            $error['message']   = trim($m[4]);
            $error['file']      = $m[5] ?? '';
            $error['line']      = isset($m[6]) ? (int)$m[6] : 0;
        }

        // Stack trace
        foreach ($lines as $line) {
            if (preg_match('/^#\d+\s+(.*)$/', $line, $m)) {
                $error['stack'][] = $m[1];
            }
        }
        
        $created = strtotime($error['timestamp']);
        if ($created === false) {
            $created = time();
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $created;
        $logitem->name = 'PHP Error Log';
        $logitem->type = $error['level'];
        $logitem->description = $error['message'];
        $logitem->file = $error['file'];
        $logitem->line = $error['line'];
        $logitem->stacktrace = htmlspecialchars($log, ENT_QUOTES);
        
        return $logitem;
    }
}
?>