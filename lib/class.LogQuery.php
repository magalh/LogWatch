<?php
class LogQuery
{

    private $logfilepath;

    public function __construct($logfilepath)
    {
        $this->logfilepath = $logfilepath;
        $this->rootPath = CMS_ROOT_PATH;
    }

    public function parseLogFile()
    {
        clearstatcache();
        $logs = [];
        $file = fopen($this->logfilepath, 'r');
        if ($file) {
            $i = 0;
            while (($line = fgets($file)) !== false) {
                $parsedLog = $this->parseLogEntry($line, $i);
                if ($parsedLog !== null) {
                    $logs[] = $parsedLog;
                    $i++;
                }
            }
            fclose($file);

            usort($logs, function($a, $b) {
                return $b->created - $a->created;
            });
        }
        return $logs;
    }

    private function parseLogEntry($line, $rowIndex)
    {
        // Define regex patterns for different log formats
        $apacheLogPattern = '/\[(.*?)\] \[php:(.*?)\] \[pid (\d+)\] \[client (.*?)\] (.*?) in (.*?) on line (\d+)/';
        $phpErrorLogPattern = '/\[(.*?)\] PHP (.*?):  (.*?) in (.*?) on line (\d+)/';
        $phpErrorLogPattern2 = '/\[(.*?)\] \[php:(.*?)\] \[pid (\d+)\] \[client (.*?)\] (.*?)(?:, referer: .*?)?$/';
        $syslogPattern = '/<\d+>(.*?) (.*?) (.*?)\[(\d+)\]: (.*)/';
        $nginxLogPattern = '/(.*?) - (.*?) \[(.*?)\] "(.*?)" (\d+) (\d+) "(.*?)" "(.*?)"/';
        $jsonLogPattern = '/^\{.*\}$/';

        //Apache
        if (preg_match($apacheLogPattern, $line, $matches)) {
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('D M d H:i:s.u Y', $matches[1])->getTimestamp(),
                $matches[2],
                $matches[5],
                $this->extractFilePath($matches[6]),
                $this->extractStackTrace($line),
                $matches[7],
                $this->getLineIcon($matches[2])
            );
        }

        if (preg_match($phpErrorLogPattern2, $line, $matches)) {
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('D M d H:i:s.u Y', $matches[1])->getTimestamp(),
                $matches[2],
                $matches[5],
                '', // No file path in this pattern
                '', // No stack trace in this pattern
                '', // No line number in this pattern
                $this->getLineIcon($matches[2])
            );
        }

        //PHP
        if (preg_match($phpErrorLogPattern, $line, $matches)) {
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('d-M-Y H:i:s e', $matches[1])->getTimestamp(),
                $matches[2],
                $matches[3],
                $this->extractFilePath($matches[4]),
                $this->extractStackTrace($line),
                $matches[5],
                $this->getLineIcon($matches[2])
            );
        }

        //Syslog
        if (preg_match($syslogPattern, $line, $matches)) {
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $matches[1])->getTimestamp(),
                'syslog',
                $matches[5],
                $this->extractFilePath($matches[5]),
                '',
                '',
                $this->getLineIcon('syslog')
            );
        }

        //Nginx
        if (preg_match($nginxLogPattern, $line, $matches)) {
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('d/M/Y:H:i:s O', $matches[3])->getTimestamp(),
                'nginx',
                $matches[4],
                '',
                '',
                '',
                $this->getLineIcon('nginx')
            );
        }

        //JSON
        if (preg_match($jsonLogPattern, $line)) {
            $logData = json_decode($line, true);
            return $this->createLogObject(
                $rowIndex,
                DateTime::createFromFormat('Y-m-d\TH:i:s.u\Z', $logData['timestamp'])->getTimestamp(),
                $logData['level'],
                $logData['message'],
                '',
                '',
                '',
                $this->getLineIcon($logData['level'])
            );
        }

        // If no pattern matches, use today's date and the line content as the message
        return $this->createLogObject(
            $rowIndex,
            time(), // Current timestamp
            'unknown', // Type set to 'unknown'
            trim($line), // Use the line content as the description
            '', // No file path
            '', // No stack trace
            '', // No line number
            '' // No icon
        );

        return null;
    }

    private function createLogObject($row, $created, $type, $description, $file, $stackTrace, $line, $icon)
    {
        return (object)[
            'row' => $row,
            'created' => $created,
            'type' => $type,
            'description' => $description,
            'file' => $file,
            'stack_trace' => $stackTrace,
            'line' => $line,
            'icon' => $icon,
        ];
    }

    private function extractFilePath($filePath)
    {
        // Remove the root path and any trailing stack trace information
        $filePath = strtok($filePath, ':');
        $filePath = str_replace($this->rootPath, '', $filePath);
        return trim($filePath);
    }

    private function extractStackTrace($line)
    {
        $stackTrace = [];
        if (preg_match('/Stack trace:(.*)/s', $line, $matches)) {
            $stackTraceLines = explode('\n', str_replace($this->rootPath, '', $matches[1]));
            foreach ($stackTraceLines as $stackLine) {
                $stackTrace[] = trim($stackLine);
            }
        }
        return implode('<br>', $stackTrace);
    }

    private function formatStackTrace($description)
    {
        $formattedDescription = str_replace('\n', "\n", $description);
        $lines = explode("\n", $formattedDescription);
        return implode('<br>', $lines);
    }

    public function getLineIcon($type)
    {
        $themeObject = cms_utils::get_theme_object();
        $icon = '';
        switch ($type) {
            case 'WARN':
                case 'warning':
                $icon = $themeObject->DisplayImage('icons/extra/warning.png', 'warning', '', '', 'systemicon');
                break;
            case 'INFO':
                case 'notice':
                    case 'depricated':
                $icon = $themeObject->DisplayImage('icons/extra/info.png', 'info', '', '', 'systemicon');
                break;
            case 'ERROR':
                case 'error':
                $icon = $themeObject->DisplayImage('icons/extra/block.png', 'stop', '', '', 'systemicon');
                break;
            default:
                $icon = '';
        }
        return $icon;
    }

}
?>