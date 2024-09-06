<?php
auth_ensure_user_authenticated();
auth_reauthenticate();

layout_page_header(plugin_lang_get('manage'));
layout_page_begin();


$t_issuer = plugin_config_get( 'issuer' );
?>
<div class="col-md-12 col-xs-12">
    <div class="space-10">

    </div>

<form action="<?php echo plugin_page( 'config_update' )?>" method="post">
    <?php echo form_security_field( 'plugin_totp_config_update' ) ?>
    <label>
        Issuer :
        <input name="issuer" value="<?php echo string_attribute( $t_issuer ) ?>" />
    </label>
    <br>
    <input type="submit" />
</form>

</div>

<?php
layout_page_end();
