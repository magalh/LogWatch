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
        $logs = [];
        $file = fopen($this->logfilepath, 'r');
        if ($file) {
            $i = 0;
            while (($line = fgets($file)) !== false) {
                if (preg_match('/\[(.*?)\] \[php:(.*?)\] \[pid (\d+)\] \[client (.*?)\] (.*?) in (.*?) on line (\d+)/', $line, $matches)) {
                    
                    $description = $matches[5];
                    $filePath = $this->extractFilePath($matches[6]);
                    $stackTrace = $this->extractStackTrace($line);

                    $date = DateTime::createFromFormat('D M d H:i:s.u Y', $matches[1]);
                    if ($date === false) {
                        // Handle potential date parsing errors
                        continue;
                    }
                    
                    $logs[] = (object)[
                        'row' => $i++,
                        'created' => $date->getTimestamp(),
                        'type' => $matches[2],
                        'description' => $description,
                        'file' => $filePath,
                        'stack_trace' => $stackTrace,
                        'line' => $matches[7],
                        'icon' => $this->getLineIcon($matches[2]),
                    ];
                }
            }
            fclose($file);

            usort($logs, function($a, $b) {
                return $b->created - $a->created;
            });

        }
        return $logs;
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
                $icon = $themeObject->DisplayImage('icons/system/warning.gif', 'warning', '', '', 'systemicon');
                break;
            case 'INFO':
                case 'notice':
                    case 'depricated':
                $icon = $themeObject->DisplayImage('icons/system/info.gif', 'info', '', '', 'systemicon');
                break;
            case 'ERROR':
                case 'error':
                $icon = $themeObject->DisplayImage('icons/system/stop.gif', 'stop', '', '', 'systemicon');
                break;
            default:
                $icon = '';
        }
        return $icon;
    }

}
?>