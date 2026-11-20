<?php
/**
 * Runs the unmodified local builder (build_xml.php) inside the throwaway CI site.
 *
 *   cd <site>/modules/<Module>
 *   php run-build-xml.php <Module>
 *
 * One state difference is normalised first. On a real dev site some installed module always enables
 * CmsLangOperations::allow_nonadmin_lang() while the modules are loaded at bootstrap (here it is true on
 * cmsmadesimple.local, false on a fresh site). With it off, core lang('author'/'version'/'changehistory')
 * returns '' outside the admin, so the <about> block loses its labels. Turning it on reproduces what the
 * local builder writes.
 */
if (PHP_SAPI !== 'cli') exit(1);
define('CMS_CLI', 1);
require_once('../../lib/include.php');
\CmsLangOperations::allow_nonadmin_lang(true);
$GLOBALS['argv'] = [__DIR__ . '/build_xml.php', $argv[1] ?? ''];
require __DIR__ . '/build_xml.php';
