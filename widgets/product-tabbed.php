<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_ProductTabbed_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-producttabbedsection';
	}

	public function get_title() {
		return __( '8DT - Products In Tabs', 'the100' );
	}

	public function get_icon() {
      	// Icon name from the Elementor font file
		return 'eicon-slider-full-screen';
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
			'the100_section_producttab_section',
			[
				'label' => __( 'Products Slider', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_producttab_title',
			[
				'label' => __( 'Products Slider Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'the100_section_producttab_desc',
			[
				'label' => __( 'Products Slider Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_producttab_type',
			[
				'label' => __( 'Type of Product', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'latest',
				'options' => [
					'category' => __('Multiple Category', 'the100-pro'),
					'mixed' => __('Mixed of Latest,Onsale,Featured','the100-pro')
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_producttab_category',
			[
				'label' => __( 'Product Category (if category)', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'options' => $the100_woocommerce_categories,
				'multiple' => true,
				'condition' => [
					'the100_section_producttab_type' => 'category',
				],
				'frontend_available' => true,
			]
		);
		$the100_woocommerce_mixeds = array('latest' => __( 'Latest Products', 'the100' ),
			'onsale' => __( 'On Sale Products', 'the100' ),
			'featured' => __( 'Featured Products', 'the100' ));
		$this->add_control(
			'the100_section_producttab_mixed',
			[
				'label' => __( 'Product Types (if mixed)', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => '',
				'options' => $the100_woocommerce_mixeds,
				'multiple' => true,
				'condition' => [
					'the100_section_producttab_type' => 'mixed',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_producttab_number',
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
			$producttab_title = $settings['the100_section_producttab_title'];
			$producttab_list_desc = "";
			if(isset($settings['the100_section_producttab_desc'])){ $producttab_list_desc = $settings['the100_section_producttab_desc'];}
			$producttab_type       =   $settings['the100_section_producttab_type'];
			$producttab_number     =   $settings['the100_section_producttab_number'];
			$producttab_category = null;
			$producttab_mixed = null;
			if($producttab_type == 'category'){
				$producttab_category   =   $settings['the100_section_producttab_category'];
			}else{
				$producttab_mixed   =   $settings['the100_section_producttab_mixed'];
			}
			$count=0;
			?>
			<div class="the100-product-slider woocommerce">
				<div class="ed-container-tabbed">
					<div class="title-desc-wrap">
						<h2 class="section-title wow fadeInLeft"><span><?php echo esc_attr($producttab_title); ?></span></h2>
						<div class="section-desc wow fadeInRight"><?php echo esc_attr($producttab_list_desc); ?></div>
					</div>
					<?php 
					if($producttab_category!=null){
						?>
						<div class="tab-prod-slider prod-slider-full-width clear">
							<div class="tabcat-title">  
								<ul>
									<?php
									$i=1;
									foreach ($producttab_category as $key => $value) {
										if($value != '') {
											$cat =  get_term($value, 'product_cat'); 
											if($cat!=null){
												?>
												<li class="<?php echo 'tabcat-'.$key; echo($i==1)?" active":"";?> tabcat ">
													<span><?php echo $cat->name;?></span>
												</li>
												<?php 
												$i++;
											}
										}
									}
									?>
								</ul>
							</div>
							<div class="tabprod-content">
								<?php
								$i=1;
								foreach ($producttab_category as $key => $value) {
									if($value != '') {
										$cat =  get_term($value, 'product_cat'); 
										if($cat!=null){
											?>
											<div class="<?php echo 'tabprod-tabcat-'.$key; echo($i==1)?" active":"";?> tabprod">
												<ul class="product-tab-wrap owl-slider owl-carousel owl-theme">
													<?php
													$cargs = array(
														'post_type' => 'product',
														'tax_query' => array(array('taxonomy'  => 'product_cat',
															'field'     => 'id', 
															'terms'     => $value                                                                 
														)),
														'posts_per_page' => $producttab_number
													);
													$count = 0;
													$product_loop = new WP_Query( $cargs);
													while ( $product_loop->have_posts() ) : $product_loop->the_post(); 
														global $product; 
														$count+=0.5;

														wc_get_template_part( 'content', 'product' ); 
													endwhile; ?>
													<?php wp_reset_query(); 
													?>
												</ul>
											</div>
											<?php 
											$i++;
										}
									}
								}
								?>       
							</div>
						</div> 
						<?php 
					}elseif($producttab_mixed!=null){
						$the100_woocommerce_mixeds = array('latest' => __( 'New Products', 'the100' ),
							'onsale' => __( 'On Sale Products', 'the100' ),
							'featured' => __( 'Featured Products', 'the100' ));
							?>
							<div class="tab-prod-slider prod-slider-full-width clear">
								<div class="tabcat-title">  
									<ul>
										<?php
										$i=1;
										foreach ($producttab_mixed as $key => $value) {
											if($value != '') {
												?>
												<li class="<?php echo 'tabcat-'.$key; echo($i==1)?" active":"";?> tabcat ">
													<span><?php echo $the100_woocommerce_mixeds[$value];?></span>
												</li>
												<?php 
												$i++;
											}
										}
										?>
									</ul>
								</div>
								<div class="tabprod-content">
									<?php
									$i=1;
									foreach ($producttab_mixed as $key => $value) {
										if($value != '') {
											$producttab_args = '';
											if($value == 'latest'){
												$producttab_args = array(
													'post_type' => 'product',
													'posts_per_page' => $producttab_number
												);
											}
											elseif($value == 'featured'){
												$producttab_visibility_term_ids = wc_get_product_visibility_term_ids();
												$producttab_args = array(  
													'post_type' => 'product',  
													'posts_per_page' => $producttab_number,
													'meta_query'     => array(),
													'tax_query'      => array(
														'relation' => 'AND',
													),
												); 
												$producttab_args['tax_query'][] = array(
													'taxonomy' => 'product_visibility',
													'field'    => 'term_taxonomy_id',
													'terms'    => $producttab_visibility_term_ids['featured'],
												);
											}
											elseif($value == 'onsale'){
												$producttab_args = array(
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
											?>
											<div class="<?php echo 'tabprod-tabcat-'.$key; echo($i==1)?" active":"";?> tabprod">
												<ul class="product-tab-wrap owl-slider owl-carousel owl-theme">
													<?php
													$count = 0;
													$product_loop = new WP_Query( $producttab_args);
													while ( $product_loop->have_posts() ) : 
														$product_loop->the_post(); 
														global $product; 
														$count+=0.5;
														wc_get_template_part( 'content', 'product' ); 
													endwhile;
													wp_reset_query(); 
													?>
												</ul>
											</div>
											<?php 
											$i++;
										}
									}
									?>       
								</div>
							</div> 
							<?php
						}else{
							esc_html_e('Category Not Found','the100-pro');
						}
						?>
					</div>
				</div>
				<?php
			endif;
		}

		protected function _content_template() {}

	}