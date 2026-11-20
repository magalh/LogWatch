<?php
if (php_sapi_name() === 'cli') {
    define('CMS_CLI', 1);
}
require_once("../../lib/include.php");

$moduleDir = getcwd();
$moduleName = isset($argv[1]) ? $argv[1] : 'TwoFactor';

define('MODULE_DTD_VERSION', '1.3');

// Build exclude patterns from .distignore if available, otherwise use defaults
// NOTE: CMSMS get_recursive_file_list() matches regex against basename() only,
// so path-based patterns (e.g. lib/aws.phar) need special handling.
$distignore = $moduleDir . '/.distignore';
$xml_exclude_files = [];
$xml_exclude_paths = []; // For path-based exclusions (contain /)

if (file_exists($distignore)) {
    $lines = file($distignore, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;

        if (strpos($line, '/') !== false) {
            // Path-based pattern - handle separately since get_recursive_file_list
            // only matches against basename
            $xml_exclude_paths[] = $line;
        } else {
            // Simple name pattern - convert to regex for basename matching
            $pattern = preg_quote($line, '/');
            $pattern = str_replace('\\*', '.*', $pattern);
            $xml_exclude_files[] = "^" . $pattern;
        }
    }
    // Always exclude archive files
    $xml_exclude_files[] = "^" . preg_quote($moduleName) . ".*\\.(tar\\.gz|zip|xml)$";
} else {
    $xml_exclude_files = [
        "^\\.svn", "^CVS$", "^\\#.*\\#$", "~$", "\\.bak$",
        "^\\.env", "^\\.kiro", "^\\.claude", "^\\.git", "^\\.tmp$",
        "^\\.amazonq", "^build$", "^releases", "^moduleinfo\\.ini$",
        "^composer\\.(json|lock)$",
        "^\\.assets", "^\\.distignore$", "^\\.gitignore$", "^README\\.md$"
    ];
}

$modinstance = \cms_utils::get_module($moduleName);
if (!$modinstance) {
    die("Error: Could not load module $moduleName\n");
}

// CreateXMLPackage logic starts here
global $CMSMS_GENERATING_XML;
$CMSMS_GENERATING_XML = 1;

$filecount = 0;
$dir = $moduleDir;

$files = get_recursive_file_list($dir, $xml_exclude_files);

// Filter out path-based exclusions
if (!empty($xml_exclude_paths)) {
    $files = array_filter($files, function($file) use ($dir, $xml_exclude_paths) {
        $rel = substr($file, strlen($dir) + 1); // relative path without leading /
        if ($rel === false || $rel === '') return true; // keep root dir entry
        foreach ($xml_exclude_paths as $excludePath) {
            // Exact match or prefix match for directories
            if ($rel === $excludePath || strpos($rel, $excludePath . '/') === 0) {
                return false;
            }
        }
        return true;
    });
}

// Always exclude .git directories (including submodules like rules/.git)
$files = array_filter($files, function($file) use ($dir) {
    $rel = substr($file, strlen($dir) + 1);
    if ($rel === false || $rel === '') return true;
    // Exclude .git dir at root or nested (submodules)
    if ($rel === '.git' || strpos($rel, '.git/') === 0 || strpos($rel, '/.git') !== false) {
        return false;
    }
    return true;
});

$xmltxt = '<?xml version="1.0" encoding="ISO-8859-1"?>';
$xmltxt .= "\n<!DOCTYPE module [\n";
$xmltxt .= "  <!ELEMENT module (dtdversion,name,version,description*,help*,about*,requires*,file+)>\n";
$xmltxt .= "  <!ELEMENT dtdversion (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT name (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT version (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT mincmsversion (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT description (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT help (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT about (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT requires (requiredname,requiredversion)>\n";
$xmltxt .= "  <!ELEMENT requiredname (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT requiredversion (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT file (filename,isdir,data)>\n";
$xmltxt .= "  <!ELEMENT filename (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT isdir (#PCDATA)>\n";
$xmltxt .= "  <!ELEMENT data (#PCDATA)>\n";
$xmltxt .= "]>\n";
$xmltxt .= "<module>\n";

$xmltxt .= "\t<dtdversion>".MODULE_DTD_VERSION."</dtdversion>\n";
$xmltxt .= "\t<name>".$modinstance->GetName()."</name>\n";
$xmltxt .= "\t<version>".$modinstance->GetVersion()."</version>\n";
$xmltxt .= "    <mincmsversion>".$modinstance->MinimumCMSVersion()."</mincmsversion>\n";
$xmltxt .= "\t<help><![CDATA[".base64_encode($modinstance->GetHelpPage())."]]></help>\n";
$xmltxt .= "\t<about><![CDATA[".base64_encode($modinstance->GetAbout())."]]></about>\n";
$desc = $modinstance->GetAdminDescription();
if ($desc != '') $xmltxt .= "\t<description><![CDATA[".$desc."]]></description>\n";

$depends = $modinstance->GetDependencies();
foreach ($depends as $key=>$val) {
    $xmltxt .= "\t<requires>\n";
    $xmltxt .= "\t  <requiredname>$key</requiredname>\n";
    $xmltxt .= "\t  <requiredversion>$val</requiredversion>\n";
    $xmltxt .= "\t</requires>\n";
}

foreach ($files as $file) {
    // strip off the beginning
    if (substr($file,0,strlen($dir)) == $dir) $file = substr($file,strlen($dir));
    if ($file == '') continue;

    $xmltxt .= "\t<file>\n";
    $filespec = $dir.DIRECTORY_SEPARATOR.$file;
    $xmltxt .= "\t  <filename>$file</filename>\n";

    if (@is_dir($filespec)) {
        $xmltxt .= "\t  <isdir>1</isdir>\n";
    }
    else {
        $xmltxt .= "\t  <isdir>0</isdir>\n";
        $data = base64_encode(file_get_contents($filespec));
        $xmltxt .= "\t  <data><![CDATA[".$data."]]></data>\n";
    }

    $xmltxt .= "\t</file>\n";
    ++$filecount;
}
$xmltxt .= "</module>\n";

$message = 'XML package of '.strlen($xmltxt).' bytes created for '.$modinstance->GetName();
$message .= ' including '.$filecount.' files';
unset($CMSMS_GENERATING_XML);

file_put_contents($modinstance->GetName() . "-" . $modinstance->GetVersion() . ".xml", $xmltxt);
echo "✅ XML package created\n";
echo $message."\n";
