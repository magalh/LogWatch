<?php

if( !defined('CMS_VERSION') ) exit;
if( !$this->CheckPermission(LogWatch::MANAGE_PERM) ) return;

$startelement = 0;
$pagenum = 1;
$agelimit = -1;
$pagelimit = 10000;
$thispage = 1;

if ( isset($params['pagenum']) ) $thispage = (int)$params['pagenum'];

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('admin_logs_tab.tpl'),null,null,$smarty);

$error = null;
$message = null;

try{

    $logfilepath = $this->GetPreference('logfilepath');
    $tpl->assign('logfilepath',$logfilepath);

    // Debugging output
    if (!file_exists($logfilepath)) {
        throw new LogicException('Log file does not exist: ' . $logfilepath);
    }
    if (!is_readable($logfilepath)) {
        throw new LogicException('Log file is not readable: ' . $logfilepath);
    }

    // Read the log file and parse the contents
    $logs = parseLogFile($logfilepath);

    $matchcount = count($logs);
    $pagelimit = 10; // Define your page limit
    $thispage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate page variables
    $npages = (int)($matchcount / $pagelimit);
    if ($matchcount % $pagelimit > 0) $npages++;
    $startoffset = ($thispage - 1) * $pagelimit;

    // Slice logs to display only the current page logs
    $logs = array_slice($logs, $startoffset, $pagelimit);


} catch (LogicException $e) {
    $error = 1;	
    $message = $e->getMessage();
}


$tpl->assign('message',$message);
$tpl->assign('error',$error);
$tpl->assign('logs',$logs);
$tpl->display();

function parseLogFile($logfilepath)
{
    $logs = [];
    $file = fopen($logfilepath, 'r');
    if ($file) {
        while (($line = fgets($file)) !== false) {
            if (preg_match('/\[(.*?)\] \[php:(.*?)\] \[pid (\d+)\] \[client (.*?)\] (.*?) in (.*?) on line (\d+)/', $line, $matches)) {
                $logs[] = (object)[
                    'created' => $matches[1],
                    'type' => $matches[2],
                    'description' => $matches[5],
                    'file' => $matches[6],
                    'line' => $matches[7],
                ];
            }
        }
        fclose($file);
    }
    return $logs;
}