<?php
if (!isset($gCms)) exit;

if( version_compare($oldversion,'1.4.0') < 0 ) {
    $this->CreatePermission(LogWatch::CLEAR_LOGS, 'Clear logs');
    $this->CreatePermission(LogWatch::EXPORT_LOGS, 'Export Logs');
}

?>
