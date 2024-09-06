<?php


function isUserTOTPConfigured($userId) {
    $data = db_query("SELECT user_id, secret_key FROM " . plugin_table("totp") . " WHERE user_id = '$userId'");
    return !(db_num_rows($data) == 0);
}

function retrieveTOTPForUser($userId) {
    $query = "SELECT * FROM {plugin_TOTP_totp} WHERE user_id = {$userId} LIMIT 1;";
    $result = db_query($query);
    $secret_key = "";

    while ($row = db_fetch_array($result)) {
        $secret_key = $row["secret_key"];
    }
    return $secret_key;
}