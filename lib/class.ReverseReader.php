<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#---------------------------------------------------------------------------------------------------

/**
 * Reverse Reader - Reads and parses log files from end of file (newest first)
 * Optimized for large log files using fseek/tail instead of loading entire file
 */
class ReverseReader
{
    /**
     * Parse log file using reverse reading
     *
     * @param string $logfilepath Path to log file
     * @param int $limit Maximum entries to return
     * @return array Array of parsed log entries (newest first)
     */
    public static function parseLogFile($logfilepath)
    {
        if (!file_exists($logfilepath) || !is_readable($logfilepath)) {
            return [];
        }

        // Detect format by sampling first lines
        $handle = fopen($logfilepath, 'r');
        if (!$handle) {
            return [];
        }

        $sampleLines = [];
        for ($i = 0; $i < 5 && !feof($handle); $i++) {
            $line = trim(fgets($handle));
            if (!empty($line)) {
                $sampleLines[] = $line;
            }
        }
        fclose($handle);

        $isPhpLog = false;
        foreach ($sampleLines as $line) {
            if (preg_match('/^\[\d{2}-\w{3}-\d{4}\s+\d{2}:\d{2}:\d{2}\s+[^\]]+\]\s+PHP\s+(Fatal error|Warning|Notice|Parse error|Deprecated):/', $line)) {
                $isPhpLog = true;
                break;
            }
        }

        // Get raw entries from file (newest first)
        if ($isPhpLog) {
            $entries = self::getPhpEntriesReverse($logfilepath);
        } else {
            $entries = self::getApacheEntriesReverse($logfilepath);
        }

        // Parse entries using existing parsers
        $logs = [];
        foreach ($entries as $i => $entry) {
            $parsed = ServerLogParser::parseEntry($entry, $i);
            if ($parsed !== null) {
                $logs[] = $parsed;
            }
        }

        // Sort by date DESC
        usort($logs, function ($a, $b) {
            return $b->created - $a->created;
        });

        return $logs;
    }

    /**
     * Get Apache log entries in reverse order
     */
    private static function getApacheEntriesReverse($file)
    {
        return self::getApacheEntriesReverseSpl($file);
    }

    /**
     * Get PHP log entries in reverse order
     */
    private static function getPhpEntriesReverse($file)
    {
        return self::getPhpEntriesReverseSpl($file);
    }

    /**
     * Parse Apache lines in reverse order, reassembling multi-line entries
     */
    private static function parseLinesReverseApache($lines)
    {
        $entries = [];
        $current = '';

        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $line = rtrim($lines[$i]);
            if ($line === '') {
                continue;
            }

            // Apache log starts with [Day Mon DD ...]
            if (preg_match('/^\[[A-Z][a-z]{2}\s[A-Z][a-z]{2}\s+\d{1,2}\s/', $line)) {
                if ($current !== '') {
                    $entries[] = trim($current);
                }
                $current = $line . "\n";
            } else {
                $current = $line . "\n" . $current;
            }
        }

        if ($current !== '') {
            $entries[] = trim($current);
        }

        return $entries;
    }

    /**
     * Parse PHP lines in reverse order, reassembling multi-line entries (stack traces)
     */
    private static function parseLinesReversePhp($lines)
    {
        $entries = [];
        $current = '';

        for ($i = count($lines) - 1; $i >= 0; $i--) {
            $line = rtrim($lines[$i]);
            if ($line === '') {
                continue;
            }

            // PHP log starts with [DD-Mon-YYYY HH:MM:SS TZ] PHP
            if (preg_match('/^\[\d{2}-\w{3}-\d{4}\s+\d{2}:\d{2}:\d{2}\s+[^\]]+\]\s+PHP\s+/', $line)) {
                if ($current !== '') {
                    $entries[] = trim($current);
                }
                $current = $line . "\n";
            } else {
                $current = $line . "\n" . $current;
            }
        }

        if ($current !== '') {
            $entries[] = trim($current);
        }

        return $entries;
    }

    /**
     * Get Apache log entries using SplFileObject (fallback for Windows / no shell_exec)
     */
    private static function getApacheEntriesReverseSpl($file)
    {
        $fp = new SplFileObject($file, 'r');
        $fp->seek(PHP_INT_MAX);
        $lastLine = $fp->key();

        $entries = [];
        $current = '';

        for ($i = $lastLine; $i >= 0; $i--) {
            $fp->seek($i);
            $line = rtrim($fp->current());

            if ($line === '') {
                continue;
            }

            if (preg_match('/^\[[A-Z][a-z]{2}\s[A-Z][a-z]{2}\s+\d{1,2}\s/', $line)) {
                if ($current !== '') {
                    $entries[] = trim($current);
                }
                $current = $line . "\n";
            } else {
                $current = $line . "\n" . $current;
            }
        }

        if ($current !== '') {
            $entries[] = trim($current);
        }

        return $entries;
    }

    /**
     * Get PHP log entries using SplFileObject (fallback for Windows / no shell_exec)
     */
    private static function getPhpEntriesReverseSpl($file)
    {
        $fp = new SplFileObject($file, 'r');
        $fp->seek(PHP_INT_MAX);
        $lastLine = $fp->key();

        $entries = [];
        $current = '';

        for ($i = $lastLine; $i >= 0; $i--) {
            $fp->seek($i);
            $line = rtrim($fp->current());

            if ($line === '') {
                continue;
            }

            if (preg_match('/^\[\d{2}-\w{3}-\d{4}\s+\d{2}:\d{2}:\d{2}\s+[^\]]+\]\s+PHP\s+/', $line)) {
                if ($current !== '') {
                    $entries[] = trim($current);
                }
                $current = $line . "\n";
            } else {
                $current = $line . "\n" . $current;
            }
        }

        if ($current !== '') {
            $entries[] = trim($current);
        }

        return $entries;
    }
}
?>
