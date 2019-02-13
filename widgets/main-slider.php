<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Main_Slider_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-carousel';
	}

	public function get_title() {
		return __( '8DT - Category Carousel', 'the100' );
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

		$this->start_controls_section(
			'the100_section_category_carousel',
			[
				'label' => __( 'Category Posts Carousel', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_carousel_category',
			[
				'label' => __( 'Choose Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				//'section' => 'the100_section_category_carousel',
				'options' => the100_elementor_category_lists()
			]
		);

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'the100_cc_slides_to_show',
			[
				'label' => __( 'Slides to Show', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'' => __( 'Default', 'the100' ),
				] + $slides_to_show,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_navigation',
			[
				'label' => __( 'Navigation', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => __( 'Arrows and Dots', 'the100' ),
					'arrows' => __( 'Arrows', 'the100' ),
					'dots' => __( 'Dots', 'the100' ),
					'none' => __( 'None', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_caption_type',
			[
				'label' => __( 'Caption', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'caption-left',
				'options' => [
					'caption-no' => __( 'None', 'the100' ),
					'caption-left' => __( 'Caption Left', 'the100' ),
					'caption-center' => __( 'Caption Center', 'the100' ),
				],
			]
		);

		$this->add_control(
			'the100_home_slider_overlay',
			[
				'label' => __( 'Overlay', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'over-image',
				'options' => [
					'over-image' => __('Over Image', 'the100'),
					'over-caption' => __('Behind Caption', 'the100'),
					'no' => __('No Overlay', 'the100'),
				],
			]
		);

		$this->add_control(
			'the100_home_slider_layout',
			[
				'label' => __( 'Slider Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lay-one',
				'options' => [
					'lay-one' => __('Layout One', 'the100-pro'),
					'lay-two' => __('Layout Two', 'the100-pro'),
					'lay-three' => __('Layout Three', 'the100-pro'),
					'lay-four' => __('Layout Four', 'the100-pro'),
					'lay-five' => __('Layout Five', 'the100-pro'),
					'lay-six' => __('Layout Six', 'the100-pro'),
					'lay-seven' => __('Layout Seven', 'the100-pro'),
					'lay-eight' => __('Layout Eight', 'the100-pro'),
					'lay-nine' => __('Layout Nine', 'the100-pro'),
					'lay-ten' => __('Layout Ten', 'the100-pro'),
				],
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
			'the100_cc_autoplay',
			[
				'label' => __( 'Autoplay', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'the100' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_speed',
			[
				'label' => __( 'Animation Speed', 'the100' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_infinite_loop',
			[
				'label' => __( 'Infinite Loop', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_effect',
			[
				'label' => __( 'Effect', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'slideOutLeft',
				'options' => [
					'slideOutLeft' => __( 'Slide Left', 'the100' ),
					'slideOutRight' => __( 'Slide Right', 'the100' ),
					'slideOutUp' => __( 'Slide Up', 'the100' ),
					'slideOutDown' => __( 'Slide Down', 'the100' ),
					'rotateOut' => __( 'Rotate', 'the100' ),
					'flip' => __( 'Flip', 'the100' ),
					'bounceOut' => __( 'Bounce', 'the100' ),
					'lightSpeedOut' => __( 'LightSpeed', 'the100' ),
					'zoomOut' => __( 'Zoom', 'the100' ),
					'fadeOut' => __( 'Fade', 'the100' ),
				],
				'condition' => [
					'the100_cc_slides_to_show' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_numeric_page',
			[
				'label' => __( 'Show Total Number', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_image_nav',
			[
				'label' => __( 'Show Image Navigation', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_cc_title_nav',
			[
				'label' => __( 'Show Title Pagination', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => __( 'Yes', 'the100' ),
					'no' => __( 'No', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

	}

	protected function render() {

      // get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$sl_clas = "";
		
		$the100_carousel_category = ! empty( $settings['the100_carousel_category'] ) ? $settings['the100_carousel_category'] : '';
		
		$the100_cc_navigation = $settings['the100_cc_navigation'];
		$show_pager = ($the100_cc_navigation == "both" || $the100_cc_navigation == "dots" ) ? "true" : "false";
		$show_controls = ($the100_cc_navigation == "both" || $the100_cc_navigation == "arrows" ) ? "true" : "false";
		$auto_transition = ($settings['the100_cc_autoplay'] == "yes") ? "true" : "false";
		$pause_on_hover = ($settings['the100_cc_pause_on_hover'] == "yes") ? "true" : "false";
		$slider_transition = $settings['the100_cc_effect'];
		$the100_cc_numeric_page = $settings['the100_cc_numeric_page'];
		$the100_cc_image_nav = $settings['the100_cc_image_nav'];
		$the100_cc_title_nav = $settings['the100_cc_title_nav'];
		if($the100_cc_title_nav == "yes") {
			$custom_pager = "true";
			$show_pager = "true";
			$sl_clas = "text-pager";
		}
		else { 
			$custom_pager =  "false";
		}
		$slider_autoplay_speed = $settings['the100_cc_autoplay_speed'];
		$slider_speed = $settings['the100_cc_speed'];
		$the100_cc_slides_to_show = (!empty($settings['the100_cc_slides_to_show']))?$settings['the100_cc_slides_to_show']:1;
		$the100_cc_slides_to_show_tablet = (!empty($settings['the100_cc_slides_to_show_tablet']))?$settings['the100_cc_slides_to_show_tablet']:1;
		$the100_cc_slides_to_show_mobile = (!empty($settings['the100_cc_slides_to_show_mobile']))?$settings['the100_cc_slides_to_show_mobile']:1;
		$the100_cc_infinite_loop = ($settings['the100_cc_infinite_loop'] == "yes") ? "true" : "false";
		$show_caption = $settings['the100_cc_caption_type'];
		$the100_home_slider_layout = $settings['the100_home_slider_layout'];

		if(!empty($the100_carousel_category)){
			?>
			<section id="slider-section" class="slider <?php echo esc_attr($the100_home_slider_layout);?>">
				<script type="text/javascript">
					jQuery(document).ready(function($) { 
						var rtoleft = false;
						if($('body').hasClass('rtl')){
							var rtoleft = true;
						}
						var owl = $("#main-slider");
						owl.owlCarousel({
							items:<?php echo esc_attr($the100_cc_slides_to_show); ?>,
							rtl: rtoleft,
							loop: <?php echo esc_attr($the100_cc_infinite_loop); ?>,
							autoplay:<?php echo esc_attr($auto_transition); ?>,
							autoplayTimeout:<?php echo esc_attr($slider_autoplay_speed); ?>,
							autoplaySpeed:<?php echo esc_attr($slider_speed); ?>,
							autoplayHoverPause:<?php echo esc_attr($pause_on_hover); ?>,
							nav: <?php echo esc_attr($show_controls); ?>,
							dots: <?php echo esc_attr($show_pager); ?>,
							dotsData:<?php echo esc_attr($custom_pager); ?>,
							animateOut: '<?php echo esc_attr($slider_transition); ?>',
							responsive: {
								360:{
									dotsEach: 3,
									items:<?php echo esc_attr($the100_cc_slides_to_show_mobile); ?>,
								},
								768:{
									items:<?php echo esc_attr($the100_cc_slides_to_show_tablet); ?>,
								},
								1024:{
									items:<?php echo esc_attr($the100_cc_slides_to_show); ?>,
								}
							},
						});
						//show number total
						var totalItems = $('#main-slider .slides').length;
						var currentIndex = $('#main-slider .active').index() + 1;
						$('.number-total').html('<span class="heading">0'+currentIndex+'</span>/0'+totalItems+'');
						$('#main-slider').on('changed.owl.carousel', function(e) {
							if (!e.namespace || e.property.name != 'position') return
								var currentIndex = e.item.index + 1;
							$('.number-total').html('<span class="heading">0'+currentIndex+'</span>/0'+e.item.count+'')
						});

						//show prev next image
						var prevDefault = $('#main-slider').find(".owl-item").eq(0).find(".slides").attr('data-navipicture');
						var nextDefault = $('#main-slider').find(".owl-item").eq(1).find(".slides").attr('data-navipicture');
						var titleprevDefault = $('#main-slider').find(".owl-item").eq(0).find("h2").html();
						var titlenextDefault = $('#main-slider').find(".owl-item").eq(1).find("h2").html();
						$('.navPrev').find('img').attr('src', prevDefault);
						$('.navNext').find('img').attr('src', nextDefault);
						$('.navPrev').find('.image').css('background-image', 'url(' + prevDefault + ')');
						$('.navNext').find('.image').css('background-image', 'url(' + nextDefault + ')');
						$('.navPrev').find('h5').html(titleprevDefault);
						$('.navNext').find('h5').html(titlenextDefault);

						$('#main-slider').on('changed.owl.carousel', function(property) {
							var current = property.item.index;
							var prev = $(property.target).find(".owl-item").first().find(".slides").attr('data-navipicture');
							var next = $(property.target).find(".owl-item").eq(current).next().find(".slides").attr('data-navipicture');
							var title_prev = $(property.target).find(".owl-item").first().find("h2").html();
							var title_next = $(property.target).find(".owl-item").eq(current).next().find("h2").html();

							$('.navPrev').find('img').attr('src', prev);
							$('.navNext').find('img').attr('src', next);
							$('.navPrev').find('.image').css('background-image', 'url(' + prev + ')');
							if (typeof next !== "undefined" && next != "") {
								$('.navNext').find('.image').css('background-image', 'url(' + next + ')');
							}
							$('.navPrev').find('h5').html(title_prev);
							$('.navNext').find('h5').html(title_next);
						});
						
						$('#main-slider.text-pager').on('changed.owl.carousel', function(property) {
							var currentIndex = $('#main-slider .owl-item.active').next();
							$('#main-slider.text-pager .owl-dot').hide();
							var pager_current = currentIndex.find(".slides").attr('data-index');
							var pager_prev = currentIndex.prev('.owl-item').find(".slides").attr('data-index');
							var pager_next = currentIndex.next('.owl-item').find(".slides").attr('data-index');
							//console.log(pager_prev+">"+pager_current+">"+pager_next+">>");
							$('#main-slider.text-pager .owl-dot').eq(pager_prev-1).show();
							$('#main-slider.text-pager .owl-dot').eq(pager_current-1).show();
							$('#main-slider.text-pager .owl-dot').eq(pager_next-1).show();
						});

						$('#main-slider').on('translated.owl.carousel', function(event) {
							$('.navPrev').removeClass('on');
							$('#main-slider .owl-stage .owl-item').each(function(){
								if($(this).last().hasClass('active')){
									$(this).closest('#main-slider').next('.navPrev').addClass('on');
								} else {
									$('.navPrev').removeClass('on');
								}
							});
						});
						//next, prev trigger click
						$('.navNext').on('click', function(e) {
							e.preventDefault();
							$('#main-slider').trigger('next.owl.carousel', [600]);
						});

						$('.navPrev').on('click', function(e) {
							e.preventDefault();
							$('#main-slider').trigger('to.owl.carousel', [0,0,true]);
							$('.navPrev').removeClass('on');
						});  
					});
				</script>
				<div class="main-slider-wrap">
					<?php if($the100_cc_numeric_page=='yes'){ ?>
						<div class="number-total">
						</div>
					<?php }?>
					<div id="main-slider" class="owl-slider owl-carousel owl-theme <?php echo esc_attr($sl_clas);?>">
						<?php
						$loop = new WP_Query(array('cat' => $the100_carousel_category,'post_status'=>'publish','posts_per_page' => -1));
						$the100_overlay = $settings['the100_home_slider_overlay'];
						if($loop->have_posts()){
							$sn = 1;
							while($loop->have_posts()){
								$loop-> the_post();                    
								?>
								<div class="slides overlay-<?php echo esc_attr($the100_overlay);?>" data-navipicture="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium');?>" data-index="<?php echo esc_attr($sn); ?>" data-dot="<?php echo esc_attr('<i>0'.$sn.'</i><b>'.get_the_title().'</b>');?>">
									<?php
									if(has_post_thumbnail()){
										the_post_thumbnail('full');
									}
									if($show_caption != 'caption-no'): ?>
										<div class="caption-wrapper <?php echo esc_attr($show_caption);?>">  
											<div class="ed-container">
												<div class="slider-caption">
													<h2 class="small-caption"> 
														<?php the_title(); ?>								
													</h2>
													<div class="slider-content">
														<?php the_excerpt();?>
													</div>
												</div>
											</div>
										</div>  
										<?php
									endif; ?> 
								</div>
								<?php 
								$sn++;
							}
							wp_reset_query();
						}?>
					</div>
					<?php if($the100_cc_image_nav=='yes'){ ?>
						<div class="navPrev navImage">
							<div class="box-nav-image">
								<div class="text-nav-image">
									<span>Prev top</span>
									<h5></h5>
								</div>
								<div class="image"><img src="" alt=""></div>
							</div>
							<div class="icon-nav">
								<i class="fa fa-angle-left"></i>
							</div>
						</div>
						<div class="navNext navImage">
							<div class="box-nav-image">
								<div class="text-nav-image">
									<span>Next up</span>
									<h5></h5>
								</div>
								<div class="image"><img src="" alt=""></div>
							</div>
							<div class="icon-nav">
								<i class="fa fa-angle-right"></i>
							</div>
						</div>  
						<?php
					}
					?>
				</div>
				<?php
			}
		}

		protected function _content_template() {}

	}