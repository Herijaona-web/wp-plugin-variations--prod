<style>
    #addresses {
    display: none;
    }
    #txt_customer_details {
        padding: 12px;
        text-align: center;
        text-align: justify;
        margin: 0 0 16px;
        line-height: 0.1px;
        font-size: 12px;
        font-style: inherit;
        font-weight: 100;
    }
</style>
<div class="customer-details">
    <h2><?php esc_html_e( 'Customer details', 'woocommerce' ); ?></h2>
    <table style="border: 1px solid #8c7c66;margin: 12px;width: 100%;padding: 12px;">
        <thead>
            <tr>
                <th colspan="2">
                    <p id="txt_customer_details">Nom: <strong><?=$first_name.' '.$last_name;?></strong></p>
                    <p id="txt_customer_details">Adresse email: <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong></p>
                    <p id="txt_customer_details" >Numéro de téléphone:<strong> <?php echo wc_make_phone_clickable( $order->get_billing_phone() ); ?></strong></p>
                    <div  style="border-bottom: 1px solid #e2e2e2;margin: 12px 0;"></div>
                </th>						 
            </tr>
            <tr>
                <th><h2><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></h2></th>
                <th><h2><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></h2></th>
            </tr>   
        </thead>
        <tbody>
            <tr>
                <td><?=$billing_address?></td>
                <td><?=$shipping_address?></td>
            </tr>
        </tbody>
    </table>			
</div>