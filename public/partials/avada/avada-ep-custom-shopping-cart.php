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
			<div class="row">
				<div class="col-12 col-md-12 col-lg-12">
					<?php
					$cart_items = WC()->cart->get_cart();					
					$product = $cart_item['data'];
					$i=1;
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$product = $cart_item['data'];						
						$product_name = $product->get_name();
						$product_price = $product->get_price();
						$quantity = $cart_item['quantity'];						
						$taxes = WC()->cart->get_tax_totals();
						$tax_amount = 0;
						foreach ( $taxes as $tax ) {
						  $tax_amount += $tax->amount;
						  
						}
						$tax_price = $tax_amount / $quantity; // Calculer le montant de taxe pour chaque produit
						$total_price = $product_price * $cart_item['quantity'] + $tax_amount;												
					?>				

						<div class="cart-item">
							<div class="title_line job-edit d-flex justify-content-between mb-5" style="color: #818181;font-size: 18px;">
								<span style="display:block">
									Traitement <?=$i ;?>									 
									<a href="" class="item_title"><?=$product_name;?></a>
									<div class="custom_name">
									</div>
								</span>								
								<div class="">									
									<a href="<?php echo $cart_item['data']->get_permalink()  ;?>" class="update-cart-link"><?php //echo esc_html__( 'Update cart', 'woocommerce' )?>
										<span class="pixicon icon-edit "><i class="fas fa-edit"></i></span>
									</a>									
									<?php $remove_item_url = wc_get_cart_remove_url( $cart_item_key );?>									
									<a href="<?php echo esc_url($remove_item_url);?>" class="remove-item-link">										        								
										<span class="pixicon icon-delete"><i class="fas fa-trash-alt"></i></span>
									</a>
								</div>								
							</div>
							<div class="row">
								<div class="col-12 col-md-8 item_column" style="line-height: 20px;">
									<div class="row">
										<div class="col-6">
											<strong>Copies</strong> <br>
											<span><?=$quantity?></span>
										</div>
										<div class="col-6">
											<strong>Livraison indicative</strong><br>
											<span><?=$cart_item['etapes_print_delivery_date'] ;?></span>
										</div>
									</div>
									<div class="job_details_line" style="border-bottom: 1px solid #e2e2e2;margin: 12px 0;"></div>
								</div>
								<div class="col-12 col-md-4 item_column top-recap">
									<div class="row">
										<div class="col-6">
										<strong>Prix HT</strong><br>
										</div>
										<div class="col-6 text-right">
											<?=$product_price*$quantity;?>
										</div>
										<div class="col-6">
											<strong>TVA</strong>
										</div>
										<div class="col-6 text-right">
											<?=$tax_amount;?>
										</div>									

									</div>
									<div class="job_details_line" style="border-bottom: 1px solid #e2e2e2;margin: 12px 0;"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-md-8 item_column bottom-recap" style="align-self: center;">
									<a href="#collapseEPCart<?=$quantity?>" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="collapseEPCart<?=$quantity?>" class="job_details_btn d-none d-md-flex">
										<span class="pixicon icon-more"> <i class="fa fa-plus" aria-hidden="true"></i>&nbsp; </span> Détails du traitement										
									</a>
								</div>
								<div class="col-12 col-md-4 item_column bottom-recap" style="align-self: center;">
									<div class="row item_total" style="font-size: 16px;">
										<div class="col-6">
											<strong>Montant total</strong>
										</div>
										<div class="col-6 text-right">
											<?=wc_price( $total_price );?>
										</div>
									</div>
								</div>
							</div>
							<div id="collapseEPCart<?=$quantity?>"  class="row job_details collapse" style="font-size: 13px;">
							<?php 
								$options = $this->dataset->get_options();
								foreach( $options as $option ) {
									if ($cart_item["etapes_print_$option"]) {
									$labels = $this->dataset->get_results_by_options($option, array($cart_item['etapes_print_' . $option]));
							?>		
								<div class="col-4">
									<span class="attribute_title" style="color: rgb(85, 85, 85);font-family: Montserrat, sans-serif;font-size: 11px;opacity: 0.6"><?php echo $this->dataset->get_label_by_key($option) ?> :</span>
									<span class="attribute_value"><?php echo $this->dataset->get_label_by_key($cart_item["etapes_print_$option"], $labels);?></span>
								</div>
							<?php } } ?>
							</div>							
						</div>
					<?php	
						$i++;
						}
					?>
				</div>
				<div class="col-12 col-lg-4 col-md-4"></div>
			</div>	