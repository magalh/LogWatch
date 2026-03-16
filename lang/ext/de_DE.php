<?php
$lang['admindescription'] = 'Überwachen und analysieren Sie PHP-Fehler und Serverprotokolle in einer benutzerfreundlichen Oberfläche';
$lang['ask_uninstall'] = 'Möchten Sie das Modul LogWatch wirklich deinstallieren?';
$lang['custom_error_handler'] = 'Benutzerdefinierter Fehlerhandler';
$lang['custom_error_handler_desc'] = 'Aktiviere den integrierten PHP-Fehlerhandler';
$lang['custom_error_handler_help'] = 'Erfasst PHP-Fehler direkt und schreibt sie in eine lokale Logdatei. Nützlich, wenn auf Serverfehlerprotokolle nicht zugegriffen werden kann (Shared Hosting, eingeschränkte Berechtigungen).';
$lang['date'] = 'Datum';
$lang['description'] = 'LogWatch hilft Entwicklern bei der Behebung von PHP-Fehlern, indem es eine saubere, organisierte Ansicht der Serverfehlerprotokolle bietet. Überwachen Sie schwerwiegende Fehler, Warnungen, Hinweise und veraltete Funktionen direkt von Ihrem CMS-Verwaltungsbereich aus.';
$lang['error_hidden'] = 'Fehler als behoben markiert und in den Protokollen versteckt';
$lang['error_hide_failed'] = 'Fehler konnte nicht ausgeblendet werden';
$lang['error_log_file_not_found'] = 'Logdateifehler!';
$lang['error_log_file_manual'] = 'Die manuelle Protokolldatei konnte nicht geladen werden: %s';
$lang['error_log_file_selected'] = 'Die gewählte Logdatei konnte nicht geladen werden: %s';
$lang['error_unhidden'] = 'Fehler in der Protokollansicht wiederhergestellt';
$lang['error_unhide_failed'] = 'Fehler konnte nicht eingeblendet werden';
$lang['export_csv'] = 'CSV exportieren';
$lang['file'] = 'Datei';
$lang['filter_error_types_desc'] = 'Wählen Sie aus, welche Fehlertypen in der Protokollansicht angezeigt werden sollen:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Linie';
$lang['log_source'] = 'Log-Quelle';
$lang['logwatch_pro_status'] = 'LogWatch Profi-Status';
$lang['manual_log_path'] = 'Manueller Protokolldateipfad';
$lang['manual_log_path_desc'] = 'Geben Sie den vollständigen Serverpfad zu Ihrer Fehlerprotokolldatei ein';
$lang['message'] = 'Nachricht';
$lang['pro_disabled'] = 'Deaktiviert';
$lang['pro_disabled_desc'] = 'Premium-Funktionen sind derzeit deaktiviert';
$lang['pro_enabled'] = 'Aktiviert';
$lang['pro_enabled_desc'] = 'Premium-Funktionen sind aktiv (Benachrichtigungen, Analysen, Integrationen)';
$lang['prompt_go'] = 'Geh';
$lang['prompt_page'] = 'Seite';
$lang['settings_saved'] = 'Einstellungen wurden erfolgreich gespeichert';
$lang['tab_filters'] = 'Filter';
$lang['tab_hidden'] = 'Versteckte Fehler';
$lang['tab_logs'] = 'Logs';
$lang['tab_premium'] = 'Prämie';
$lang['tab_settings'] = 'Einstellungen';
$lang['type'] = 'Typ';
$lang['help_general'] = 'Allgemeines';
$lang['help_features'] = 'Funktionen';
$lang['help_configuration'] = 'Konfiguration';
$lang['help_pro_features'] = 'Profi-Funktionen';
$lang['help_upgrade'] = 'Auf Pro upgraden';
$lang['help_troubleshooting'] = 'Problembehebung';
$lang['error_log_file_reasons'] = <<<EOT
Dies kann folgende Ursachen haben:
<ul>
 <li>Die Datei ist am angegebenen Speicherort nicht vorhanden</li>
 <li>Ungenügende Leseberechtigungen für den Webserver</li>
 <li>Der Dateipfad ist falsch oder hat sich geändert</li></ul>
Bitte wählen Sie eine andere Protokollquelle oder überprüfen Sie die Dateiberechtigungen.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Keine Log-Quellen gefunden!</strong> <br/>
LogWatch konnte keine lesbaren Fehlerprotokolldateien auf Ihrem Server automatisch erkennen. Dies kann bei Shared Hosting oder wenn sich Protokolldateien an nicht standardmäßigen Speicherorten befinden, der Fall sein. <br/>
Bitte verwenden Sie die unten stehende Option für den manuellen Protokollpfad, um den vollständigen Pfad zu Ihrer Fehlerprotokolldatei anzugeben.
EOT;
?>