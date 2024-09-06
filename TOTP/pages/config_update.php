<?php
auth_ensure_user_authenticated();
auth_reauthenticate();


form_security_validate( 'plugin_totp_config_update' );

$f_issuer = gpc_get_string( 'issuer' );

form_security_purge( 'plugin_totp_config_update' );

if( $f_issuer !== '' ) {
    plugin_config_set( 'issuer', $f_issuer );
    print_header_redirect( 'manage_plugin_page.php' );
} else {
    error_parameters( 'issuer', string_attribute( $f_issuer ) );
    trigger_error( ERROR_CONFIG_OPT_INVALID, ERROR );
    print_header_redirect( plugin_page( 'config', true ) );
}

