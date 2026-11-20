<?php
/**
 * Standalone CMSMS module XML packager (PHP 8.x CLI).
 *
 * Writes the same <module> XML as modules/build/build_xml.php, without a CMSMS install, a database
 * or a core download. The builder gets its metadata from a running site through cms_utils::get_module().
 * This script loads the module main class itself, on a minimal stand-in for CMSModule that only
 * implements what the XML needs. The file list, exclusions, ordering and XML layout are copied from
 * build_xml.php unchanged.
 *
 * Usage:
 *   php build_xml_standalone.php [ModuleName] [moduleDir] [outDir]
 *
 *   ModuleName  default: the single *.module.php in moduleDir
 *   moduleDir   default: current directory (contains <Module>.module.php)
 *   outDir      default: moduleDir. Output file: <Module>-<version>.xml
 *
 * Exit codes:
 *   0  XML written
 *   1  error (bad input, missing module file, unexpected exception)
 *   3  the module needs the CMSMS runtime for metadata (for example MinimumCMSVersion() or a missing core language key).
 *      The XML cannot be produced standalone.
 *   4  README.md is missing; the README is required for standalone module help.
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "build_xml_standalone.php must run from the CLI\n");
    exit(1);
}
if (PHP_VERSION_ID < 80000) {
    fwrite(STDERR, "PHP 8.0 or newer is required\n");
    exit(1);
}

define('MODULE_DTD_VERSION', '1.3');

// Module files start with "if (!defined('CMS_VERSION')) exit;". The value is only a guard. No CMSMS core is loaded.
if (!defined('CMS_VERSION')) {
    define('CMS_VERSION', 'standalone');
}

/** Metadata that needs the real CMSMS runtime. Thrown instead of guessing a value. */
final class XmlStandaloneUnsupported extends RuntimeException
{
}

/** A module README is the standalone source for the package help element. */
final class XmlStandaloneMissingReadme extends RuntimeException
{
}

/** Support data for the stand-in CMSModule: English strings and the module's own lang file. */
final class XmlStandaloneSupport
{
    /** Module directory whose lang/en_US.php the Lang() stand-in reads. */
    public static $moduleDir = '';

    private static $moduleLang = null;

    /**
     * English strings of the CMSMS core admin realm that CMSModule::GetAbout() and GetHelpPage() use.
     * Copied from admin/lang/en_US.php of CMSMS 2.2.24. Any other core key is refused (see coreLang()).
     */
    private static $coreLang = [
        'author'        => 'Author',
        'version'       => 'Version',
        'changehistory' => 'Change History',
        'dependencies'  => 'Dependencies',
        'parameters'    => 'Parameters',
    ];

    public static function coreLang($key)
    {
        if (!isset(self::$coreLang[$key])) {
            throw new XmlStandaloneUnsupported("core language string '$key' is not bundled in the standalone packager");
        }
        return self::$coreLang[$key];
    }

    /** Same lookup as CmsLangOperations::lang_from_realm() for a module realm, default language en_US. */
    public static function moduleLang($key, array $params)
    {
        if (self::$moduleLang === null) {
            $lang = [];
            $fn = self::$moduleDir . '/lang/en_US.php';
            if (is_file($fn)) {
                include $fn;
            }
            self::$moduleLang = $lang;
        }
        if (!isset(self::$moduleLang[$key])) {
            return "-- Missing Languagestring: $key --";
        }
        $str = self::$moduleLang[$key];
        return count($params) ? vsprintf($str, $params) : $str;
    }
}

function lang($key)
{
    return XmlStandaloneSupport::coreLang($key);
}

/**
 * Stand-in for the CMSMS CMSModule base class. Method bodies follow lib/classes/class.CMSModule.php and
 * lib/classes/internal/module_support/modmisc.inc.php (CMSMS 2.2.24) where the XML depends on them.
 */
abstract class CMSModule
{
    protected $params = [];

    public function GetName()
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    public function GetFriendlyName()
    {
        return $this->GetName();
    }

    abstract public function GetVersion();

    public function MinimumCMSVersion()
    {
        // The core default is the CMSMS version of the site, which the standalone packager does not have.
        throw new XmlStandaloneUnsupported(get_class($this) . ' does not declare MinimumCMSVersion()');
    }

    public function GetAdminDescription()
    {
        return '';
    }

    public function IsPluginModule()
    {
        return false;
    }

    public function GetChangeLog()
    {
        return '';
    }

    public function GetAuthor()
    {
        return '';
    }

    public function GetAuthorEmail()
    {
        return '';
    }

    public function GetDependencies()
    {
        return [];
    }

    /** Copy of CMSModule::GetParameters(). Parameters come from InitializeAdmin() when none are set yet. */
    public function GetParameters()
    {
        if (count($this->params) == 0) {
            $this->InitializeAdmin();
        }
        return $this->params;
    }

    public function InitializeAdmin()
    {
    }

    /** Copy of CMSModule::CreateParameter(). */
    final public function CreateParameter($param, $defaultval = '', $helpstring = '', $optional = true)
    {
        array_push($this->params, ['name' => $param, 'default' => $defaultval, 'help' => $helpstring,
                                   'optional' => $optional]);
    }

    public function GetHelp()
    {
        return '';
    }

    /** Module lang lookup. Default language only (en_US), as the builder writes it. */
    public function Lang()
    {
        $args = func_get_args();
        $key = array_shift($args);
        if (count($args) == 1 && is_array($args[0])) {
            $args = $args[0];
        }
        return XmlStandaloneSupport::moduleLang($key, $args);
    }

    /** Copy of CMSModule::GetAbout() plus cms_module_GetAbout(). */
    public function GetAbout()
    {
        $str = '';
        if ($this->GetAuthor() != '') {
            $str .= "<br />" . lang('author') . ": " . $this->GetAuthor();
            if ($this->GetAuthorEmail() != '') {
                $str .= ' &lt;' . $this->GetAuthorEmail() . '&gt;';
            }
            $str .= "<br />";
        }
        $str .= "<br />" . lang('version') . ": " . $this->GetVersion() . "<br />";

        if ($this->GetChangeLog() != '') {
            $str .= "<br />" . lang('changehistory') . ":<br />";
            $str .= $this->GetChangeLog() . '<br />';
        }
        return $str;
    }

    /** Copy of CMSModule::GetHelpPage() plus cms_module_GetHelpPage(). */
    final public function GetHelpPage()
    {
        $str = '';
        @ob_start();
        echo $this->GetHelp();
        $str .= @ob_get_contents();
        @ob_end_clean();

        $dependencies = $this->GetDependencies();
        if (count($dependencies) > 0) {
            $str .= '<h3>' . lang('dependencies') . '</h3>';
            $str .= '<ul>';
            foreach ($dependencies as $dep => $ver) {
                $str .= '<li>';
                $str .= $dep . ' =&gt; ' . $ver;
                $str .= '</li>';
            }
            $str .= '</ul>';
        }
        $paramarray = $this->GetParameters();
        if (count($paramarray) > 0) {
            $str .= '<h3>' . lang('parameters') . '</h3>';
            $str .= '<ul>';
            foreach ($paramarray as $oneparam) {
                $str .= '<li>';
                $help = '';
                if ($oneparam['optional'] == true) {
                    $str .= '<em>(optional)</em> ';
                }
                if (isset($oneparam['help'])) {
                    $help = $oneparam['help'];
                }
                $str .= $oneparam['name'] . '="' . $oneparam['default'] . '" - ' . $help . '</li>';
            }
            $str .= '</ul>';
        }
        return $str;
    }
}

/* ---------------------------------------------------------------------------------------------------------
 * Copied from build_xml.php and get_recursive_file_list() in lib/misc.functions.php (CMSMS 2.2.24).
 * Do not change these without changing the builder too: the output must stay byte-identical.
 * ------------------------------------------------------------------------------------------------------- */

function get_recursive_file_list($path, $excludes, $maxdepth = -1, $mode = "FULL", $d = 0)
{
    $fn = function ($file, $excludes) {
        // strip the path from the file
        if (empty($excludes)) {
            return false;
        }
        foreach ($excludes as $excl) {
            if (@preg_match("/" . $excl . "/i", basename($file))) {
                return true;
            }
        }
        return false;
    };

    if (substr($path, strlen($path) - 1) != '/') {
        $path .= '/';
    }
    $dirlist = array();
    if ($mode != "FILES") {
        $dirlist[] = $path;
    }
    if ($handle = opendir($path)) {
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            if ($fn($file, $excludes)) {
                continue;
            }

            $file = $path . $file;
            if (!@is_dir($file)) {
                if ($mode != "DIRS") {
                    $dirlist[] = $file;
                }
            } elseif ($d >= 0 && ($d < $maxdepth || $maxdepth < 0)) {
                $result = get_recursive_file_list($file . '/', $excludes, $maxdepth, $mode, $d + 1);
                $dirlist = array_merge($dirlist, $result);
            }
        }
        closedir($handle);
    }
    if ($d == 0) {
        natcasesort($dirlist);
    }
    return $dirlist;
}

function markdown_escape($text)
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Render the supported Markdown inline syntax. All unrecognised text is escaped
 * before the generated HTML tokens are restored.
 */
function markdown_inline($text, $depth = 0)
{
    if ($depth > 8) {
        return markdown_escape($text);
    }

    $tokens = [];
    $token = static function ($html) use (&$tokens) {
        $index = count($tokens);
        $tokens[] = $html;
        return "\x00CMSMSMDTOKEN{$index}\x00";
    };

    $text = preg_replace_callback('/`([^`\r\n]+)`/', static function ($match) use ($token) {
        return $token('<code>' . markdown_escape($match[1]) . '</code>');
    }, $text);

    $text = preg_replace_callback('/\[([^]\r\n]+)\]\(([^()\s]+)\)/', static function ($match) use ($token) {
        $url = $match[2];
        if (preg_match('/^(?:javascript|data|vbscript):/i', $url)) {
            return $match[0];
        }
        return $token('<a href="' . markdown_escape($url) . '">' . markdown_escape($match[1]) . '</a>');
    }, $text);

    $text = preg_replace_callback('/(\*\*|__)(.+?)\1/', static function ($match) use ($token, $depth) {
        return $token('<strong>' . markdown_inline($match[2], $depth + 1) . '</strong>');
    }, $text);

    $text = preg_replace_callback('/(?<!\*)\*([^*\r\n]+?)\*(?!\*)/', static function ($match) use ($token, $depth) {
        return $token('<em>' . markdown_inline($match[1], $depth + 1) . '</em>');
    }, $text);

    $text = preg_replace_callback('/(?<!_)_([^_\r\n]+?)_(?!_)/', static function ($match) use ($token, $depth) {
        return $token('<em>' . markdown_inline($match[1], $depth + 1) . '</em>');
    }, $text);

    $result = markdown_escape($text);
    $tokenPattern = '/\x00CMSMSMDTOKEN(\d+)\x00/';
    for ($pass = 0; $pass <= count($tokens); ++$pass) {
        $next = preg_replace_callback($tokenPattern, static function ($match) use (&$tokens) {
            return $tokens[(int) $match[1]];
        }, $result);
        if ($next === $result) {
            break;
        }
        $result = $next;
    }
    return $result;
}

/** Convert the supported, deterministic Markdown subset to an escaped HTML fragment. */
function markdown_to_html($markdown)
{
    $markdown = str_replace(["\r\n", "\r"], "\n", $markdown);
    $lines = explode("\n", $markdown);
    $html = '';
    $count = count($lines);
    $i = 0;

    while ($i < $count) {
        $line = $lines[$i];
        if (trim($line) === '') {
            ++$i;
            continue;
        }

        if (preg_match('/^ {0,3}```(?:[^`\r\n]*)$/', $line)) {
            ++$i;
            $code = [];
            while ($i < $count && !preg_match('/^ {0,3}```\s*$/', $lines[$i])) {
                $code[] = $lines[$i];
                ++$i;
            }
            if ($i < $count) {
                ++$i;
            }
            $html .= '<pre><code>' . markdown_escape(implode("\n", $code)) . '</code></pre>' . "\n";
            continue;
        }

        if (preg_match('/^ {0,3}(#{1,6})(?:[ \t]+(.*)|[ \t]*)$/', $line, $match)) {
            $level = strlen($match[1]);
            $content = isset($match[2]) ? rtrim($match[2]) : '';
            $content = preg_replace('/[ \t]+#+[ \t]*$/', '', $content);
            $html .= '<h' . $level . '>' . markdown_inline($content) . '</h' . $level . '>' . "\n";
            ++$i;
            continue;
        }

        if (preg_match('/^ {0,3}(?:(?:\* *){3,}|(?:- *){3,}|(?:_ *){3,})$/', $line)) {
            $html .= '<hr />' . "\n";
            ++$i;
            continue;
        }

        if (preg_match('/^ {0,3}[-+*][ \t]+(.+)$/', $line, $match)) {
            $html .= '<ul>' . "\n";
            while ($i < $count && preg_match('/^ {0,3}[-+*][ \t]+(.+)$/', $lines[$i], $item)) {
                $html .= '<li>' . markdown_inline($item[1]) . '</li>' . "\n";
                ++$i;
            }
            $html .= '</ul>' . "\n";
            continue;
        }

        if (preg_match('/^ {0,3}\d+[.)][ \t]+(.+)$/', $line, $match)) {
            $html .= '<ol>' . "\n";
            while ($i < $count && preg_match('/^ {0,3}\d+[.)][ \t]+(.+)$/', $lines[$i], $item)) {
                $html .= '<li>' . markdown_inline($item[1]) . '</li>' . "\n";
                ++$i;
            }
            $html .= '</ol>' . "\n";
            continue;
        }

        $paragraph = [$line];
        ++$i;
        while ($i < $count && trim($lines[$i]) !== '') {
            $next = $lines[$i];
            $isBlock = preg_match('/^ {0,3}```(?:[^`\r\n]*)$/', $next)
                || preg_match('/^ {0,3}(#{1,6})(?:[ \t]+(.*)|[ \t]*)$/', $next)
                || preg_match('/^ {0,3}(?:(?:\* *){3,}|(?:- *){3,}|(?:_ *){3,})$/', $next)
                || preg_match('/^ {0,3}[-+*][ \t]+(.+)$/', $next)
                || preg_match('/^ {0,3}\d+[.)][ \t]+(.+)$/', $next);
            if ($isBlock) {
                break;
            }
            $paragraph[] = $next;
            ++$i;
        }
        $html .= '<p>' . markdown_inline(implode("\n", $paragraph)) . '</p>' . "\n";
    }

    return $html;
}

function read_module_readme($moduleDir)
{
    $readme = $moduleDir . '/README.md';
    if (!is_file($readme)) {
        throw new XmlStandaloneMissingReadme('README.md is required for the module help (none found in ' . $moduleDir . ')');
    }
    $contents = file_get_contents($readme);
    if ($contents === false) {
        throw new RuntimeException('Unable to read ' . $readme);
    }
    if (preg_match('//u', $contents) !== 1) {
        throw new RuntimeException($readme . ' is not valid UTF-8');
    }
    return $contents;
}

/** Returns [xml string, number of entries]. */
function build_module_xml($modinstance, $dir, $moduleName)
{
    $moduleDir = $dir;

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

    global $CMSMS_GENERATING_XML;
    $CMSMS_GENERATING_XML = 1;

    $filecount = 0;

    $files = get_recursive_file_list($dir, $xml_exclude_files);

    // Filter out path-based exclusions
    if (!empty($xml_exclude_paths)) {
        $files = array_filter($files, function ($file) use ($dir, $xml_exclude_paths) {
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
    $files = array_filter($files, function ($file) use ($dir) {
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

    $xmltxt .= "\t<dtdversion>" . MODULE_DTD_VERSION . "</dtdversion>\n";
    $xmltxt .= "\t<name>" . $modinstance->GetName() . "</name>\n";
    $xmltxt .= "\t<version>" . $modinstance->GetVersion() . "</version>\n";
    $xmltxt .= "    <mincmsversion>" . $modinstance->MinimumCMSVersion() . "</mincmsversion>\n";
    $readmeHtml = markdown_to_html(read_module_readme($dir));

    $xmltxt .= "\t<help><![CDATA[" . base64_encode($readmeHtml) . "]]></help>\n";
    $xmltxt .= "\t<about><![CDATA[" . base64_encode($modinstance->GetAbout()) . "]]></about>\n";
    $desc = $modinstance->GetAdminDescription();
    if ($desc != '') $xmltxt .= "\t<description><![CDATA[" . $desc . "]]></description>\n";

    $depends = $modinstance->GetDependencies();
    foreach ($depends as $key => $val) {
        $xmltxt .= "\t<requires>\n";
        $xmltxt .= "\t  <requiredname>$key</requiredname>\n";
        $xmltxt .= "\t  <requiredversion>$val</requiredversion>\n";
        $xmltxt .= "\t</requires>\n";
    }

    foreach ($files as $file) {
        // strip off the beginning
        if (substr($file, 0, strlen($dir)) == $dir) $file = substr($file, strlen($dir));
        if ($file == '') continue;

        $xmltxt .= "\t<file>\n";
        $filespec = $dir . DIRECTORY_SEPARATOR . $file;
        $xmltxt .= "\t  <filename>$file</filename>\n";

        if (@is_dir($filespec)) {
            $xmltxt .= "\t  <isdir>1</isdir>\n";
        } else {
            $xmltxt .= "\t  <isdir>0</isdir>\n";
            $data = base64_encode(file_get_contents($filespec));
            $xmltxt .= "\t  <data><![CDATA[" . $data . "]]></data>\n";
        }

        $xmltxt .= "\t</file>\n";
        ++$filecount;
    }
    $xmltxt .= "</module>\n";

    unset($CMSMS_GENERATING_XML);
    return [$xmltxt, $filecount];
}

/* ---------------------------------------------------------------------------------------------------------
 * Entry point
 * ------------------------------------------------------------------------------------------------------- */

function xml_standalone_run(array $argv)
{
    $moduleName = $argv[1] ?? '';
    $moduleDir = $argv[2] ?? getcwd();
    $outDir = $argv[3] ?? null;

    if (($real = realpath($moduleDir)) === false || !is_dir($real)) {
        fwrite(STDERR, "Error: module directory not found: $moduleDir\n");
        return 1;
    }
    chdir($real);
    $dir = getcwd(); // same value the builder uses as its module directory
    if (!is_file($dir . '/README.md')) {
        throw new XmlStandaloneMissingReadme('README.md is required for the module help (none found in ' . $dir . ')');
    }

    if ($moduleName === '') {
        $candidates = glob($dir . '/*.module.php');
        if (count($candidates) !== 1) {
            fwrite(STDERR, "Error: expected exactly one *.module.php in $dir, found " . count($candidates) . "\n");
            return 1;
        }
        $moduleName = basename($candidates[0], '.module.php');
    }

    $file = $dir . '/' . $moduleName . '.module.php';
    if (!is_file($file)) {
        fwrite(STDERR, "Error: $file not found\n");
        return 1;
    }

    XmlStandaloneSupport::$moduleDir = $dir;

    // Module files check for $gCms (CMSMS app object) in CMSMailer and similar. Only the guard is set.
    $gCms = new stdClass();
    require $file;

    if (!class_exists($moduleName, false) || !is_subclass_of($moduleName, 'CMSModule')) {
        fwrite(STDERR, "Error: $file does not declare class $moduleName extending CMSModule\n");
        return 1;
    }
    // No constructor: it needs the CMSMS runtime (database, Smarty, cms_utils). Metadata methods do not need it.
    $modinstance = (new ReflectionClass($moduleName))->newInstanceWithoutConstructor();

    [$xmltxt, $filecount] = build_module_xml($modinstance, $dir, $moduleName);

    $outDir = $outDir ?? $dir;
    if (!is_dir($outDir) && !mkdir($outDir, 0777, true)) {
        fwrite(STDERR, "Error: cannot create output directory $outDir\n");
        return 1;
    }
    $outFile = rtrim($outDir, '/\\') . '/' . $modinstance->GetName() . '-' . $modinstance->GetVersion() . '.xml';
    if (file_put_contents($outFile, $xmltxt) === false) {
        fwrite(STDERR, "Error: cannot write $outFile\n");
        return 1;
    }

    echo 'XML package of ' . strlen($xmltxt) . ' bytes created for ' . $modinstance->GetName();
    echo ' including ' . $filecount . ' files' . "\n";
    echo $outFile . "\n";
    return 0;
}

try {
    exit(xml_standalone_run($argv));
} catch (XmlStandaloneMissingReadme $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(4);
} catch (XmlStandaloneUnsupported $e) {
    fwrite(STDERR, 'Standalone packager cannot build this module: ' . $e->getMessage() . "\n");
    exit(3);
} catch (Error $e) {
    // Undefined CMSMS API (for example ProcessTemplate, ModuleOperations or CmsApp) called from a metadata method.
    fwrite(STDERR, 'Standalone packager stopped on ' . get_class($e) . ': ' . $e->getMessage() . "\n");
    fwrite(STDERR, "The module probably needs the CMSMS runtime. This repo has no CMSMS fallback.\n");
    exit(3);
} catch (Throwable $e) {
    fwrite(STDERR, 'Error: ' . get_class($e) . ': ' . $e->getMessage() . "\n");
    exit(1);
}
