<?php
$lang['admindescription'] = 'Monitoruj i analizuj błędy PHP i dzienniki serwera w przyjaznym dla użytkownika interfejsie';
$lang['ask_uninstall'] = 'Czy na pewno chcesz odinstalować moduł LogWatch?';
$lang['custom_error_handler'] = 'Niestandardowy moduł obsługi błędów';
$lang['custom_error_handler_desc'] = 'Włącz wbudowany moduł obsługi błędów PHP';
$lang['custom_error_handler_help'] = 'Przechwytuje błędy PHP bezpośrednio i zapisuje je do lokalnego pliku dziennika. Przydatne, gdy dzienniki błędów serwera nie są dostępne (hosting współdzielony, ograniczone uprawnienia).';
$lang['date'] = 'Data';
$lang['description'] = 'LogWatch pomaga programistom rozwiązywać problemy PHP, zapewniając czysty, zorganizowany widok dzienników błędów serwera. Monitoruj błędy krytyczne, ostrzeżenia, powiadomienia i przestarzałe funkcje bezpośrednio z panelu administracyjnego CMS.';
$lang['error_hidden'] = 'Błąd oznaczony jako naprawiony i ukryty w dziennikach';
$lang['error_hide_failed'] = 'Nie udało się ukryć błędu';
$lang['error_log_file_not_found'] = 'Błąd pliku dziennika!';
$lang['error_log_file_manual'] = 'Nie można załadować ręcznego pliku dziennika: %s';
$lang['error_log_file_selected'] = 'Nie można załadować wybranego pliku dziennika: %s';
$lang['error_unhidden'] = 'Błąd przywrócony do widoku dzienników';
$lang['error_unhide_failed'] = 'Nie udało się odkryć błędu';
$lang['export_csv'] = 'Eksportuj CSV';
$lang['file'] = 'Plik';
$lang['filter_error_types_desc'] = 'Wybierz typy błędów, które mają być wyświetlane w widoku dzienników:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Linia';
$lang['log_source'] = 'Źródło dziennika';
$lang['logwatch_pro_status'] = 'LogWatch Status Pro';
$lang['manual_log_path'] = 'Ręczna ścieżka pliku dziennika';
$lang['manual_log_path_desc'] = 'Wprowadź pełną ścieżkę serwera do pliku dziennika błędów';
$lang['message'] = 'Wiadomość';
$lang['pro_disabled'] = 'Niepełnosprawny';
$lang['pro_disabled_desc'] = 'Funkcje premium są obecnie wyłączone';
$lang['pro_enabled'] = 'Włączone';
$lang['pro_enabled_desc'] = 'Funkcje premium są aktywne (powiadomienia, analizy, integracje)';
$lang['prompt_go'] = 'Idź';
$lang['prompt_page'] = 'Strona';
$lang['settings_saved'] = 'Ustawienia zostały pomyślnie zapisane';
$lang['tab_filters'] = 'Filtry';
$lang['tab_hidden'] = 'Ukryte błędy';
$lang['tab_logs'] = 'Dzienniki';
$lang['tab_premium'] = 'Premia';
$lang['tab_settings'] = 'Ustawienia';
$lang['type'] = 'Typ';
$lang['help_general'] = 'Generał';
$lang['help_features'] = 'Funkcje';
$lang['help_configuration'] = 'Konfiguracja';
$lang['help_pro_features'] = 'Funkcje Pro';
$lang['help_upgrade'] = 'Uaktualnij do Pro';
$lang['help_troubleshooting'] = 'Rozwiązywanie problemów';
$lang['error_log_file_reasons'] = <<<EOT
Może to być spowodowane:
<ul>
 <li>Plik nie istnieje w określonej lokalizacji</li>
 <li>Niewystarczające uprawnienia do odczytu dla serwera WWW</li>
 <li>Ścieżka pliku jest nieprawidłowa lub została zmieniona</li></ul>
Wybierz inne źródło dziennika lub sprawdź uprawnienia do plików.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Nie wykryto źródeł dziennika!</strong> <br/>
LogWatch nie może automatycznie wykryć żadnych czytelnych plików dziennika błędów na serwerze. Może się to zdarzyć na hostingu współdzielonym lub gdy pliki dziennika znajdują się w niestandardowych lokalizacjach. <br/>
Użyj opcji ręcznej ścieżki dziennika poniżej, aby określić pełną ścieżkę do pliku dziennika błędów.
EOT;
?>