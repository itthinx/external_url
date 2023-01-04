<?php
/**
 * functions.php
 *
 * Copyright (c) www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package external_url
 * @since 1.0.0
 */

/**
 * Implements an additional link shown after the Add to Cart button based on existing product custom field data.
 *
 * - external_url : URL that the added link points to
 * - external_url_label : Label used for the link, otherwise the class' default is used.
 */
class Custom_Field_External_Url {

	/**
	 * Link label default.
	 *
	 * @var string
	 */
	const DEFAULT_LABEL = 'Buy on ACME';

	/**
	 * Register our action hook.
	 */
	public static function init() {
		add_action( 'woocommerce_after_add_to_cart_form', array( __CLASS__, 'woocommerce_after_add_to_cart_form' ) );
	}

	/**
	 * Renders the extra link button if the external_url custom field data is set for the product.
	 */
	public static function woocommerce_after_add_to_cart_form() {

		global $product;

		$output = '';

		if ( !empty( $product ) && class_exists( 'WC_Product' ) && $product instanceof WC_Product ) {
			$external_url = get_post_meta( $product->get_id(), 'external_url', true );
			if ( !empty( $external_url ) && is_string( $external_url ) ) {
				$external_url = trim( $external_url );
				if ( strlen( $external_url ) > 0 ) {
					$external_url_label = get_post_meta( $product->get_id(), 'external_url_label', true );
					if ( empty( $external_url_label ) || !is_string( $external_url_label ) || strlen( trim( $external_url_label ) === 0 ) ) {
						$external_url_label = self::DEFAULT_LABEL;
					}
					$output .= sprintf(
						'<a class="button" href="%s">%s</a>',
						esc_url( $external_url ),
						esc_html( trim( $external_url_label ) )
					);
				}
			}
		}
		echo wp_kses_post( $output );
	}
}

Custom_Field_External_Url::init();
