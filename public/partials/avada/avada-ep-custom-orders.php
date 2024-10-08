<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       x
 * @since      4.0.0
 *
 * @package    Etapes_Print
 * @subpackage Etapes_Print/public/partials
 */
?>
<h2 class="avada-woocommerce-myaccount-heading fusion-responsive-typography-calculated" data-fontsize="44" style="--fontSize:44; line-height: 1.26;" data-lineheight="55.44px">
	Commandes
</h2>
<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Commande</span></th>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-date"><span class="nobr">Date</span></th>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-status"><span class="nobr">État</span></th>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-total"><span class="nobr">Total</span></th>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-download"><span class="nobr"></span></th>
        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Actions</span></th>
    </tr>
		</thead>

		<tbody>
      <?php foreach ($orders as $key => $value) { ?>
        <?php
          $timestamp = strtotime($value->date_created);
          $formatted_date = wp_date('d F Y', $timestamp);
          $items_count = count( $value->get_items() );
          $actions = wc_get_account_orders_actions( $value );
          $order_invoice = end($actions)['url'];
          $pdf_url = $pdf_url = wp_nonce_url( add_query_arg( array(
            'action'        => 'generate_wpo_wcpdf',
            'document_type' => 'invoice',
            'order_ids'     => $value->id,
            'my-account'    => true,
          ), admin_url( 'admin-ajax.php' ) ), 'generate_wpo_wcpdf' );
        ?>
        <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-completed order">
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-number" data-title="Commande">
            <a href="<?php echo $order_invoice ?>">n°<?php echo $value->id ?></a>
          </td>
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-date" data-title="Date">
            <time datetime="<?php echo $timestamp ?>"><?php echo $formatted_date; ?></time>
          </td>
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-status" data-title="État">
          <div class="progress-container">
            <div class="progress-segment <?php echo in_array($value->status, ['pending', 'processing', 'completed']) ? 'active' : ''; ?>">En attente</div>
            <div class="progress-segment <?php echo in_array($value->status, ['processing', 'completed']) ? 'active' : ''; ?>">En cours</div>
            <div class="progress-segment <?php echo $value->status == 'completed' ? 'active' : ''; ?>">Terminé</div>
          </div>
          </td>
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-total" data-title="Total">
            <span class="woocommerce-Price-amount amount"><?php echo $value->total ?>&nbsp;<span class="woocommerce-Price-currencySymbol">€</span></span> pour <?php echo $items_count; ?> articles
          </td>
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-download">
            <a target="_blank" href="<?php echo $value->status == 'completed' ? $pdf_url : '#'; ?>" class="avada-ep-custom-button <?php echo $value->status; ?>">Facture</button>
          </td>
          <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions" data-title="Actions">
            <a href="<?php echo $order_invoice ?>" class="woocommerce-button wp-element-button button view">Voir</a>
          </td>
        </tr>
    <?php } ?>
  </tbody>
</table>