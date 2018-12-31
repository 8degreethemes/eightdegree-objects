<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Testimonial_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-testimonialsection';
	}

	public function get_title() {
		return __( '8DT - Testimonial Section', 'the100' );
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
			'the100_section_testimonial_section',
			[
				'label' => __( 'Testimonial Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_testimonial_title',
			[
				'label' => __( 'Testimonial Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_testimonial_category',
			[
				'label' => __( 'Choose Testimonial Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_partner_title',
			[
				'label' => __( 'Partner Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);	

		$this->add_control(
			'the100_section_partner_category',
			[
				'label' => __( 'Choose Partner Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_testimonial_layout',
			[
				'label' => __( 'Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lay-one',
				'options' => [
					'lay-one' => __( 'Layout 1', 'the100' ),
					'lay-two' => __( 'Layout 2', 'the100' ),
					'lay-three' => __( 'Layout 3', 'the100' ),
					'lay-four' => __( 'Layout 4', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
		$the100_tp_layout = (!empty($settings['the100_section_testimonial_layout']))?$settings['the100_section_testimonial_layout']:"lay-one";
		$the100_testimonial_cat = (!empty($settings['the100_section_testimonial_category']))?$settings['the100_section_testimonial_category']:"0";
		$the100_partner_cat = (!empty($settings['the100_section_partner_category']))?$settings['the100_section_partner_category']:"0";
		if($the100_testimonial_cat!='0' || $the100_partner_cat!='0'){
			?>
			<section class="testimonial-partner-section">
				<div class="ed-container">
					<?php
					if($the100_testimonial_cat!='0'){
						$the100_testimonial_title = (!empty($settings['the100_section_testimonial_title']))?$settings['the100_section_testimonial_title']:"";

						if($the100_partner_cat!='0'){$the100_class=' testimonial-partner';}else{$the100_class=' testimonial-only';}
						?>
						<div class="testimonial-section <?php echo esc_attr($the100_tp_layout.$the100_class);?>">
							<?php if($the100_testimonial_title!=''){
								?>
								<h2 class="section-title wow fadeInLeft"><span><?php echo wp_kses_post($the100_testimonial_title);?></span></h2>
								<?php
							}
							if($the100_testimonial_cat>0){
								$testimonial = new WP_Query(array('cat' => $the100_testimonial_cat,'post_status'=>'publish','posts_per_page' => 5));
								if($testimonial->have_posts()){
									echo "<div id='testimonial-posts-wrap' class='testimonial-posts-wrap owl-slider owl-carousel owl-theme'>";
									while($testimonial->have_posts()){
										$testimonial-> the_post();
										echo "<div class='testimonial-posts'>";
										if($the100_tp_layout=='lay-three'){
											echo "<div class='testimonial-excerpt'>";
											if(has_post_thumbnail()){
												echo "<div class='testimonial-image'>";
												the_post_thumbnail('the100-rectangle');
												echo "</div>";
											}
											echo "<div class='testimonial-titlecontent-wrap'>";
											the_title( '<h3 class="testimonial-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
											the_excerpt();
											echo '<a href="' . esc_url( get_permalink() ) . '" >'.__('More Info','the100-pro').'</a>';
											echo "</div>";
											echo "</div>";
										}elseif($the100_tp_layout=='lay-four'){
											echo "<div class='testimonial-titleimg-wrap'>";
											if(has_post_thumbnail()){
												echo "<div class='testimonial-image'>";
												the_post_thumbnail('the100-square');
												echo "</div>";
											}
											echo "<div class='testimonial-excerpt'>";
											the_excerpt();
											echo "</div>";
											the_title( '<h3 class="testimonial-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
											echo "</div>";
										}else{
											echo "<div class='testimonial-excerpt'>";
											the_excerpt();
											echo "</div>";
											echo "<div class='testimonial-titleimg-wrap'>";
											if(has_post_thumbnail()){
												echo "<div class='testimonial-image'>";
												the_post_thumbnail('the100-square');
												echo "</div>";
											}
											the_title( '<h3 class="testimonial-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
											echo "</div>";
										}
										echo "</div>";
									}
									echo "</div>";
									wp_reset_postdata();
								}
							}
							?>
						</div>	
						<?php
					}
					if($the100_partner_cat!='0'){
						$the100_partner_title = (!empty($settings['the100_section_partner_title']))?$settings['the100_section_partner_title']:"";
						if($the100_testimonial_cat!='0'){$the100_class=' testimonial-partner';}else{$the100_class=' partner-only';}
						?>
						<div class="partner-section <?php echo esc_attr($the100_tp_layout.$the100_class);?>">
							<?php if($the100_partner_title!=''){
								?>
								<h2 class="section-title wow fadeInRight"><span><?php echo wp_kses_post($the100_partner_title);?></span></h2>
								<?php
							}
							if($the100_partner_cat>0){
								$post_num = 10;
								if($the100_tp_layout=='lay-one' && $the100_testimonial_cat!='0'){
									$post_num = 9;
								}elseif($the100_tp_layout=='lay-three'){
									$post_num = 12;
								}elseif($the100_tp_layout=='lay-four'){
									$post_num = 6;
								}
								$partner = new WP_Query(array('cat' => $the100_partner_cat,'post_status'=>'publish','posts_per_page' => $post_num));
								if($partner->have_posts()){
									echo "<div class='partner-posts-wrap wow fadeInRight'>";
									while($partner->have_posts()){
										$partner-> the_post();
										if(has_post_thumbnail()){									
											echo "<div class='partner-posts'>";
											echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';the_post_thumbnail('the100-square');
											echo '</a>';
											echo "</div>";
										}
									}
									echo "</div>";
									wp_reset_postdata();
								}
							}
							?>
						</div>	
						<?php
					}
					?>
				</div>
			</section>	
			<?php
		}
	}

	protected function _content_template() {}

}