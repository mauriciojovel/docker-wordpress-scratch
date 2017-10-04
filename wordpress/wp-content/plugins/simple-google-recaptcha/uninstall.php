<?php
if (!defined("WP_UNINSTALL_PLUGIN")) {
	exit();
}

function sgr_delete() {
	global $wpdb;
	delete_option("sgr_site_key");
	delete_option("sgr_secret_key");
}

sgr_delete();
?>