<?php
/**
 * BA Lost Password
 *
 * Override BABE_My_account::get_lostpassword_form()
 * @version 1.0.0
 */

if (is_user_logged_in()){
    return;
}
?>

<div class="my_account_page_content_wrapper login_register_page">
    <div id="lostpassword" class="triply-form-popup">
        <h3 class="triply-login-title"><?php esc_html_e( 'Reset password', 'triply' ); ?></h3>
        <form id="lostpassword_reset" name="lostpassword_reset" method="post" action="">
            <div class="lost_username">
                <label for="user_email"><?php esc_html_e( 'Your email', 'triply' ); ?></label>
                <input type="text" name="user_email" id="user_email" class="input" value="" size="20">
            </div>

            <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('babe-nonce')) ?>">
            <input type="hidden" name="action" value="lostpassword_reset">

            <div class="lost_submit">
                <button class="btn button lostpassword_submit"><?php esc_html_e( 'Get new password', 'triply' ); ?></button>
            </div>
        </form>
    </div>
</div>
