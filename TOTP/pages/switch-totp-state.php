<?php

auth_ensure_user_authenticated();
auth_reauthenticate();
current_user_ensure_unprotected();
$t_user_id = auth_get_current_user_id();


if (!isUserTOTPConfigured($t_user_id)) {
    $secret = Totp::GenerateSecret(32);
    $encoded = Base32::encode($secret);

    $query = "INSERT INTO {plugin_TOTP_totp}(user_id, secret_key) VALUES ({$t_user_id}, '{$encoded}');";
} else {
    $query = "DELETE FROM {plugin_TOTP_totp} WHERE user_id = {$t_user_id} LIMIT 1;";
}

db_query($query);

// Redirect
header("Location: ". plugin_page('manage-totp'));