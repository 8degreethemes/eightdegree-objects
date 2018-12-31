<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Team_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-teamsection';
	}

	public function get_title() {
		return __( '8DT - Team Section', 'the100' );
	}

	public function get_icon() {
      // Icon name from the Elementor font file
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'the100_section_team_section',
			[
				'label' => __( 'Team Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_team_title',
			[
				'label' => __( 'Team Section Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_team_description',
			[
				'label' => __( 'Team Section Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_team_category',
			[
				'label' => __( 'Choose Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_team_excerpt',
			[
				'label' => __( 'Enable Excerpt', 'the100' ),
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
			'the100_section_team_layout',
			[
				'label' => __( 'Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'lay-one',
				'options' => [
					'lay-one' => __( 'Layout 1', 'the100' ),
					'lay-two' => __( 'Layout 2', 'the100' ),
					'lay-three' => __( 'Layout 3', 'the100' ),
					'lay-four' => __( 'Layout 4', 'the100' ),
					'lay-five' => __( 'Layout 5', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_team_btntext',
			[
				'label' => __( 'Team Button Text', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('View All','the100'),
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_team_layout = (!empty($settings['the100_section_team_layout']))?$settings['the100_section_team_layout']:"lay-one";
		$the100_team_title = (!empty($settings['the100_section_team_title']))?$settings['the100_section_team_title']:"";
		$the100_team_desc = (!empty($settings['the100_section_team_description']))?$settings['the100_section_team_description']:"";
		?>
		<section class="team-section <?php echo esc_attr($the100_team_layout);?>">
			<div class="ed-container">
				<?php
				$the100_team_cat = (!empty($settings['the100_section_team_category']))?$settings['the100_section_team_category']:"0";
				if($the100_team_title!='' || $the100_team_desc!=''){
					echo '<div class="title-wrap">';
					?>
					<?php if($the100_team_title!=''){ ?>
						<h2 class="section-title wow fadeInLeft"><span><?php echo wp_kses_post($the100_team_title);?></span></h2>
					<?php } ?>
					<?php if($the100_team_desc!=''){ ?>
						<div class="section-desc  wow fadeInRight"><?php echo wp_kses_post(force_balance_tags($the100_team_desc));?></div>
					<?php }
					$the100_cat_btn = (!empty($settings['the100_section_team_btntext']))?$settings['the100_section_team_btntext']:__('View All','the100');
					if($the100_team_cat>0 && $the100_cat_btn!=''){
						$the100_cat_link = get_category_link( $the100_team_cat );
						echo '<a class="btn btn-team" href="'.esc_url( $the100_cat_link ).'">'. esc_html($the100_cat_btn).'</a>';
					}
					echo '</div>';
				}

				if($the100_team_cat>0){
					$team = new WP_Query(array('cat' => $the100_team_cat,'post_status'=>'publish','posts_per_page' => 4));
					if($team->have_posts()){
						echo "<div class='team-posts-wrap clear'>";
						while($team->have_posts()){
							$team-> the_post();
							echo "<div class='team-posts wow zoomIn'>";
							if(has_post_thumbnail()){
								echo "<div class='team-imgwrap'>";
								if($the100_team_layout=='lay-one'){
									the_post_thumbnail('the100-vh-large');
								}else{
									the_post_thumbnail('the100-square');
								}
								echo "</div>";
							}
							echo "<div class='team-titledesc-wrap'>";
							echo "<div class='team-titledesc-inside-wrap'>";
							the_title( '<h3 class="team-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							echo '<div class="personal-details">';
							echo "<span class='team-position'>".get_post_meta(get_the_ID(), 'the100_position', true)."</span>";
							echo "<span class='team-phone'>".get_post_meta(get_the_ID(), 'the100_phone', true)."</span>";
							echo "<span class='team-email'>".get_post_meta(get_the_ID(), 'the100_mail', true)."</span>";
							echo '<span class="personal-social">';
							$the100_fb = get_post_meta(get_the_ID(), 'the100_facebook', true);
							if($the100_fb!=''){
								echo '<a href="'.esc_url($the100_fb).'"><i class="fa fa-facebook"></i></a>';
							}
							$the100_tw = get_post_meta(get_the_ID(), 'the100_twitter', true);
							if($the100_tw!=''){
								echo '<a href="'.esc_url($the100_tw).'"><i class="fa fa-twitter"></i></a>';
							}
							$the100_sk = get_post_meta(get_the_ID(), 'the100_skype', true);
							if($the100_sk!=''){
								echo '<a href="'.esc_url($the100_sk).'"><i class="fa fa-skype"></i></a>';
							}
							echo '</span>';
							echo '</div>';
							if($settings['the100_section_team_excerpt']=='yes'){
								echo "<div class='team-excerpt'>";
								the_excerpt();
								echo "</div>";
							}
							echo "</div>";
							echo "</div>";
							echo "</div>";
						}
						echo "</div>";
						wp_reset_query();
					}
				}
				?>
			</div>
		</section>
		<?php
	}

	protected function _content_template() {}

}