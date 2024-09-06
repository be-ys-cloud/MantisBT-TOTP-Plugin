<?php

# Copyright (c) BeYs Cloud - 2024. This program is proposed under GPLv3
# See LICENSE.txt for more informations.

require_once('vendor/phpqrcode/phpqrcode.php');
require_once('vendor/php-totp/Base32.php');
require_once('vendor/php-totp/HOTP.php');
require_once('vendor/php-totp/TOTP.php');
require_once('core/database.php');

class TOTPPlugin extends MantisPlugin
{

    // register plugin informations
    function register()
    {
        $this->name = plugin_lang_get('title');
        $this->description = plugin_lang_get('description');

        $this->version = '1.0.0';
        $this->requires = array(
            'MantisCore' => '2.3.0-dev',
        );

        $this->author = 'BeYs Cloud';
        $this->contact = 'dev-cloud@be-ys.com';
        $this->url = 'https://www.be-ys.cloud';
        $this->page = 'config'; # Default plugin page (update)
    }

    // Create required SQL scheme to store informations in database
    function schema()
    {
        $t_table_options = array(
            'mysql' => 'DEFAULT CHARSET=utf8',
            'pgsql' => 'WITHOUT OIDS',
        );

        return array(
            array("CreateTableSQL", array(plugin_table("totp"), "
          user_id I NOT NULL UNIQUE,
          secret_key C(2000) NOT NULL UNIQUE
        ", $t_table_options))
        );
    }

    // Plugin general configuration
    function config()
    {
        return array(
            'issuer' => 'MantisBt Bug Tracker',
        );
    }

    // List impacted hooks by our plugin
    function hooks()
    {
        $t_hooks = array(
            'EVENT_AUTH_USER_FLAGS' => 'auth_user_flags',
            'EVENT_MANAGE_USER_PAGE' => 'manage_user_page',
            'EVENT_USER_PAGE' => 'show_user_page',
            'EVENT_MENU_ACCOUNT' => 'account_menu',
        );

        return $t_hooks;
    }

    // Add a "Manage TOTP" section in user account
    function account_menu(){
            return array( '<a href="' . plugin_page( 'manage-totp' ) . '">' . plugin_lang_get( 'manage' ) .  '</a>', );
    }

    // Handle custom authentication
    function auth_user_flags($p_event_name, $p_args)
    {
        // Retrieve user arguments
        $t_username = $p_args['username'];
        $t_user_id = $p_args['user_id'];

        // If user does not exists (or is anonymous), let him go through standard authentication
        if (!$t_user_id || user_is_anonymous($t_user_id)) {
            return null;
        }

        if(!isUserTOTPConfigured($t_user_id)) {
            return null;
        }


        // If we reach this point, use our own authentication process.
        $t_flags = new AuthFlags();
        $t_flags->setCredentialsPage(helper_url_combine(plugin_page('login-totp', true), 'username=' . $t_username));
        return $t_flags;
    }
}
