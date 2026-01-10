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