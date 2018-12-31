<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Promo_Page_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-promopage';
	}

	public function get_title() {
		return __( '8DT - Promo Page', 'the100' );
	}

	public function get_icon() {
      // Icon name from the Elementor font file
		return 'eicon-info-box';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'the100_section_promo_page',
			[
				'label' => __( 'Promo Page', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_promo_page',
			[
				'label' => __( 'Choose Page', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_page_lists()
			]
		);

		$this->add_control(
			'the100_promo_page_layout',
			[
				'label' => __( 'Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lay-one',
				'options' => [
					'lay-one' => __( 'Layout 1', 'the100' ),
					'lay-two' => __( 'Layout 2', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'the100' ),
			]
		);

		$this->add_control(
			'the100_promo_page_btntext',
			[
				'label' => __( 'Promo Page Button Text', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Learn More','the100'),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_promo_page_btnlink',
			[
				'label' => __( 'Promo Page Button Link', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_promopage_layout = (!empty($settings['the100_promo_page_layout']))?$settings['the100_promo_page_layout']:"lay-one";
		?>
		<section class="promopage-section <?php echo esc_attr($the100_promopage_layout);?>">
			<div class="ed-container">
				<?php
				$the100_promopage = (!empty($settings['the100_promo_page']))?$settings['the100_promo_page']:"0";
				$page = get_post( $the100_promopage );
				if (!empty($the100_promopage)) {
					if($the100_promopage_layout=='lay-one'){
						?>					
						<h2 class="section-title"><span><?php echo wp_kses_post($page->post_title); ?></span></h2>
						<?php
					}
					?>
					<div class="promopage-wrap">
						<div class="promopage-contents section-desc">
							<?php
							if($the100_promopage_layout=='lay-two'){
								?>					
								<h2 class="section-title"><span><?php echo wp_kses_post($page->post_title); ?></span></h2>
								<?php
							}
							?>
							<?php echo wp_kses_post(the100_pro_excerpt($page->post_content,'325','...',true,true));
							$the100_pp_link = (!empty($settings['the100_promo_page_btnlink']))?$settings['the100_promo_page_btnlink']:"#";
							$the100_pp_btntxt = (!empty($settings['the100_promo_page_btntext']))?$settings['the100_promo_page_btntext']:__('Learn More','the100-pro');
							if(!empty($the100_pp_btntxt)){
								?>
								<div class="promopage-btn"><a href="<?php echo esc_url($the100_pp_link);?>"><?php echo esc_html($the100_pp_btntxt)?></a></div>
								<?php
							}?>
						</div>
						<div class="promopage-image">
							<?php echo get_the_post_thumbnail( $the100_promopage, 'the100-rectangle' );?>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
		</section>	
		<?php
	}

	protected function _content_template() {}

}