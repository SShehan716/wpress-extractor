<?php

$action         = $args['action'];
$prepaid_amount = $args['prepaid_amount'];
$total_amount   = $args['total_amount'];
$payment_model  = $args['payment_model'];
$payment_fields = '';
$currency = $args['order_currency'] ? $args['order_currency'] : BABE_Order::get_order_currency($args['order_id']);

$deposit = '';
if ($payment_model == 'deposit_full') $deposit = esc_html__('(deposit)', 'triply');

if ($action == 'to_pay') {
    $payment_methods_arr = BABE_Settings::get_active_payment_methods();

    $payment_titles    = '';
    $payment_details   = '';
    $input_fields_name = 'payment';
    $first_method      = '';
    $i                 = 0;
    if (isset($payment_methods_arr) && !empty($payment_methods_arr)):
        foreach ($payment_methods_arr as $method => $method_title) {
            //// input names like payment[...]
            $tab_start_active = !$i ? ' tab_active' : '';
            $first_method     = !$i ? $method : $first_method;

            $payment_titles  .= '<span class="payment_method_title payment_method_title_' . $method . ' tab_title' . $tab_start_active . '" data-method="' . $method . '">' . apply_filters('babe_checkout_payment_title_' . $method, $method_title, $args, $input_fields_name) . '</span>';
            $payment_details .= '<div class="payment_method_fields payment_method_fields_' . $method . ' tab_content' . $tab_start_active . '" data-method="' . $method . '">' . apply_filters('babe_checkout_payment_fields_' . $method, '', $args, $input_fields_name) . '</div>';
            $i++;
        }
    endif;
        echo BABE_html::checkout_form_element_amount_group($total_amount, $prepaid_amount, $payment_model, $args['order_id']);
    ?>

    <h2 class="checkout-title"><?php esc_html_e('Payment Method', 'triply') ?></h2>
    <div class="payment_group tabs_group">
        <div class="payment_titles_group tabs_titles">
            <?php printf("%s", $payment_titles); ?>
        </div>
        <div class="payment_fields_group">
            <?php printf("%s", $payment_details); ?>
        </div>
        <input type="hidden" name="payment[payment_method]" value="<?php echo esc_attr($first_method); ?>">
    </div>

    <?php

} else { ?>

    <h2><?php esc_html_e('Payment details', 'triply') ?></h2>
    <div class="payment_group payment_details_before_av_check">
        <?php echo sprintf( __('You could pay after the items availability confirmation! <br> Confirmation will be sent to your e-mail. <br> Amount to pay online will be %s.', 'triply'), BABE_Currency::get_currency_price($prepaid_amount)); ?>
    </div>
    <?php
}
