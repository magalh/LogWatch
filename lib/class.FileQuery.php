<?php
class FileQuery
{

    private $logfilepath;

    public function __construct()
    {
        $this->logfilepath = LogWatch::LOGWATCH_FILE;
    }

    public function parseLogFile()
    {

        $logs = [];
        $file = fopen($this->logfilepath, 'r');
        if ($file) {
            $i = 0;
            while (($logline = fgets($file)) !== false) {
                $parsedLog = $this->parseLogEntry($logline, $i);
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

    private function parseLogEntry($logline, $rowIndex)
    {
        $logPattern = '/^(?<created>[^|]+)\|(?<name>[^|]+)\|(?<type>[^|]+)\|(?<file>[^|]+)\|(?<line>\d+)\|(?<description>[^|]+)\|(?<stacktrace>.*)$/';

        if (preg_match($logPattern, $logline, $matches)) {

            $created = $matches['created'];
            $name = $matches['name'];
            $type = $matches['type'];
            $file = $matches['file'];
            $line = $matches['line'];
            $description = $matches['description'];
            $stacktrace = $matches['stacktrace'];

            $logitem = new FileItem();
            $logitem->row = $rowIndex;
            $logitem->created = $created;
            $logitem->name = $name;
            $logitem->type = $type;
            $logitem->file = $file;
            $logitem->line = $line;
            $logitem->description = $description;
            $logitem->stacktrace = $stacktrace;

            return $logitem;
        }

    }

    private function formatStackTrace($description)
    {
        $formattedDescription = str_replace('\n', "\n", $description);
        $lines = explode("\n", $formattedDescription);
        return implode('<br>', $lines);
    }

}
?>