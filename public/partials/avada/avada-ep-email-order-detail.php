<style>
    #wrapper #template_container #template_body #body_content_inner table .td{
        display:none;
    }			
    .custom_content_email {
        border:1px solid #8c7c66;
        margin:10px;
    }
    .custom_content_email table {    
        width: 100%;
        border:none;
    }
    .custom_content_email table th, td {
        /* border: 1px solid #ddd; */
        padding: 8px;
        text-align: left;
    }
    .custom_content_email table h4 {
        margin: 0;
    }
    #addresses {
        display: none;
    }
</style>
<h2><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>
<ul style="text-align: justify!important;margin: 12px!important;">
    <li>Numéro de commande: <strong><?=$order->get_order_number();?></strong></li>
    <li>Date de commande: <strong><?=date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ) ;?></strong></li>
    <li>Mode de paiement: <strong><?=$order->get_payment_method_title();?></strong></li>
    <li>Mode de livraison: <strong><?=$order->get_shipping_method();?></strong></li>
</ul>		
<?php foreach( $order->get_items() as $item_id => $item ):;
    $product = $item->get_product();
    $item_total = $item->get_total();
    $tax = $item->get_total_tax();
?>
    <div class="custom_content_email">
        <table id="cart">
        <thead>
            <tr>
                <th style="width:50%">Product</th>
                <th style="width:10%">Prix</th>
                <th style="width:8%">Quantity</th>
                <th style="width:8%" class="text-center">TVA</th>
                <th class="width:8%"><?=wc_price( $tax ); ?></th>					
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><h4><?=$product->get_name();?></h4></td>
                <td><?=wc_price( $item_total );?></td>
                <td><?=$item->get_quantity();?></td>
                <td style="text-center" class="text-center"><strong>Subtotal</strong></td>
                <td class="text-center"><?=$order->get_formatted_line_subtotal( $item )?></td>			
            </tr>
            <tr>				
                <td colspan="4">
                    <div  style="border-bottom: 1px solid #e2e2e2;margin: 12px 0;"></div>
                    <strong>Total</strong>
                </td>
                <td class="text-center">
                    <div style="border-bottom: 1px solid #e2e2e2;margin: 12px 0;"></div>
                    <strong><?=$order->get_formatted_order_total();?></strong>
                </td>
            </tr>			
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <p>Description du produit</p>
                    <?php 
                        $options = $this->dataset->get_options();					
                        foreach ( $options as $option ) {
                            $meta_value = $item->get_meta( $this->dataset->get_label_by_key( $option ) );
                            if ( $meta_value ) {
                                echo '<span style="font-size:10px;">' . $this->dataset->get_label_by_key( $option ) . '</span> :';
                                echo '<span style="font-size: 10px;">' . $meta_value . '</span><br>';
                            }
                        }
                    ?>	
                </td>			
            </tr>
        </tfoot>
        </table>
    </div>
<?php endforeach;?>	