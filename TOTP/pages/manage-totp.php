<?php

auth_ensure_user_authenticated();
auth_reauthenticate();
current_user_ensure_unprotected();

layout_page_header(plugin_lang_get('manage'));
layout_page_begin();
print_account_menu('plugin.php?page=TOTP/manage-totp');

$t_user_id = auth_get_current_user_id();

$isTOTPConfigured = isUserTOTPConfigured($t_user_id);
if ($isTOTPConfigured) {
    $secret_key = retrieveTOTPForUser($t_user_id);
}

?>

    <div class="col-md-12 col-xs-12">
        <div class="space-10">

        </div>

        <div id="user-custom-fields" class="form-container">
            <h3>
                <?php echo plugin_lang_get($isTOTPConfigured ? "totp_enabled" : "totp_not_enabled"); ?>
            </h3>

            <?php
            if ($isTOTPConfigured) {
                echo "<img src='" . plugin_page('render-qrcode') . "'/><br />";
                echo plugin_lang_get("secret_key") . ": <code>" . $secret_key . "</code>";
            }
            ?>
            <br/><br/>

            <form action="<?php echo plugin_page('switch-totp-state'); ?>" method='post'>
                <input type='submit'
                       value='<?php echo plugin_lang_get($isTOTPConfigured ? 'totp_disable_button' : 'totp_enable_button'); ?>'
                       class="button-totp <?php echo $isTOTPConfigured ? 'button-totp-enabled' : 'button-totp-disabled'; ?>"/>
            </form>
        </div>
    </div>

    <style>
        .button-totp {
            border: 0;
            border-radius: 0;
            padding: 10px;
            color: white
        }

        .button-totp-enabled {
            background: green;
        }

        .button-totp-disabled {
            background: darkred;
        }
    </style>
<?php

layout_page_end();