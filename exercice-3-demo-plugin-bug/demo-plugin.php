<?php
/*
 * Plugin Name: Contrainte de connexion 
 * Plugin URI: 
 * Description: Ce plugin contient un bug, et vous devrez paramétrer le jour de la semaine en fonction du jour où vous faites le test :)
 * Author: Be API
 * Version: 1.0
 * Author URI: 
 */

// A personnaliser selon le jour ou vous effectuez votre test
global $jour_de_la_semaine;
$jour_de_la_semaine = 7;

add_filter( 'authenticate', 'auth_check_mail_extension', 100, 3 );
function auth_check_mail_extension( $user, $username, $password ) {
	if ( $user instanceof WP_User && stripos( $user->user_email, '.fr' ) === false ) {
		return new WP_Error( 'auth_forbidden', "Uniquement des mails .FR autorisés" );
	}

	return $user;
}

add_filter( 'authenticate', 'auth_check_weekday', 105, 3 );
function auth_check_weekday( $user, $username, $password ) {
	global $jour_de_la_semaine;

	if ( $user instanceof WP_User && current_time( 'N' ) == $jour_de_la_semaine ) {
		return new WP_Error( 'auth_day_error', 'Pas de connexion ce jour de la semaine' );
	}

	return $user;
}
