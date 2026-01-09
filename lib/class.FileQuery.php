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

        // Split by lines and process each line as a separate entry
        $lines = explode("\n", $content);
        $entries = array_filter(array_map('trim', $lines), function($line) {
            return !empty($line) && strpos($line, '[') === 0;
        });
        
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