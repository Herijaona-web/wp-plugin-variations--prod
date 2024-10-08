<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       x
 * @since      4.0.0
 *
 * @package    Etapes_Print
 * @subpackage Etapes_Print/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Etapes_Print
 * @subpackage Etapes_Print/public
 * @author     Njakasoa Rasolohery <ras.njaka@gmail.com>
 */
class Etapes_Print_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $dataset;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $dataset ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->dataset = $dataset;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Etapes_Print_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Etapes_Print_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( is_cart() ) {
			wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css' );
		}				
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/etapes-print-public.css?ver=4.0.0', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Etapes_Print_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Etapes_Print_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( get_post_meta( get_the_ID(), 'etapes_print_quantity', true ) ){
			wp_enqueue_script('vue', 'https://unpkg.com/vue@3', [], '3', true);
			wp_enqueue_script('pdf', 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.10.377/build/pdf.min.js', [], '3', true);
			
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/etapes-print-public-display.js', array( 'jquery' ), $this->version, true );

			$data = array();
			foreach ($this->dataset->get_options() as $option) {
				if ($option === 'production') {
					$etapes_print_delay_delivery = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_delay', true );
					if (!$etapes_print_delay_delivery) {
						$etapes_print_delay_delivery = get_option('etapes_print_delay_delivery');
					}
					$data['delay_delivery'] = $etapes_print_delay_delivery;
				} else if (get_post_meta( get_the_ID(), 'etapes_print_' . $option, true )) {
					if ($option === 'cover') {
						$cover_code = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_value', true );
						$etapes_print_cover = $this->dataset->get_cover_by_code($cover_code);
						$data['cover'] = $etapes_print_cover;
					} else if ($option === 'select_rules') {
						$select_rule_codes = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_values', true );
						$data[$option] = $this->dataset->get_select_rules_by_codes($select_rule_codes);
					} else if ($option === 'custom_format') {
						$etapes_print_format_width = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_width', true );
						$etapes_print_format_height = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_height', true );
						$etapes_print_format_setup_price = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_setup_price', true );
						$etapes_print_format_p1000 = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_p1000', true );
						$data[$option] = array( 
							'width' => $etapes_print_format_width,
							'height' => $etapes_print_format_height,
							'setup_price' => $etapes_print_format_setup_price,
							'p1000' => $etapes_print_format_p1000
						);
					} else if ($option === 'quantity') {
						$etapes_print_quantity_price_array = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_price_array', true );
						if (!$etapes_print_quantity_price_array) {
							$etapes_print_quantity_price_array = get_option('etapes_print_price_array');
						}
						$etapes_print_quantity_default_quantity = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_default_quantity', true );
						if (!$etapes_print_quantity_default_quantity) {
							$etapes_print_quantity_default_quantity = get_option('etapes_print_default_quantity');
						}
						$data['price_array'] = $etapes_print_quantity_price_array;
						$data['default_quantity'] = $etapes_print_quantity_default_quantity;

						$etapes_print_quantity_min = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_min', true );
						$etapes_print_quantity_max = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_max', true );
						$data[$option] = array( 
							'min' => $etapes_print_quantity_min,
							'max' => $etapes_print_quantity_max
						);
					} else if (	get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_values', true ) &&
					get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_default_value', true ) ) {
						$etapes_print_options_values = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_values', true );
						$etapes_print_default_value = get_post_meta( get_the_ID(), 'etapes_print_' . $option . '_default_value', true );
						$data[$option] = array( 
							'options_values' => $etapes_print_options_values,
							'default_value' => $etapes_print_default_value
						);
					}
				}
			}
			$data['delivery_price'] = get_option('etapes_print_delivery_price');
			wp_localize_script( $this->plugin_name, 'etapes_print_vue_object', $data);
		}

		if ( is_cart() ) {
			wp_enqueue_script( 'bootstrap' , plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );
		}		

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/etapes-print-public.js', array( 'jquery' ), $this->version, false );

		
	}

	public function product_variations() {
		global $product;
		$options = $this->dataset->get_options();
		$custom_options = get_option( 'etapes_print_customized_option', '' );
		$etapes_print_cover = null;
		if (get_post_meta( get_the_ID(), 'etapes_print_cover', true )) {
			$cover_code = get_post_meta( get_the_ID(), 'etapes_print_cover_value', true );
			if ($cover_code) {
				$etapes_print_cover = $this->dataset->get_cover_by_code($cover_code);
				$cover = array(
					'format' => explode(',', $etapes_print_cover['format']),
					'pages' => ['page_4'],
					'paper' => explode(',', $etapes_print_cover['paper'])
				);
	
				// mettre pelliculage en premier dans l'affichage
				$key = array_search('refinement', $options);
				array_splice($options, $key, 1);
				array_unshift($options, 'refinement');
			}
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/etapes-print-public-display.php' );
	}

	public function add_cart_item_data($cart_item_data, $product_id, $variation_id) {
		$options = $this->dataset->get_options();


		$etapes_print_pdf = array();
		if( isset( $_FILES ) && isset( $_FILES['etapes_print_file'] ) && $_FILES['etapes_print_file']['name'] !== '' ){
			$attach_id = media_handle_upload( 'etapes_print_file', 0 );

			$etapes_print_pdf['etapes_print_pdf_id'] = $attach_id;
			$etapes_print_pdf['etapes_print_pdf_url'] = wp_get_attachment_url( esc_attr( $attach_id ) );
			$cart_item_data['etapes_print_pdf'][] = $etapes_print_pdf;
		}

		foreach( $options as $option ) {
			if( isset($_REQUEST['etapes_print_' . $option]) ){
				if ('format' == $option && 'custom_format' == $_REQUEST['etapes_print_' . $option]) {
					$cart_item_data['etapes_print_custom_format_width'] = $_REQUEST['etapes_print_custom_format_width'];
					$cart_item_data['etapes_print_custom_format_height'] = $_REQUEST['etapes_print_custom_format_height'];
				}
				$cart_item_data['etapes_print_' . $option] = sanitize_text_field($_REQUEST['etapes_print_' . $option]);
			}
		}

		$cart_item_data['etapes_print_weight'] = number_format($_REQUEST['etapes_print_weight'], 2, '.', '') . 'kg';
		$cart_item_data['etapes_print_price'] = $this->dataset->calculate_from_request();
		$cart_item_data['etapes_print_delivery_date'] = sanitize_text_field($_REQUEST['etapes_print_delivery_date']);

    return $cart_item_data;
	}

	public function get_item_data($item_data, $cart_item) {
		$options = $this->dataset->get_options();

		if ( isset( $cart_item['etapes_print_pdf'] ) ) {
			foreach ( $cart_item['etapes_print_pdf'] as $etapes_print_pdf ) {
				$name    = 'Fichier choisi';
				$display = $etapes_print_pdf['etapes_print_pdf_id'];

				$item_data[] = array(
					'name'    => $name,
					'display' => wp_get_attachment_image( $display, 'thumbnail', 'true', '' )
				);
			}
		}

		foreach( $options as $option ) {
			if( array_key_exists('etapes_print_' . $option, $cart_item)  && $option != 'quantity') {
					$custom_details = $cart_item['etapes_print_' . $option];
					$custom_format = '';
					if ('format' == $option && 'custom_format' == $custom_details) {
						$custom_format = ' (' . $cart_item['etapes_print_custom_format_width'] . 'cm x ' . $cart_item['etapes_print_custom_format_height'] . 'cm)';
					}
					$custom_details_labels = $this->dataset->get_results_by_options($option, array($custom_details));
					$item_data[] = array(
							'key'   => $this->dataset->get_label_by_key($option),
							'value' => $this->dataset->get_label_by_key($custom_details, $custom_details_labels) . $custom_format
					);
			}
		}

		if( array_key_exists('etapes_print_delivery_date', $cart_item) ) {
			$item_data[] = array(
				'key'   => 'Livraison indicative',
				'value' => $cart_item['etapes_print_delivery_date']
			);
		}
		
		$item_data[] = array(
			'key'   => 'Poids',
			'value' => $cart_item['etapes_print_weight']
		);
    return $item_data;
	}

	public function checkout_create_order_line_item($item, $cart_item_key, $values, $order) {
		$options = $this->dataset->get_options();

		if ( !empty( $values['etapes_print_pdf'] ) ) {
			foreach ( $values['etapes_print_pdf'] as $key => $value ) {
				$media_url = wp_get_attachment_url( esc_attr( $value['etapes_print_pdf_id'] ) );	
				$item->add_meta_data( '_etapes_print_pdf', $media_url );
			}
		}

		foreach( $options as $option ) {
			$custom_format = '';
			if ('format' == $option && 'custom_format' == $values['etapes_print_' . $option]) {
				$custom_format = ' (' . $values['etapes_print_custom_format_width'] . 'cm x ' . $values['etapes_print_custom_format_height'] . 'cm)';
			}
			if( array_key_exists('etapes_print_' . $option, $values) ) {
					$labels = $this->dataset->get_results_by_options($option, array($values['etapes_print_' . $option]));
					$item->add_meta_data($this->dataset->get_label_by_key($option), $this->dataset->get_label_by_key($values['etapes_print_' . $option], $labels) . $custom_format);
			}
		}

		if( array_key_exists('etapes_print_delivery_date', $values) ) {
			$item->add_meta_data('Livraison indicative', $values['etapes_print_delivery_date']);
		}

		$item->add_meta_data('Poids', $values['etapes_print_weight']);
	}


	public function custom_before_cart_table () {
		include( plugin_dir_path( __FILE__ ) . 'partials/avada/avada-ep-custom-shopping-cart.php');
	}

	public function custom_after_cart_table() {
		if ( isset( $_GET['empty-cart'] ) ) {
			WC()->cart->empty_cart();
		}
		include( plugin_dir_path( __FILE__ ) . 'partials/avada/avada-ep-button-empty-cart.php');
	}
	
	public function cart_item_quantity($product_quantity, $cart_item_key, $cart_item) {
		$product_id = $cart_item['product_id'];
		if ( isset( $cart_item['etapes_print_quantity'] ) ) {
			return $cart_item['etapes_print_quantity'];
		}
		return $product_quantity;
	}

	public function remove_cart_action( $cart_item_key, $cart ) {
		$removed_item = $cart->removed_cart_contents[ $cart_item_key ];

		if ( isset( $removed_item['etapes_print_pdf'] ) && isset( $removed_item['etapes_print_pdf'][0] ) &&
				isset( $removed_item['etapes_print_pdf'][0]['etapes_print_pdf_id'] ) && $removed_item['etapes_print_pdf'][0]['etapes_print_pdf_id'] !== '' ) {

			$etapes_print_pdf_id = $removed_item['etapes_print_pdf'][0]['etapes_print_pdf_id'];

			$delete_status = wp_delete_attachment( $etapes_print_pdf_id, true );
		}
	}

	public function before_calculate_totals( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) )
				return;

		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
				return;

		// First loop to check if product 11 is in cart
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ){
			
			if ( isset($cart_item['etapes_print_price']) && isset($cart_item['etapes_print_quantity'] ) ) {
				$cart->set_quantity( $cart_item_key, $cart_item['etapes_print_quantity'] );
				$cart_item['data']->set_price($cart_item['etapes_print_price'] / $cart_item['etapes_print_quantity']);
				// $cart_item['data']->set_price($cart_item['etapes_print_price']);
			}
		}
	}

	public function is_sold_individually( $return, $product ) {
		return get_post_meta( get_the_ID(), 'etapes_print_quantity', true );
	}

	public function get_price_html( $price ) {
		$etape_print_ready = get_post_meta( get_the_ID(), 'etapes_print_quantity', true );
		if ($etape_print_ready) {
			return '';
		}
		return $price;
	}

	/*
	// FOR DEV ONLY
	public function cart_needs_payment($this_total_0,  $instance) {
		return false; 
	}
	*/
	

	public function account_menu_items( $items ) {
		unset( $items['dashboard'] );
		unset( $items['payment-methods'] );
		unset( $items['ly_saved_cards'] );
		return $items;
	}

	function custom_my_account_redirect() {
		$current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		if ( is_user_logged_in() && $current_url == "/shop/my-account/") {
				wp_redirect( '/shop/my-account/orders/' );
		}
	}

	function custom_my_account_orders_panel() {
		$args = array(
			'customer_id' => get_current_user_id(),
			'limit' => -1,
		);
		$orders = wc_get_orders($args);
		include( plugin_dir_path( __FILE__ ) . 'partials/avada/avada-ep-custom-orders.php' );
	}
	
	public function custom_order_details_email( $order, $sent_to_admin, $plain_text, $email ) {
		include( plugin_dir_path( __FILE__ ) . 'partials/avada/avada-ep-email-order-detail.php');
	}
	
	public function custom_woocommerce_email_customer_details( $order, $sent_to_admin, $plain_text, $email ) {		
		$user = $order->get_user();
		$email = $user->user_email;
		$phone = $order->get_billing_phone();
		$first_name = $order->get_billing_first_name();
		$last_name = $order->get_billing_last_name();
		$billing_address = $order->get_formatted_billing_address();
		include( plugin_dir_path( __FILE__ ) . 'partials/avada/avada-ep-email-addresses.php');
	}


	function wh_cartOrderItemsbyNewest() {
		if (WC()->cart->get_cart_contents_count() == 0) {
			return;
		}
		$cart_sort = [];
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$cart_sort[$cart_item_key] = WC()->cart->cart_contents[$cart_item_key];
		}
		return WC()->cart->cart_contents = array_reverse($cart_sort);
	
	}

}
