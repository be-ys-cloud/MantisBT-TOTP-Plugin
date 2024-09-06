<?php
auth_ensure_user_authenticated();
auth_reauthenticate();

$t_user_id = auth_get_current_user_id();
$t_user_username = current_user_get_field("username");
$t_issuer = urlencode(plugin_config_get( 'issuer' ));

QRCode::png("otpauth://totp/".$t_user_username."?issuer=".$t_issuer."&secret=".retrieveTOTPForUser($t_user_id), false, QR_ECLEVEL_L, 6);