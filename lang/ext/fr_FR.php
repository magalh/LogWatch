<?php
$lang['admindescription'] = 'Surveillez et analysez les erreurs PHP et les journaux du serveur dans une interface conviviale';
$lang['ask_uninstall'] = 'Êtes-vous sûr de vouloir désinstaller le module LogWatch ?';
$lang['custom_error_handler'] = 'Gestionnaire d\'erreurs personnalisé';
$lang['custom_error_handler_desc'] = 'Activer le gestionnaire d\'erreurs PHP intégré';
$lang['custom_error_handler_help'] = 'Capture directement les erreurs PHP et les écrit dans un fichier journal local. Utile lorsque les journaux d\'erreurs du serveur ne sont pas accessibles (hébergement mutualisé, autorisations restreintes).';
$lang['date'] = 'Date';
$lang['description'] = 'LogWatch aide les développeurs à résoudre les erreurs PHP en fournissant une vue claire et organisée des journaux d\'erreurs du serveur. Surveillez les erreurs fatales, les avertissements, les notifications et les fonctions obsolètes directement depuis le panneau d\'administration de votre CMS.';
$lang['error_hidden'] = 'Erreur marquée comme corrigée et masquée dans les journaux';
$lang['error_hide_failed'] = 'Impossible de masquer l\'erreur';
$lang['error_log_file_not_found'] = 'Erreur dans le fichier journal !';
$lang['error_log_file_manual'] = 'Le fichier journal manuel n\'a pas pu être chargé : %s';
$lang['error_log_file_selected'] = 'Le fichier journal sélectionné n\'a pas pu être chargé : %s';
$lang['error_unhidden'] = 'Erreur restaurée dans la vue des journaux';
$lang['error_unhide_failed'] = 'Impossible d\'afficher l\'erreur';
$lang['export_csv'] = 'Exporter au format CSV';
$lang['file'] = 'Dossier';
$lang['filter_error_types_desc'] = 'Sélectionnez les types d\'erreurs à afficher dans la vue des journaux :';
$lang['friendlyname'] = 'LogWatch';
$lang['line'] = 'Ligne';
$lang['log_source'] = 'Source du journal';
$lang['logwatch_pro_status'] = 'Statut Pro LogWatch';
$lang['manual_log_path'] = 'Chemin manuel du fichier journal';
$lang['manual_log_path_desc'] = 'Entrez le chemin complet du serveur vers votre fichier journal des erreurs';
$lang['message'] = 'Message';
$lang['pro_disabled'] = 'Désactivé';
$lang['pro_disabled_desc'] = 'Les fonctionnalités premium sont actuellement désactivées';
$lang['pro_enabled'] = 'Activé';
$lang['pro_enabled_desc'] = 'Les fonctionnalités premium sont actives (notifications, analyses, intégrations)';
$lang['prompt_go'] = 'Va';
$lang['prompt_page'] = 'Page';
$lang['settings_saved'] = 'Paramètres enregistrés avec succès';
$lang['tab_filters'] = 'Filtres';
$lang['tab_hidden'] = 'Erreurs cachées';
$lang['tab_logs'] = 'Journaux';
$lang['tab_premium'] = 'Prime';
$lang['tab_settings'] = 'Réglages';
$lang['type'] = 'Tapez';
$lang['help_general'] = 'Général';
$lang['help_features'] = 'Caractéristiques';
$lang['help_configuration'] = 'Configuration';
$lang['help_pro_features'] = 'Fonctionnalités Pro';
$lang['help_upgrade'] = 'Passez à la version Pro';
$lang['help_troubleshooting'] = 'Résolution des problèmes';
$lang['error_log_file_reasons'] = <<<EOT
Cela peut être dû à :
<ul>
 <li>Le fichier n'existe pas à l'emplacement indiqué</li>
 <li>Autorisations de lecture insuffisantes pour le serveur Web</li>
 <li>Le chemin du fichier est incorrect ou a été modifié</li></ul>
Sélectionnez une autre source de journal ou vérifiez les autorisations du fichier.
EOT;
$lang['error_no_log_sources'] = <<<EOT
<strong>Aucune source de log n'a été détectée !</strong> <br/>
LogWatch n'a pas pu détecter automatiquement les fichiers journaux d'erreurs lisibles sur votre serveur. Cela peut se produire sur un hébergement partagé ou lorsque les fichiers journaux se trouvent dans des emplacements non standard. <br/>
Utilisez l'option de chemin de journalisation manuel ci-dessous pour spécifier le chemin complet de votre fichier journal des erreurs.
EOT;
?>