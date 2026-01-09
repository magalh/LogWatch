<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#          see /LogWatch/README.md or <http://www.gnu.org/licenses/gpl-2.0.html>
#---------------------------------------------------------------------------------------------------
# CMS Made Simple(TM) is (c) CMS Made Simple Foundation 2004-2020 (info@cmsmadesimple.org)
# Project's homepage is: http://www.cmsmadesimple.org
#---------------------------------------------------------------------------------------------------
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# However, as a special exception to the GPL, this software is distributed
# as an addon module to CMS Made Simple. You may not use this software
# in any Non GPL version of CMS Made simple, or in any version of CMS
# Made simple that does not indicate clearly and obviously in its admin
# section that the site was built with CMS Made simple.
#---------------------------------------------------------------------------------------------------
use Kassner\LogParser\LogParser;

class ServerLogParser
{
    public static function parseEntry($logEntry, $rowIndex)
    {
        $logEntry = trim($logEntry);
        
        // Skip empty lines or malformed entries
        if (empty($logEntry)) {
            return null;
        }
        
        // Try PHP error log format first: [timestamp] PHP Level: message
        if (preg_match('/^\[([^\]]+)\]\s+PHP\s+(\w+):\s*(.+)$/', $logEntry, $matches)) {
            return self::parsePHPError($matches, $rowIndex);
        }
        
        // Try Apache error log format: [timestamp] [module:level] [pid] [client] message
        if (preg_match('/^\[([^\]]+)\]\s+\[([^\]]+)\](?:\s+\[[^\]]+\])*\s*(.+)$/', $logEntry, $matches)) {
            return self::parseApacheError($matches, $rowIndex);
        }
        
        // Try Nginx error log format: timestamp [level] pid#tid: message
        if (preg_match('/^(\d{4}\/\d{2}\/\d{2}\s+\d{2}:\d{2}:\d{2})\s+\[([^\]]+)\]\s+\d+#\d+:\s*(.+)$/', $logEntry, $matches)) {
            return self::parseNginxError($matches, $rowIndex);
        }
        
        // Try syslog format: timestamp hostname process[pid]: message
        if (preg_match('/^(\w{3}\s+\d{1,2}\s+\d{2}:\d{2}:\d{2})\s+\S+\s+(\w+)(?:\[\d+\])?:\s*(.+)$/', $logEntry, $matches)) {
            return self::parseSyslogError($matches, $rowIndex);
        }
        
        return null;
    }
    
    private static function parsePHPError($matches, $rowIndex)
    {
        $timestamp_str = $matches[1];
        $level = $matches[2];
        $message = $matches[3];
        
        $created = strtotime($timestamp_str);
        if ($created === false) {
            $created = time();
        }
        
        // Better error type detection
        if (false !== strpos($message, 'PHP Warning')) {
            $level = 'Warning';
            $message = str_replace('PHP Warning:', '', $message);
        } else if (false !== strpos($message, 'PHP Notice')) {
            $level = 'Notice';
            $message = str_replace('PHP Notice:', '', $message);
        } else if (false !== strpos($message, 'PHP Fatal error')) {
            $level = 'Fatal error';
            $message = str_replace('PHP Fatal error:', '', $message);
        } else if (false !== strpos($message, 'PHP Parse error')) {
            $level = 'Parse error';
            $message = str_replace('PHP Parse error:', '', $message);
        } else if (false !== strpos($message, 'PHP Deprecated')) {
            $level = 'Deprecated';
            $message = str_replace('PHP Deprecated:', '', $message);
        }
        
        $message = trim($message);
        
        // Extract file and line
        $errorFile = '';
        $errorLine = 0;
        
        if (false !== strpos($message, ' on line ')) {
            $parts = explode(' on line ', $message);
            $errorLine = (int)trim($parts[1]);
            $message = str_replace(' on line ' . $errorLine, '', $message);
        }
        
        if (false !== strpos($message, ' in /')) {
            $parts = explode(' in /', $message);
            $errorFile = '/' . trim($parts[1]);
            $message = str_replace(' in ' . $errorFile, '', $message);
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $created;
        $logitem->name = 'PHP Error Log';
        $logitem->type = $level;
        $logitem->description = trim($message);
        $logitem->file = $errorFile;
        $logitem->line = $errorLine;
        $logitem->stacktrace = htmlspecialchars($matches[3], ENT_QUOTES);
        
        return $logitem;
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
    
    private static function parseNginxError($matches, $rowIndex)
    {
        $timestamp_str = $matches[1];
        $level = $matches[2];
        $message = $matches[3];
        
        $created = strtotime($timestamp_str);
        if ($created === false) {
            $created = time();
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $created;
        $logitem->name = 'Nginx Error Log';
        $logitem->type = ucfirst($level);
        $logitem->description = self::extractShortDescription($message);
        $logitem->file = self::extractFile($message);
        $logitem->line = self::extractLine($message);
        $logitem->stacktrace = htmlspecialchars($message, ENT_QUOTES);
        
        return $logitem;
    }
    
    private static function parseSyslogError($matches, $rowIndex)
    {
        $timestamp_str = $matches[1];
        $process = $matches[2];
        $message = $matches[3];
        
        $timestamp_str = date('Y') . ' ' . $timestamp_str;
        $created = strtotime($timestamp_str);
        if ($created === false) {
            $created = time();
        }
        
        $logitem = new stdClass();
        $logitem->row = $rowIndex;
        $logitem->created = $created;
        $logitem->name = 'System Log';
        $logitem->type = ucfirst($process);
        $logitem->description = self::extractShortDescription($message);
        $logitem->file = self::extractFile($message);
        $logitem->line = self::extractLine($message);
        $logitem->stacktrace = htmlspecialchars($message, ENT_QUOTES);
        
        return $logitem;
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