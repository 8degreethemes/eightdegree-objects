<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Product_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-latestproductsection';
	}

	public function get_title() {
		return __( '8DT - Products Grid', 'the100' );
	}

	public function get_icon() {
      	// Icon name from the Elementor font file
		return 'eicon-products';
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
		$the100_woocommerce_categories[''] = 'Select Product Category:';
		foreach ($woocommerce_categories_obj as $category) {
			$the100_woocommerce_categories[$category->term_id] = $category->name;
		}

		$this->start_controls_section(
			'the100_section_products_section',
			[
				'label' => __( 'Products Grid', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_product_title',
			[
				'label' => __( 'Products Grid Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'the100_section_product_desc',
			[
				'label' => __( 'Products Grid Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_product_type',
			[
				'label' => __( 'Type of Product', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => [
					'latest' => __( 'Latest Products', 'the100' ),
					'onsale' => __( 'On Sale Products', 'the100' ),
					'featured' => __( 'Featured Products', 'the100' ),
					'category' => __('Category', 'the100-pro'),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_product_category',
			[
				'label' => __( 'Product Category (if category)', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $the100_woocommerce_categories,
				'condition' => [
					'the100_section_product_type' => 'category',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_product_number',
			[
				'label' => __( 'Number of Products', 'the100' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '8',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		if(!empty($settings)):
			$product_title = $settings['the100_section_product_title'];
			$product_list_desc = "";
			if(isset($settings['the100_section_product_desc'])){ $product_list_desc = $settings['the100_section_product_desc'];}
			$product_type       =   $settings['the100_section_product_type'];
			$product_category   =   $settings['the100_section_product_category'];
			$product_number     =   $settings['the100_section_product_number'];
			$product_args       =   '';
			if($product_type == 'category'){
				$product_args = array(
					'post_type' => 'product',
					'tax_query' => array(array('taxonomy'  => 'product_cat',
						'field'     => 'id', 
						'terms'     => $product_category                                                                 
					)),
					'posts_per_page' => $product_number
				);
			}
			elseif($product_type == 'latest'){
				$product_args = array(
					'post_type' => 'product',
					'posts_per_page' => $product_number
				);
			}
			elseif($product_type == 'featured'){
				$product_visibility_term_ids = wc_get_product_visibility_term_ids();
				$product_args = array(  
					'post_type' => 'product',  
					'posts_per_page' => $product_number,
					'meta_query'     => array(),
					'tax_query'      => array(
						'relation' => 'AND',
					),
				); 
				$product_args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'term_taxonomy_id',
					'terms'    => $product_visibility_term_ids['featured'],
				);
			}

			elseif($product_type == 'onsale'){
				$product_args = array(
					'post_type'      => 'product',
					'meta_query'     => array(
						'relation' => 'OR',
						array(
							'key'           => '_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						),
						array(
							'key'           => '_min_variation_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						)
					)
				);
			}
			
			$count=0;
			$product_loop = new WP_Query( $product_args );
			?>
			<div class="the100-product-grid woocommerce">
				<div class="ed-container-grid">
					<div class="grid">
						<div class="title-desc-wrap">
							<h2 class="section-title wow fadeInLeft"><span><?php echo esc_attr($product_title); ?></span></h2>
							<div class="section-desc wow fadeInRight"><?php echo esc_attr($product_list_desc); ?></div>
						</div>
						<?php if($product_loop->have_posts()){ ?>			

							<ul class="product-grid-wrap">
								<?php
								while ( $product_loop->have_posts() ) : $product_loop->the_post(); 
									wc_get_template_part( 'content', 'product' );
								endwhile; ?>
								<?php wp_reset_query(); ?>
							</ul>
						<?php }else{
							esc_html_e('Products Not Found','the100-pro');
						} ?>
					</div>
				</div>
			</div>
			<?php
		endif;
	}

	protected function _content_template() {}

}