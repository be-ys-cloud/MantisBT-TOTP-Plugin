# MantisBT TOTP Login

## Presentation

This plugin aim to provide a simple (but yet efficient) TOTP login for MantisBT user.

Features:

* Create and revoke TOTP from your personal account
* Per-user private key, no seed required in global configuration
* Custom authentication process for people who have a TOTP configured

## Requirements

* **You need php-gd installed on your server in order to make QRCode generation works.**
* MantisBT >= 2.3.0

## Installation

Simply put the `TOTP` folder in your MantisBT plugin folder, and activate it from the configuration panel. You are ready
to go!

You can provide a custom issuer in the plugin configuration. By default, set to `MantisBT Bug Tracker`.

## Update this plugin

If you want to update this plugin, things should be easy, except for `login.php` and `login-totp.php`. But no worries,
these files are copied from the original MantisBT source code, and have a few modifications.

In details, magic happens:

### `pages/login.php#L67` to `pages/login.php#L87`:

```php
if (auth_does_password_match($t_user_id, $f_password)) {
    // Check OTP

    $secret_key = retrieveTOTPForUser($t_user_id);

    $key = (new Totp())->GenerateToken(Base32::decode($secret_key));

    if ($f_totp === $key) {
        auth_attempt_login($f_username, $f_password, $f_perm_login);
        session_set('secure_session', $f_secure_session);

        if ($f_username == 'administrator' && $f_password == 'root' && (is_blank($t_return) || $t_return == 'index.php')) {
            $t_return = 'account_page.php';
        }

        $t_redirect_url = 'login_cookie_test.php?return=' . $t_return;
        print_header_redirect($t_redirect_url);

        exit;
    }
}

user_increment_failed_login_count($t_user_id);
```

(based on https://github.com/mantisbt/mantisbt/blob/master/login.php)

Here, we check if password is granted or not for this user, then we are looking for TOTP. If everything is good, we log
the user. Otherwise, we redirect him to the landing page.

Also, we removed the `else` condition and just kept the content, in order to avoid code duplicate when user validates
his password but not his TOTP.

### `pages/login-totp.php#L220` to `pages/login-totp.php#L227`

```html
<br/>
<span class="block input-icon input-icon-right">
    <input id="totp" name="totp" type="password"
        placeholder="<?php echo plugin_lang_get('totp') ?>"
        size="6" maxlength="6"
        class="form-control">
    <?php print_icon('fa-key', 'ace-icon'); ?>
</span>
```

(based on https://github.com/mantisbt/mantisbt/blob/master/login_password_page.php)

Here, we... just add a "totp" field to our page. That's all.

## License

This program is proposed under GPLv3. See LICENSE.txt for more information.

## Contributing

PR, issues and so on are welcome! Don't hesitate to tag us if needed. Thank you in advance for your contribution!

## Acknowledgments

We are using some libraries (in `vendor` folder), and would like to thank these people for their amazing work:
* PHP-TOTP (https://github.com/lfkeitel/php-totp)
* PHPQrCode (https://phpqrcode.sourceforge.net/)