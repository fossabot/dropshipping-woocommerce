<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

$knawat_options = knawat_dropshipwc_get_options();
$mp_consumer_key = isset( $knawat_options['mp_consumer_key'] ) ? esc_attr( $knawat_options['mp_consumer_key'] ) : '';
$mp_consumer_secret = isset( $knawat_options['mp_consumer_secret'] ) ? esc_attr( $knawat_options['mp_consumer_secret'] ) : '';
$token_status = isset( $knawat_options['token_status'] ) ? esc_attr( $knawat_options['token_status'] ) : 'invalid';
$product_batch = isset( $knawat_options['product_batch'] ) ? esc_attr( $knawat_options['product_batch'] ) : 25;
?>
<div class="knawat_dropshipwc_settings">

	<h3><?php esc_attr_e( 'Settings', 'dropshipping-woocommerce' ); ?></h3>
	<p><?php _e( 'You need a Knawat consumer key and consumer secret for import products into your store from knawat.com','dropshipping-woocommerce' ); ?></p>
	<form method="post" id="knawatds_setting_form">
		<table class="form-table">
			<tbody>
				<?php do_action( 'knawat_dropshipwc_before_settings_section' ); ?>

				<tr class="knawat_dropshipwc_row">
					<th scope="row">
						<?php _e( 'Knawat Consumer Key','dropshipping-woocommerce' ); ?>
					</th>
					<td>
						<input class="mp_consumer_key regular-text" name="knawat[mp_consumer_key]" type="text" value="<?php if ( $mp_consumer_key != '' ) { echo $mp_consumer_key; } ?>" />
						<p class="description" id="mp_consumer_key-description">
							<?php
							printf( '%s <a href="https://app.knawat.com/" target="_blank">%s</a>',
								__('You can get your Knawat Consumer Key', 'dropshipping-woocommerce' ),
								__('from here', 'dropshipping-woocommerce' )
							);
							?>
						</p>
					</td>
				</tr>

				<tr class="knawat_dropshipwc_row">
					<th scope="row">
						<?php _e( 'Knawat Consumer Secret','dropshipping-woocommerce' ); ?>
					</th>
					<td>
						<input class="mp_consumer_secret regular-text" name="knawat[mp_consumer_secret]" type="text" value="<?php if ( $mp_consumer_secret != '' ) { echo $mp_consumer_secret; } ?>" />
						<p class="description" id="mp_consumer_secret-description">
							<?php
							printf( '%s <a href="https://app.knawat.com/" target="_blank">%s</a>',
								__('You can get your Knawat Consumer Secret', 'dropshipping-woocommerce' ),
								__('from here', 'dropshipping-woocommerce' )
							);
							?>
						</p>
					</td>
				</tr>

				<tr class="knawat_dropshipwc_row">
					<th scope="row">
						<?php _e( 'Products Batch Size:','dropshipping-woocommerce' ); ?>
					</th>
					<td>
						<input class="product_batch regular-text" name="knawat[product_batch]" type="number" value="<?php echo $product_batch; ?>" min="1" max="1000"/>
						<p class="description" id="product_batch-description">
							<?php
							_e( 'Products batch size for import products from knawat.com', 'dropshipping-woocommerce' );
							?>
						</p>
					</td>
				</tr>

				<?php if( $mp_consumer_key !='' && $mp_consumer_secret != '' ){ ?>
					<tr class="knawat_dropshipwc_row">
						<th scope="row">
							<?php _e( 'Knawat Connection status','dropshipping-woocommerce' ); ?>
						</th>
						<td>
							<?php
							if( 'valid' === $token_status ){
								?>
								<p class="connection_wrap success">
									<span class="dashicons dashicons-yes"></span> <?php _e( 'Connected','dropshipping-woocommerce' ); ?>
								</p>
								<?php
							}else{
								?>
								<p class="connection_wrap error">
									<span class="dashicons dashicons-dismiss"></span> <?php _e( 'Not connected','dropshipping-woocommerce' ); ?>
								</p>
								<p class="description">
									<?php
									_e('Please verify your knawat consumer keys.', 'dropshipping-woocommerce' );
									?>
								</p>
								<?php
							}
							?>
						</td>
					</tr>
				<?php } ?>

				<?php
				$available_gateways = array();
				$order_statuses = wc_get_order_statuses();
				if( function_exists('WC') ){
					$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
				}
				if( !empty( $available_gateways ) && !empty( $order_statuses ) ){
					?>
					<tr class="knawat_dropshipwc_row">
						<td colspan="2" style="padding-left: 0px;">
							<strong style="font-size: 1.15em">
								<?php _e('Order Status for send order to knawat.com', 'dropshipping-woocommerce' ); ?>
							</strong>
							<p>
								<?php _e('Select payment gateway wise order status for send orders to knawat.com', 'dropshipping-woocommerce' ); ?>
							</p>
						</td>
					</tr>
					<?php
					foreach ( $available_gateways as $key => $gateway) {
						?>
						<tr class="knawat_dropshipwc_row">
							<th><?php echo $gateway->method_title ?></th>
							<td>
								<select name="knawat[order_statuses][<?php echo $key; ?>]">
									<?php
									$selected_value = isset( $knawat_options['order_statuses'][$key] ) ? sanitize_text_field( $knawat_options['order_statuses'][$key] ) : 'wc-processing';
									foreach ($order_statuses as $skey => $svalue) {

										?>
										<option value="<?php echo $skey; ?>" <?php selected( $selected_value, $skey ); ?>><?php echo $svalue; ?></option>
										<?php
									}
									?>
								</select>
							</td>
						</tr>
						<?php
					}
				}
				?>

			</tbody>
		</table>
		<div class="knawatds_element submit_button">
		    <input type="hidden" name="knawatds_action" value="knawatds_save_settings" />
		    <?php wp_nonce_field( 'knawatds_setting_form_nonce_action', 'knawatds_setting_form_nonce' ); ?>
		    <input type="submit" class="button-primary knawatds_submit_button" style=""  value="<?php esc_attr_e( 'Save Settings', 'dropshipping-woocommerce' ); ?>" />
		</div>
	</form>
</div>