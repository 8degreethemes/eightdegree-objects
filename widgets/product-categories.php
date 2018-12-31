<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_ProductCat_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-productcatsection';
	}

	public function get_title() {
		return __( '8DT - Products Categories', 'the100' );
	}

	public function get_icon() {
      	// Icon name from the Elementor font file
		return 'eicon-product-categories';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$taxonomy     = 'product_cat';
		$empty        = 1;
		$orderby      = 'name';  
		$show_count   = 0;
		$pad_counts   = 0;
		$hierarchical = 1;
		$title        = '';  
		$empty        = 0;
		$args = array(
			'taxonomy'     => $taxonomy,
			'orderby'      => $orderby,
			'show_count'   => $show_count,
			'pad_counts'   => $pad_counts,
			'hierarchical' => $hierarchical,
			'title_li'     => $title,
			'hide_empty'   => $empty

		);
		$the100_woocommerce_categories = array();
		$woocommerce_categories_obj = get_categories($args);
		foreach ($woocommerce_categories_obj as $category) {
			$the100_woocommerce_categories[$category->term_id] = $category->name;
		}

		$this->start_controls_section(
			'the100_section_productcat_section',
			[
				'label' => __( 'Products Categories', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_productcat_title',
			[
				'label' => __( 'Products Category Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'the100_section_productcat_desc',
			[
				'label' => __( 'Products Category Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_product_categories',
			[
				'label' => __( 'Product Category (if category)', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'multiple' => true,
				'options' => $the100_woocommerce_categories,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_show_product_number',
			[
				'label' => __( 'Show Number of Products', 'the100' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'the100' ),
				'label_off' => __( 'Hide', 'the100' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_category_link_text',
			[
				'label' => __( 'Category Link Text', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Shop Now','the100'),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_product_categories_layout',
			[
				'label' => __( 'Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lay-one',
				'options' => [
					'lay-one' => __('Layout 1','the100'),
					'lay-two' => __('Layout 2','the100'),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		if(!empty($settings)):
			$productcat_title = $settings['the100_section_productcat_title'];
			$productcat_list_desc = "";
			if(isset($settings['the100_section_productcat_desc'])){ $productcat_list_desc = $settings['the100_section_productcat_desc'];}
			$promo_categories   =   $settings['the100_section_product_categories'];
			$show_product_number  =   $settings['the100_section_show_product_number'];
			$cat_link_text = $settings['the100_section_category_link_text'];
			$cat_lay = $settings['the100_section_product_categories_layout'];
			?>
			<div class="the100-product-categories <?php echo esc_attr($cat_lay);?>">
				<div class="container-wrap">
					<div class="grid">
						<div class="title-desc-wrap">
							<h2 class="section-title wow fadeInLeft"><span><?php echo esc_attr($productcat_title); ?></span></h2>
							<div class="section-desc wow fadeInRight"><?php echo esc_attr($productcat_list_desc); ?></div>
						</div>
						<div class="cat-promo cat-promo-slide owl-slider owl-carousel owl-theme">
							<?php
							$i = 0;$j=0;
							foreach ($promo_categories as $promo_category) {
								$i++;
								$woo_cat_id_int = (int)$promo_category;
								$terms_link = get_term_link($woo_cat_id_int,'product_cat');
								$taxonomy = 'product_cat';
								$terms = term_description( $promo_category, $taxonomy );
								$terms_name = get_term( $promo_category, $taxonomy );
								if($terms_name!=null){
									?>
									<div class="cat-promo-wrap">
										<div class="promo-cat-image">
											<a href="<?php echo esc_url( $terms_link ); ?>">
												<?php 
												$thumbnail_id = get_woocommerce_term_meta($promo_category, 'thumbnail_id', true);
												if (!empty($thumbnail_id)) {
													$image = wp_get_attachment_image_src($thumbnail_id, 'full');
													echo '<img src="' . esc_url($image[0]) . '" alt="'.esc_attr($terms_name->name).'"/>';
												}
												else{ 
													if($cat_lay=='lay-two'){
														echo '<img src="'.esc_url("http://placehold.it/350x250").'" alt="No Image"/>';
													}else{
														echo '<img src="'.esc_url("http://placehold.it/350x450").'" alt="No Image"/>';
													}
												} ?>
											</a>
										</div>
										<div class="title-link-wrap">
											<?php if($show_product_number=='yes'){ ?>
												<span class="product-count"><?php echo esc_html__('Items','the100').' - '.esc_html($terms_name->count);?></span>
											<?php }?>
											<h3>
												<a href="<?php echo esc_url( $terms_link ); ?>"><?php echo esc_html($terms_name->name); ?></a>
											</h3>
											<a href="<?php echo esc_url( $terms_link ); ?>">
												<?php echo esc_html($cat_link_text);?>
											</a>
										</div>
									</div>
									<?php
								}
							}
							?>
						</div>
					</div>
				</div>				
			</div>
			<?php
		endif;
	}

	protected function _content_template() {}

}