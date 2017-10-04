<?php
/*
* Plugin Name: Simple Google reCAPTCHA
* Description: Simply protect your WordPress against spam comments and brute-force attacks, thanks to Google reCAPTCHA!
* Version: 2.7
* Author: Michal Nov&aacute;k
* Author URI: https://www.novami.cz
* License: GPL3
* Text Domain: simple-google-recaptcha
* Domain Path: /languages
*/

function sgr_add_plugin_action_links($links) {
	return array_merge(array("settings" => "<a href=\"options-general.php?page=sgr-options\">".__("Settings", "simple-google-recaptcha")."</a>"), $links);
}
add_filter("plugin_action_links_".plugin_basename(__FILE__), "sgr_add_plugin_action_links");

function sgr_activation($plugin) {
	if ($plugin == plugin_basename(__FILE__) && (get_option("sgr_site_key") == "" || get_option("sgr_secret_key") == "")) {
		exit(wp_redirect(admin_url("options-general.php?page=sgr-options")));
	}
}
add_action("activated_plugin", "sgr_activation");

function sgr_options_page() {
	echo "<div class=\"wrap\">
	<h1>".__("Simple Google reCAPTCHA Options", "simple-google-recaptcha")."</h1>
	<form method=\"post\" action=\"options.php\">";
	settings_fields("sgr_header_section");
	do_settings_sections("sgr-options");
	submit_button();
	echo "</form>
	</div>";
}

function sgr_menu() {
	add_submenu_page("options-general.php", "reCAPTCHA", "reCAPTCHA", "manage_options", "sgr-options", "sgr_options_page");
}
add_action("admin_menu", "sgr_menu");

function sgr_display_content() {
	echo "<p>".__("You have to <a href=\"https://www.google.com/recaptcha/admin\" rel=\"external\">register your domain</a> first, get required keys (reCAPTCHA V2) from Google and save them bellow.", "simple-google-recaptcha")."</p>";
}

function sgr_display_site_key_element() {
	echo "<input type=\"text\" name=\"sgr_site_key\" class=\"regular-text\" id=\"sgr_site_key\" value=\"".get_option("sgr_site_key")."\" />";
}

function sgr_display_secret_key_element() {
	echo "<input type=\"text\" name=\"sgr_secret_key\" class=\"regular-text\" id=\"sgr_secret_key\" value=\"".get_option("sgr_secret_key")."\" />";
}

function sgr_display_options() {
	add_settings_section("sgr_header_section", __("What first?", "simple-google-recaptcha"), "sgr_display_content", "sgr-options");

	add_settings_field("sgr_site_key", __("Site Key", "simple-google-recaptcha"), "sgr_display_site_key_element", "sgr-options", "sgr_header_section");
	add_settings_field("sgr_secret_key", __("Secret Key", "simple-google-recaptcha"), "sgr_display_secret_key_element", "sgr-options", "sgr_header_section");

	register_setting("sgr_header_section", "sgr_site_key");
	register_setting("sgr_header_section", "sgr_secret_key");
}
add_action("admin_init", "sgr_display_options");

function load_language_sgr() {
	load_plugin_textdomain("simple-google-recaptcha", false, dirname(plugin_basename(__FILE__))."/languages/");
}
add_action("plugins_loaded", "load_language_sgr");

function frontend_sgr_script() {
	if (did_action("login_init") > 0 || function_exists("is_account_page") || function_exists("bp_get_signup_page") || (is_singular() && comments_open()) && !is_user_logged_in()) {
		wp_register_script("sgr_recaptcha_main", plugin_dir_url(__FILE__)."main.js");
		wp_enqueue_script("sgr_recaptcha_main");
		wp_localize_script("sgr_recaptcha_main", "sgr_recaptcha", array("site_key" => get_option("sgr_site_key")));
		wp_register_script("sgr_recaptcha", "https://www.google.com/recaptcha/api.js?hl=".get_locale()."&onload=sgr&render=explicit");
		wp_enqueue_script("sgr_recaptcha");
		wp_enqueue_style("style", plugin_dir_url(__FILE__)."style.css");
	
		add_action("comment_form_after_fields", "sgr_display");
		add_action("login_form", "sgr_display");
		add_action("register_form", "sgr_display");
		add_action("lost_password", "sgr_display");
		add_action("lostpassword_form", "sgr_display");
		add_action("retrieve_password", "sgr_display");
		add_action("resetpass_form", "sgr_display");
		add_action("woocommerce_login_form", "sgr_display");
		add_action("woocommerce_register_form", "sgr_display");
		add_action("woocommerce_lostpassword_form", "sgr_display");
		add_action("bp_after_signup_profile_fields", "sgr_display");
	}
}

function sgr_display() {
	echo "<div class=\"g-recaptcha\"></div>";
}

function sgr_verify($input) {
	if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["g-recaptcha-response"])) {
		$recaptcha_response = sanitize_text_field($_POST["g-recaptcha-response"]);
		$recaptcha_secret = get_option("sgr_secret_key");
		$response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret=".$recaptcha_secret."&response=".$recaptcha_response);
		$response = json_decode($response["body"], true);
		
		if ($response["success"] == true) {
			return $input;
		} else {
			wp_die("<p><strong>".__("ERROR:", "simple-google-recaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "simple-google-recaptcha")."</p>\n\n<p><a href=".wp_get_referer().">&laquo; ".__("Back", "simple-google-recaptcha")."</a>");
			return null;
		}
		
	} else {
		wp_die("<p><strong>".__("ERROR:", "simple-google-recaptcha")."</strong> ".__("Google reCAPTCHA verification failed.", "simple-google-recaptcha")." ".__("Do you have JavaScript enabled?", "simple-google-recaptcha")."</p>\n\n<p><a href=".wp_get_referer().">&laquo; ".__("Back", "simple-google-recaptcha")."</a>");
		return null;
	}
}

function sgr_check() {
	if (get_option("sgr_site_key") != "" && get_option("sgr_secret_key") != "") {
		
		add_action("login_enqueue_scripts", "frontend_sgr_script");
		add_action("wp_enqueue_scripts", "frontend_sgr_script");
		
		if (!is_user_logged_in()) {
			add_action("preprocess_comment", "sgr_verify");
		}
		
		add_action("wp_authenticate_user", "sgr_verify");
		add_action("registration_errors", "sgr_verify");			
		add_action("lostpassword_post", "sgr_verify");
		add_action("resetpass_post", "sgr_verify");
		add_action("bp_signup_validate", "sgr_verify");
		add_action("woocommerce_register_post", "sgr_verify");
	}
}

add_action("init", "sgr_check");