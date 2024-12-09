<?php
if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

try {
    // Check if log file exists and is readable
    $logFilePath = LogWatch::LOGWATCH_FILE;
    if (!file_exists($logFilePath) || !is_readable($logFilePath)) {
        throw new Exception($this->Lang('log_file_not_readable'));
    }

    // Set headers for CSV download
    $timestamp = date('Y-m-d_H-i-s');
    $filename = "logwatch_export_{$timestamp}.csv";
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV header based on your format
    fputcsv($output, array(
        'Created',
        'Name',
        'Type',
        'File',
        'Line',
        'Description',
        'Stacktrace'
    ));

    // Read log file and parse entries
    $logContent = file_get_contents($logFilePath);
    $logEntries = explode("\n", $logContent);

    foreach ($logEntries as $entry) {
        if (empty(trim($entry))) {
            continue;
        }

        // Parse pipe-delimited log entry
        $data = explode('|', $entry);
        if (count($data) >= 7) {
            fputcsv($output, array(
                $data[0], // created
                $data[1], // name
                $data[2], // type
                $data[3], // file
                $data[4], // line
                $data[5], // description
                $data[6]  // stacktrace
            ));
        }
    }

    fclose($output);
    exit();

} catch (Exception $e) {
    $this->SetError($this->Lang('error_exporting_logs'));
    $this->RedirectToAdminTab();
}

?>