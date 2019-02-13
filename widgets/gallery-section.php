<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Gallery_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-gallerysection';
	}

	public function get_title() {
		return __( '8DT - Gallery Section', 'the100' );
	}

	public function get_icon() {
      // Icon name from the Elementor font file
		return 'eicon-posts-grid';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'the100_section_gallery_section',
			[
				'label' => __( 'Gallery or Portfolio Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_gallery_title',
			[
				'label' => __( 'Gallery Section Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_gallery_description',
			[
				'label' => __( 'Gallery Section Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_gallery_category',
			[
				'label' => __( 'Choose Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_gallery_layout',
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

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_gallery_layout = (!empty($settings['the100_section_gallery_layout']))?$settings['the100_section_gallery_layout']:"lay-one";
		$the100_gallery_title = (!empty($settings['the100_section_gallery_title']))?$settings['the100_section_gallery_title']:"";
		$the100_gallery_desc = (!empty($settings['the100_section_gallery_description']))?$settings['the100_section_gallery_description']:"";
		?>
		<section class="gallery-section <?php echo esc_attr($the100_gallery_layout);?>">
			<?php
			echo '<div class="ed-container">';
			echo '<div class="gallery-inner-wrap">';
			
			if($the100_gallery_title!='' || $the100_gallery_desc!=''){ ?>
				<div class="title-desc-wrap">
					<?php if($the100_gallery_title!=''){ ?>
						<h2 class="section-title wow fadeInLeft"><span><?php echo wp_kses_post($the100_gallery_title);?></span></h2>
					<?php } ?>
					<?php if($the100_gallery_desc!=''){ ?>
						<div class="section-desc wow fadeInRight"><?php echo wp_kses_post(force_balance_tags($the100_gallery_desc));?></div>
						<?php
					}?>
					</div>
					<?php
				}
				if($the100_gallery_layout!='lay-three' && $the100_gallery_layout!='lay-five'){ echo "</div>";}
				if($the100_gallery_layout=='lay-two'){ echo "</div>";}
				$the100_gallery_cat = (!empty($settings['the100_section_gallery_category']))?$settings['the100_section_gallery_category']:"";
				if($the100_gallery_cat>0){
					$gpp=6;
					if($the100_gallery_layout=='lay-three' || $the100_gallery_layout=='lay-five'){
						$gpp = 4;
					}
					$gallery = new WP_Query(array('cat' => $the100_gallery_cat,'post_status'=>'publish','posts_per_page' => $gpp));
					if($gallery->have_posts()){
						$sni = 1;
						$glc = "";
						if($the100_gallery_layout=='lay-four'){
							$glc = " clear";
						}
						echo "<div class='gallery-posts-wrap".esc_attr($glc)."'>";
						while($gallery->have_posts()){
							$gallery-> the_post();
							$glcc = " wow zoomIn";
							if($the100_gallery_layout=='lay-four'){
								$glcc = "";
							}
							echo "<div class='gallery-posts".$glcc."'>";
							if(has_post_thumbnail()){
								if($the100_gallery_layout=='lay-two' || $the100_gallery_layout=='lay-five'){
									the_post_thumbnail('the100-rectangle');
								}elseif($the100_gallery_layout=='lay-four'){
									$im_size = "vh-large";
									if($sni==2 || $sni==5 || $sni==6){
										$im_size = "rectangle";
									}
									the_post_thumbnail('the100-'.$im_size);
								}else{
									the_post_thumbnail('the100-square');
								}
							}
							echo "<div class='gallery-titledesc-wrap'>";
							echo "<div class='gallery-titledesc-inside-wrap'>";
							if($the100_gallery_layout=='lay-four'){
								echo '<div class="meta-wrap">';
								the100_posted_by();
								the_category();
								echo " / ";
								the100_posted_on();
								echo '</div>';
							}
							the_title( '<h3 class="gallery-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							if($the100_gallery_layout=='lay-five'){
								echo '<div class="meta-wrap">';
								the_category();
								echo " / ";
								the100_posted_on();
								echo '</div>';
							}
							if($the100_gallery_layout!='lay-five'){
								echo "<div class='gallery-excerpt'>";
								the_excerpt();
								echo "</div>";
							}
							echo "</div>";
							echo "</div>";
							echo "</div>";
							if($sni==6){
								$sni=0;
							}
							$sni++;
						}
						echo "</div>";
						wp_reset_query();
					}
				}
				if($the100_gallery_layout=='lay-three'){ echo "</div>";}
				if($the100_gallery_layout!='lay-two'){ echo "</div>";}
				?>
			</section>
			<?php
		}

		protected function _content_template() {}

	}