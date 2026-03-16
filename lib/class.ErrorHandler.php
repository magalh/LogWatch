<?php
#---------------------------------------------------------------------------------------------------
# Module: LogWatch
# Authors: Magal Hezi, with CMS Made Simple Foundation.
# Copyright: (C) 2025 Pixel Solutions, info@pixelsolutions.biz
# License: GNU General Public License version 2
#---------------------------------------------------------------------------------------------------

/**
 * Custom Error Handler - Creates a local log file when system logs aren't accessible.
 * Registers set_error_handler() and register_shutdown_function() to capture all PHP errors
 * and write them in standard PHP error log format for the existing parsers to read.
 */
class ErrorHandler
{
    private static $log_file = null;
    private static $handler_registered = false;
    private static $handling = false;

    /**
     * Initialize the error handler.
     * Accepts the module instance to avoid calling cms_utils::get_module() during init.
     *
     * @param CMSModule $mod The LogWatch module instance
     */
    public static function init($mod)
    {
        if (self::$handler_registered) {
            return;
        }

        self::$log_file = self::getLogFilePath($mod);

        if (self::$log_file && is_writable(dirname(self::$log_file))) {
            set_error_handler([__CLASS__, 'handleError']);
            register_shutdown_function([__CLASS__, 'handleFatalError']);
            self::$handler_registered = true;
        }
    }

    /**
     * Get or create the log file path inside CMS tmp directory
     */
    private static function getLogFilePath($mod)
    {
        // Check if a custom path was previously stored
        $custom_log = $mod->GetPreference('custom_log_path', '');
        if ($custom_log && file_exists($custom_log) && is_writable($custom_log)) {
            return $custom_log;
        }

        // Build log directory from cms_config
        $config = cms_config::get_instance();
        $tmp_path = isset($config['tmp_path']) ? $config['tmp_path'] : '';
        if (empty($tmp_path) && defined('TMP_CACHE_LOCATION')) {
            $tmp_path = TMP_CACHE_LOCATION;
        }
        if (empty($tmp_path)) {
            return null;
        }

        $log_dir = cms_join_path($tmp_path, 'cache', 'logwatch');

        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0755, true);

            $htaccess = cms_join_path($log_dir, '.htaccess');
            if (!file_exists($htaccess)) {
                @file_put_contents($htaccess, "Deny from all\n");
            }

            $index = cms_join_path($log_dir, 'index.html');
            if (!file_exists($index)) {
                @file_put_contents($index, '');
            }
        }

        $log_file = cms_join_path($log_dir, 'php-errors.log');

        if (!file_exists($log_file)) {
            @file_put_contents($log_file, '');
        }

        if (is_writable($log_file)) {
            $mod->SetPreference('custom_log_path', $log_file);
            return $log_file;
        }

        return null;
    }

    /**
     * Handle PHP errors (warnings, notices, deprecated, etc.)
     */
    public static function handleError($errno, $errstr, $errfile, $errline)
    {
        // Re-entrancy guard
        if (self::$handling) {
            return false;
        }

        if (!(error_reporting() & $errno)) {
            return false;
        }

        self::$handling = true;

        $type = self::getErrorType($errno);
        $timestamp = gmdate('d-M-Y H:i:s') . ' UTC';

        $entry = sprintf(
            "[%s] PHP %s: %s in %s on line %d\n",
            $timestamp,
            $type,
            str_replace(array("\n", "\r"), ' ', $errstr),
            $errfile,
            $errline
        );

        if (self::$log_file) {
            @file_put_contents(self::$log_file, $entry, FILE_APPEND | LOCK_EX);
        }

        self::$handling = false;

        // Don't prevent the default error handler
        return false;
    }

    /**
     * Handle fatal errors via shutdown function
     */
    public static function handleFatalError()
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $type = self::getErrorType($error['type']);
            $timestamp = gmdate('d-M-Y H:i:s') . ' UTC';

            $entry = sprintf(
                "[%s] PHP %s: %s in %s on line %d\n",
                $timestamp,
                $type,
                str_replace(array("\n", "\r"), ' ', $error['message']),
                $error['file'],
                $error['line']
            );

            if (self::$log_file) {
                @file_put_contents(self::$log_file, $entry, FILE_APPEND | LOCK_EX);
            }
        }
    }

    /**
     * Map PHP error constants to human-readable type strings
     */
    private static function getErrorType($errno)
    {
        $types = [
            E_ERROR             => 'Fatal error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parse error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Fatal error',
            E_CORE_WARNING      => 'Warning',
            E_COMPILE_ERROR     => 'Fatal error',
            E_COMPILE_WARNING   => 'Warning',
            E_USER_ERROR        => 'Fatal error',
            E_USER_WARNING      => 'Warning',
            E_USER_NOTICE       => 'Notice',
            E_STRICT            => 'Notice',
            E_RECOVERABLE_ERROR => 'Fatal error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'Deprecated',
        ];

        return isset($types[$errno]) ? $types[$errno] : 'Error';
    }

    /**
     * Check if the custom error handler is active
     */
    public static function isActive()
    {
        return self::$handler_registered;
    }

    /**
     * Get the custom log file path (safe to call anytime)
     */
    public static function getLogPath()
    {
        if (self::$log_file) {
            return self::$log_file;
        }

        $mod = cms_utils::get_module('LogWatch');
        return $mod ? $mod->GetPreference('custom_log_path', '') : '';
    }
}
?>
