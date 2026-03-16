<?php
$lang['admindescription'] = 'Monitora e analizza gli errori PHP e i log del server in un\'interfaccia intuitiva';
$lang['ask_uninstall'] = 'Sei sicuro di voler disinstallare il modulo LogWatch?';
$lang['custom_error_handler'] = 'Gestore degli errori personalizzato';
$lang['custom_error_handler_desc'] = 'Abilita il gestore di errori PHP integrato';
$lang['custom_error_handler_help'] = 'Cattura direttamente gli errori PHP e li scrive in un file di registro locale. Utile quando i log degli errori del server non sono accessibili (hosting condiviso, autorizzazioni limitate).';
$lang['date'] = 'Data';
$lang['description'] = 'LogWatch aiuta gli sviluppatori a risolvere gli errori PHP fornendo una visualizzazione pulita e organizzata dei log degli errori del server. Monitora gli errori irreversibili, gli avvisi, le notifiche e le funzioni obsolete direttamente dal pannello di amministrazione del CMS.';
$lang['error_hidden'] = 'Errore contrassegnato come corretto e nascosto dai log';
$lang['error_hide_failed'] = 'Impossibile nascondere l\'errore';
$lang['error_log_file_not_found'] = 'Errore nel file di registro!';
$lang['error_log_file_manual'] = 'Impossibile caricare il file di registro manuale: %s';
$lang['error_log_file_selected'] = 'Il file di registro selezionato non può essere caricato: %s';
$lang['error_unhidden'] = 'Errore ripristinato nella visualizzazione dei registri';
$lang['error_unhide_failed'] = 'Impossibile mostrare l\'errore';
$lang['export_csv'] = 'Esporta CSV';
$lang['file'] = 'File';
$lang['filter_error_types_desc'] = 'Seleziona i tipi di errore da visualizzare nella vista dei log:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Linea';
$lang['log_source'] = 'Sorgente del registro';
$lang['logwatch_pro_status'] = 'LogWatch Stato Pro';
$lang['manual_log_path'] = 'Percorso manuale del file di registro';
$lang['manual_log_path_desc'] = 'Inserisci il percorso completo del server del tuo file di registro degli errori';
$lang['message'] = 'Messaggio';
$lang['pro_disabled'] = 'Disabili';
$lang['pro_disabled_desc'] = 'Le funzionalità premium sono attualmente disattivate';
$lang['pro_enabled'] = 'Abilitato';
$lang['pro_enabled_desc'] = 'Le funzionalità premium sono attive (notifiche, analisi, integrazioni)';
$lang['prompt_go'] = 'Vai';
$lang['prompt_page'] = 'Pagina';
$lang['settings_saved'] = 'Impostazioni salvate con successo';
$lang['tab_filters'] = 'Filtri';
$lang['tab_hidden'] = 'Errori nascosti';
$lang['tab_logs'] = 'Registri';
$lang['tab_premium'] = 'premio';
$lang['tab_settings'] = 'Impostazioni';
$lang['type'] = 'Tipo';
$lang['help_general'] = 'Generale';
$lang['help_features'] = 'Caratteristiche';
$lang['help_configuration'] = 'Configurazione';
$lang['help_pro_features'] = 'Funzionalità Pro';
$lang['help_upgrade'] = 'Esegui l\'upgrade a Pro';
$lang['help_troubleshooting'] = 'Risoluzione dei problemi';
$lang['error_log_file_reasons'] = <<<EOT
Ciò può essere dovuto a:
<ul>
 <li>Il file non esiste nella posizione specificata</li>
 <li>Autorizzazioni di lettura insufficienti per il server Web</li>
 <li>Il percorso del file non è corretto o è stato modificato</li></ul>
Seleziona una fonte di log diversa o controlla le autorizzazioni del file.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Nessuna fonte di log rilevata!</strong> <br/>
LogWatch non è riuscito a rilevare automaticamente alcun file di registro degli errori leggibile sul tuo server. Ciò può accadere sull'hosting condiviso o quando i file di registro si trovano in posizioni non standard. <br/>
Utilizza l'opzione del percorso del registro manuale qui sotto per specificare il percorso completo del file di registro degli errori.
EOT;
?>