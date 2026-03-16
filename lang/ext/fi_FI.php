<?php
$lang['admindescription'] = 'Seuraa ja analysoi PHP-virheitä ja palvelinlokeja käyttäjäystävällisessä käyttöliittymässä';
$lang['ask_uninstall'] = 'Haluatko varmasti poistaa LogWatch -moduulin?';
$lang['custom_error_handler'] = 'Mukautettu virheenkäsittelijä';
$lang['custom_error_handler_desc'] = 'Ota sisäänrakennettu PHP-virheenkäsittelijä käyttöön';
$lang['custom_error_handler_help'] = 'Kaappaa PHP-virheet suoraan ja kirjoittaa ne paikalliseen lokitiedostoon. Hyödyllinen, kun palvelimen virhelokit eivät ole käytettävissä (jaettu isännöinti, rajoitetut käyttöoikeudet).';
$lang['date'] = 'Treffi';
$lang['description'] = 'LogWatch auttaa kehittäjiä vianmäärityksessä PHP-virheitä tarjoamalla puhtaan, järjestetyn näkymän palvelimen virhelokeista. Seuraa kohtalokkaita virheitä, varoituksia, ilmoituksia ja vanhentuneita toimintoja suoraan CMS-järjestelmänvalvojan paneelista.';
$lang['error_hidden'] = 'Virhe merkitty korjaukseksi ja piilotettu lokeista';
$lang['error_hide_failed'] = 'Virheen piilottaminen epäonnistui';
$lang['error_log_file_not_found'] = 'Lokitiedoston virhe!';
$lang['error_log_file_manual'] = 'Manuaalista lokitiedostoa ei voitu ladata: %s';
$lang['error_log_file_selected'] = 'Valittua lokitiedostoa ei voitu ladata: %s';
$lang['error_unhidden'] = 'Virhe palautettu lokinäkymään';
$lang['error_unhide_failed'] = 'Virheen paljastaminen epäonnistui';
$lang['export_csv'] = 'Vie CSV';
$lang['file'] = 'Tiedostot';
$lang['filter_error_types_desc'] = 'Valitse lokinäkymässä näytettävät virhetyypit:';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Linja';
$lang['log_source'] = 'Lokilähde';
$lang['logwatch_pro_status'] = 'LogWatch Pro-tila';
$lang['manual_log_path'] = 'Manuaalinen lokitiedoston polku';
$lang['manual_log_path_desc'] = 'Kirjoita virhelokitiedoston koko palvelinpolku';
$lang['message'] = 'Viesti';
$lang['pro_disabled'] = 'Vammaiset';
$lang['pro_disabled_desc'] = 'Premium-ominaisuudet ovat tällä hetkellä poissa käytöstä';
$lang['pro_enabled'] = 'Käytössä';
$lang['pro_enabled_desc'] = 'Premium-ominaisuudet ovat aktiivisia (ilmoitukset, analytiikka, integraatiot)';
$lang['prompt_go'] = 'Mene';
$lang['prompt_page'] = 'Sivu';
$lang['settings_saved'] = 'Asetukset tallennettiin onnistuneesti';
$lang['tab_filters'] = 'Suodattimet';
$lang['tab_hidden'] = 'Piilotetut virheet';
$lang['tab_logs'] = 'Lokit';
$lang['tab_premium'] = 'palkkio';
$lang['tab_settings'] = 'Asetukset';
$lang['type'] = 'Tyyppi';
$lang['help_general'] = 'Kenraali';
$lang['help_features'] = 'Ominaisuudet';
$lang['help_configuration'] = 'Kokoonpano';
$lang['help_pro_features'] = 'Pro-ominaisuudet';
$lang['help_upgrade'] = 'Päivitä Pro-versioon';
$lang['help_troubleshooting'] = 'Vianmääritys';
$lang['error_log_file_reasons'] = <<<EOT
Tämä voi johtua:
<ul>
 <li>Tiedostoa ei ole määritetyssä paikassa</li>
 <li>Riittämättömät lukuoikeudet verkkopalvelimelle</li>
 <li>Tiedoston polku on virheellinen tai muuttunut</li></ul>
Valitse toinen lokilähde tai tarkista tiedoston käyttöoikeudet.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Lokilähteitä ei havaittu!</strong> <br/>
LogWatch ei tunnistanut automaattisesti mitään luettavissa olevia virhelokitiedostoja palvelimellasi. Tämä voi tapahtua jaetussa isännöinnissä tai kun lokitiedostot ovat epätyypillisissä sijainneissa. <br/>
Käytä alla olevaa manuaalisen lokipolun vaihtoehtoa määrittääksesi virhelokitiedoston täydellisen polun.
EOT;
?>