<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;
$related_products = array('related_product_1', 'related_product_2', 'related_product_3', 'related_product_4');
$recommended_products = array('recommended_product_1', 'recommended_product_2', 'recommended_product_3');

add_action('woocommerce_after_single_product_summary', function () {
	echo '<div class="after-single-product-summary">';
}, 11);
add_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_add_to_cart', 12);
add_action('woocommerce_after_single_product_summary', 'woocommerce_template_single_meta', 13);
add_action('woocommerce_after_single_product_summary', function () {
?>
	<div class="before-pdf-embedder">
		<?php
		$dataSheetLink = get_field('product_data_sheet');
		if ($dataSheetLink) {
			echo do_shortcode('[pdf-embedder url=' . $dataSheetLink . ']');
		}
		?>
		<div class="pdf-link">
			<a class="pdf-download-link" href="<?php echo $dataSheetLink ?>" download="<?php echo $dataSheetLink ?>">download</a>
		</div>

		<div class="ppcp">
			<?php
			dynamic_sidebar('ppcp');

			?>
			<div class="social-share">
				<ul>
					<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank" class="fa fa-facebook"></a></li>
					<li><a href="https://twitter.com/home?status=<?php echo get_permalink(); ?>" target="_blank" class="fa fa-twitter"></a></li>
					<li><a href="mailto:info@example.com?&subject=&body=<?php echo get_permalink(); ?>" target="_blank" class="fa fa-envelope"></a></li>
					<li><a href="https://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=&description=" target="_blank" class="fa fa-pinterest"></a></li>
				</ul>
			</div>
		</div>
	</div>
<?php
}, 14);
add_action('woocommerce_after_single_product_summary', function () {
	echo '</div>';
}, 14);

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div class="cstm-container custom-single-product-3">
	<div class="d-flex">
		<div class="width-75">
			<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

				<?php
				/**
				 * Hook: woocommerce_before_single_product_summary.
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action('woocommerce_before_single_product_summary');
				?>

				<div class="summary entry-summary">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action('woocommerce_single_product_summary');
					?>
				</div>
				<?php
				/**
				 * Hook: woocommerce_after_single_product_summary.
				 *
				 * @hooked woocommerce_output_product_data_tabs - 10
				 * @hooked woocommerce_upsell_display - 15
				 * @hooked woocommerce_output_related_products - 20
				 */
				do_action('woocommerce_after_single_product_summary');
				?>

				<?php
				$aRecommendedProducts = array();
				foreach ($recommended_products as $value) {
					$aRecommendedProducts[] = get_page_by_path(get_field($value), OBJECT, 'product');
				}

				// if(is_array($aRecommendedProducts)){     
				//       foreach($aRecommendedProducts as $key => $value){
				//           if(!empty($value) || $value != NULL || $value != ""){
				//               return true;
				//               break;//stop the process we have seen that at least 1 of the array has value so its not empty
				//           }
				//       }
				//       return false;
				//   }
				if (!empty(array_filter($aRecommendedProducts))):  ?>
					<div class="recommended-products">
						<div class="p-listing">
							<h2 class="wc-prl-title">Recommended Products</h2>
							<ul class="products columns-3">

								<?php
								foreach ($aRecommendedProducts as $product) {
									$id = $product->ID;
									if (! empty($product)) {
										$product = wc_get_product($product);

										$productAttr = array();

										if (get_the_category_by_ID($product->category_ids[0]) === 'Casters') {
											$productAttr = array(
												'Caster Type' => 'pa_caster-type',
												'Tread Width' => 'pa_wheel-tread-width',
												'Wheel Diameter' => 'pa_wheel-diameter',
												'Wheel Type' => 'pa_wheel-type',
												'Capacity' => 'pa_load-capacity',
												'Finish' => 'pa_caster-finish'
											);
										}
										if (get_the_category_by_ID($product->category_ids[0]) === 'Wheels') {
											$productAttr = array(
												'Wheel Color' => 'pa_wheel-color',
												'Tread Width' => 'pa_wheel-tread-width',
												'Wheel Diameter' => 'pa_wheel-diameter',
												'Wheel Type' => 'pa_wheel-type',
												'Capacity' => 'pa_load-capacity',
												'Bearing Type' => 'pa_bearing-type'
											);
										}

								?>
										<li class="product">
											<a href="<?php echo get_the_permalink($id) ?>">
												<img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'medium'); ?>" alt="">
												<h2 class="woocommerce-loop-product__title">
													<?php echo $product->name; ?>
												</h2>
												<div class="product-list-withattr">
													<div class="short-des">
														<?php
														if (strlen($product->short_description) > 50)
															$short_description = substr($product->short_description, 0, 45) . '...';
														echo $short_description;
														?>
													</div>
													<?php
													echo '<ul>';
													foreach ($productAttr as $productAttrKey => $productAttrValue) {
														if ($i == 3 && is_front_page()) break;

														if ($productAttrValue == 'pa_wheel-type') {
															$productAttrValue2 = (empty($product->get_attribute('pa_wheel-type')) ? $product->get_attribute('pa_wheel-material') : $product->get_attribute('pa_wheel-type'));
														}

														if ($productAttrValue != 'pa_wheel-type') {
															$productAttrValue2 = (empty($product->get_attribute($productAttrValue)) ? '-' : $product->get_attribute($productAttrValue));
														}
														$productAttrValue2 = (empty($productAttrValue2) ? '-' : $productAttrValue2);
														echo '<li> <strong>' . $productAttrKey . '</strong><span>' . $productAttrValue2 . '</span></li>';
														$i++;
													}
													echo '</ul>';
													?>
												</div>
											</a>
										</li>

								<?php }
								}
								?>
							</ul>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
		<div class="width-25">
			<?php
			$products = array();
			foreach ($related_products as $value) {
				$products[] = get_page_by_path(get_field($value), OBJECT, 'product');
			}
			// var_dump($products);
			if (!empty(array_filter($products))): ?>
				<div id="related-products">
					<div id="related-products-inner">
						<h2>Related Products</h2>
						<ul class="products">
							<?php
							foreach ($products as $product) {
								$id = $product->ID;
								if (! empty($product)) {
									$product = wc_get_product($product);
									$productAttr = array();

									if (get_the_category_by_ID($product->category_ids[0]) === 'Casters') {
										$productAttr = array(
											'Caster Type' => 'pa_caster-type',
											'Tread Width' => 'pa_wheel-tread-width',
											'Wheel Diameter' => 'pa_wheel-diameter',
											'Wheel Type' => 'pa_wheel-type',
											'Capacity' => 'pa_load-capacity',
											'Finish' => 'pa_caster-finish'
										);
									}
									if (get_the_category_by_ID($product->category_ids[0]) === 'Wheels') {
										$productAttr = array(
											'Wheel Color' => 'pa_wheel-color',
											'Tread Width' => 'pa_wheel-tread-width',
											'Wheel Diameter' => 'pa_wheel-diameter',
											'Wheel Type' => 'pa_wheel-type',
											'Capacity' => 'pa_load-capacity',
											'Bearing Type' => 'pa_bearing-type'
										);
									}
									// 			$caster_type =  ($product->get_attribute('pa_caster-type') != '' ) ? $product->get_attribute('pa_caster-type') : '-';
									// $wheel_tread_width = ($product->get_attribute('pa_wheel-tread-width') != '') ? $product->get_attribute('pa_wheel-tread-width') : '-';
									// $wheel_diameter = ( $product->get_attribute('pa_wheel-diameter') != '') ? $product->get_attribute('pa_wheel-diameter') : '-';
									// $wheel_type = ($product->get_attribute('pa_wheel-type') != '') ? $product->get_attribute('pa_wheel-type') : '-';
									// $load_capacity = ($product->get_attribute('pa_load-capacity') != '') ? $product->get_attribute('pa_load-capacity') : '-';
									// $finish = ($product->get_attribute('pa_finish') != '') ? $product->get_attribute('pa_finish') : '-';
							?>
									<li>
										<a href="<?php echo get_the_permalink($id) ?>">
											<img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'medium'); ?>" alt="">
											<h2 class="woocommerce-loop-product__title">
												<?php echo $product->name; ?>
											</h2>
											<div class="product-list-withattr">
												<div class="short-des">
													<?php
													if (strlen($product->short_description) > 50)
														$short_description = substr($product->short_description, 0, 45) . '...';
													echo $short_description;
													?>
												</div>
												<?php
												echo '<ul>';
												foreach ($productAttr as $productAttrKey => $productAttrValue) {
													if ($i == 3 && is_front_page()) break;

													if ($productAttrValue == 'pa_wheel-type') {
														$productAttrValue2 = (empty($product->get_attribute('pa_wheel-type')) ? $product->get_attribute('pa_wheel-material') : $product->get_attribute('pa_wheel-type'));
													}

													if ($productAttrValue != 'pa_wheel-type') {
														$productAttrValue2 = (empty($product->get_attribute($productAttrValue)) ? '-' : $product->get_attribute($productAttrValue));
													}
													$productAttrValue2 = (empty($productAttrValue2) ? '-' : $productAttrValue2);
													echo '<li> <strong>' . $productAttrKey . '</strong><span>' . $productAttrValue2 . '</span></li>';
													$i++;
												}
												echo '</ul>';
												?>
											</div>
										</a>
									</li>

							<?php }
							}
							?>
						</ul>
					</div>
					<?php if ($products[1] != NULL): ?>
						<span class="view-more">View more</span>
					<?php endif ?>
				</div>
			<?php endif ?>
		</div>
	</div>
</div>
<?php //do_action( 'woocommerce_after_single_product' ); 
?>

<style>
	.custom-single-product-3 .product_meta {
		clear: left;
	}

	.custom-single-product-3 .entry-summary .product_meta,
	.custom-single-product-3 .entry-summary form.cart {
		display: none !important;
	}

	.custom-single-product-3 .entry-summary .pdfembed-iframe,
	.custom-single-product-3 .after-single-product-summary .before-pdf-embedder .pdfembed-iframe {
		min-height: 450px !important;
	}

	.custom-single-product-3 .after-single-product-summary {
		margin-bottom: 20px;
	}

	.custom-single-product-3 .after-single-product-summary form.cart {
		float: left;
		width: 48%;
	}

	.custom-single-product-3 .after-single-product-summary .product_meta {
		float: right;
		width: 48%;
		clear: none;
	}

	.custom-single-product-3 .after-single-product-summary .before-pdf-embedder {
		width: 48%;
	}

	@media only screen and (max-width: 768px) {

		.custom-single-product-3 .after-single-product-summary .before-pdf-embedder,
		.custom-single-product-3 .after-single-product-summary .product_meta,
		.custom-single-product-3 .after-single-product-summary form.cart {
			width: 100%;
			float: none;
		}
	}
</style>