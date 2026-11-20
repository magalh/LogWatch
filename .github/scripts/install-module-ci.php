<?php
/**
 * Installs a module (and optional dependency modules) into the throwaway CI site, so that
 * cms_utils::get_module() can load it for build_xml.php.
 *
 *   cd <site>/modules/<Module>
 *   php install-module-ci.php <Module> [dep.xml ...]      (dependency XML packages are installed first)
 *
 * Prints "VERSION=<GetVersion()>" on success; exits non-zero on failure.
 */
if (PHP_SAPI !== 'cli') exit(1);
define('CMS_CLI', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);
require_once('../../lib/include.php');

$name = $argv[1] ?? '';
if ($name === '') { fwrite(STDERR, "Usage: php install-module-ci.php Module [dep.xml ...]\n"); exit(1); }
$ops = \ModuleOperations::get_instance();

foreach (array_slice($argv, 2) as $xml) {
    $r = $ops->ExpandXMLPackage($xml, 1, 0);
    if (!is_array($r) || empty($r['name'])) { fwrite(STDERR, "Error: could not expand dependency package $xml\n"); exit(1); }
    $dep = $r['name'];
    list($ok, $msg) = $ops->InstallModule($dep);
    if (!$ok) { fwrite(STDERR, "Error: could not install dependency $dep: $msg\n"); exit(1); }
    echo "Installed dependency $dep\n";
}

list($ok, $msg) = $ops->InstallModule($name);
if (!$ok) { fwrite(STDERR, "Error: could not install module $name: $msg\n"); exit(1); }
$mod = \cms_utils::get_module($name);
if (!$mod) {
    fwrite(STDERR, "Error: module $name installed but could not be loaded (missing dependency? list it in .github/release-deps.txt)\n");
    exit(1);
}
echo 'VERSION=' . $mod->GetVersion() . "\n";
