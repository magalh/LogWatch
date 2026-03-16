<?php
$lang['admindescription'] = 'Overvåg og analyser PHP-fejl og serverlogfiler i en brugervenlig grænseflade';
$lang['ask_uninstall'] = 'Er du sikker på, at du vil afinstallere LogWatch modulet?';
$lang['custom_error_handler'] = 'Brugerdefineret fejlhåndtering';
$lang['custom_error_handler_desc'] = 'Aktivér indbygget PHP-fejlbehandler';
$lang['custom_error_handler_help'] = 'Fanger PHP-fejl direkte og skriver dem til en lokal logfil. Nyttigt, når serverfejllogfiler ikke er tilgængelige (delt hosting, begrænsede tilladelser).';
$lang['date'] = 'Dato';
$lang['description'] = 'LogWatch hjælper udviklere med at fejlfinde PHP-fejl ved at give en ren, organiseret visning af serverfejllogfiler. Overvåg fatale fejl, advarsler, meddelelser og forældede funktioner direkte fra dit CMS admin panel.';
$lang['error_hidden'] = 'Fejl markeret som fast og skjult fra logfiler';
$lang['error_hide_failed'] = 'Fejl kunne ikke skjules';
$lang['error_log_file_not_found'] = 'Logfilfejl!';
$lang['error_log_file_manual'] = 'Den manuelle logfil kunne ikke indlæses: %s';
$lang['error_log_file_selected'] = 'Den valgte logfil kunne ikke indlæses: %s';
$lang['error_unhidden'] = 'Fejl gendannet til logvisning';
$lang['error_unhide_failed'] = 'Fejl kunne ikke vises';
$lang['export_csv'] = 'Eksporter CSV';
$lang['file'] = 'fil';
$lang['filter_error_types_desc'] = 'Vælg, hvilke fejltyper der skal vises i logfilvisningen:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Linje';
$lang['log_source'] = 'Logkilde';
$lang['logwatch_pro_status'] = 'LogWatch Pro-status';
$lang['manual_log_path'] = 'Manuel logfilsti';
$lang['manual_log_path_desc'] = 'Indtast den fulde serversti til din fejllogfil';
$lang['message'] = 'Besked';
$lang['pro_disabled'] = 'Handicappede';
$lang['pro_disabled_desc'] = 'Premium-funktioner er i øjeblikket deaktiveret';
$lang['pro_enabled'] = 'Aktiveret';
$lang['pro_enabled_desc'] = 'Premium-funktioner er aktive (underretninger, analyser, integrationer)';
$lang['prompt_go'] = 'Gå';
$lang['prompt_page'] = 'Side';
$lang['settings_saved'] = 'Indstillinger gemt med succes';
$lang['tab_filters'] = 'Filtre';
$lang['tab_hidden'] = 'Skjulte fejl';
$lang['tab_logs'] = 'Logfiler';
$lang['tab_premium'] = 'Præmie';
$lang['tab_settings'] = 'Indstillinger';
$lang['type'] = 'Type';
$lang['help_general'] = 'Generel';
$lang['help_features'] = 'Funktioner';
$lang['help_configuration'] = 'Konfiguration';
$lang['help_pro_features'] = 'Pro-funktioner';
$lang['help_upgrade'] = 'Opgrader til Pro';
$lang['help_troubleshooting'] = 'Fejlfinding';
$lang['error_log_file_reasons'] = <<<EOT
Dette kan skyldes:
<ul>
 <li>Filen findes ikke på det angivne sted</li>
 <li>Utilstrækkelige læsetilladelser til webserveren</li>
 <li>Filstien er forkert eller er ændret</li></ul>
Vælg en anden logkilde, eller tjek filtilladelserne.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Ingen logkilder fundet!</strong> <br/>
LogWatch kunne ikke automatisk registrere nogen læsbare fejllogfiler på din server. Dette kan ske på delt hosting, eller når logfiler er på ikke-standardiserede placeringer. <br/>
Brug den manuelle logstiindstilling nedenfor til at angive den fulde sti til din fejllogfil.
EOT;
?>