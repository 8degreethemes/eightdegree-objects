<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Featured_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-featuredsection';
	}

	public function get_title() {
		return __( '8DT - Featured Section', 'the100' );
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
			'the100_section_featured_section',
			[
				'label' => __( 'Featured Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_featured_title',
			[
				'label' => __( 'Featured Section Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_featured_description',
			[
				'label' => __( 'Featured Section Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_featured_category',
			[
				'label' => __( 'Choose Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_featured_layout',
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
					'lay-six' => __( 'Layout 6', 'the100' ),
					'lay-seven' => __( 'Layout 7', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_feat_layout = (!empty($settings['the100_section_featured_layout']))?$settings['the100_section_featured_layout']:"lay-one";
		$the100_feat_title = (!empty($settings['the100_section_featured_title']))?$settings['the100_section_featured_title']:"";
		$the100_feat_desc = (!empty($settings['the100_section_featured_description']))?$settings['the100_section_featured_description']:"";
		?>
		<section class="featured-section <?php echo esc_attr($the100_feat_layout);?>">
			<?php
			echo '<div class="ed-container">';
			if($the100_feat_layout=='lay-seven'){
				echo "<div class='td-wrap'>";
			}
			if($the100_feat_title!=''){
				?>
				<h2 class="section-title wow fadeInLeft">
					<span>
						<?php echo esc_html($the100_feat_title);
						if($the100_feat_desc!='' && ($the100_feat_layout=='lay-five')){ ?>
							<small><?php echo wp_kses_post(force_balance_tags($the100_feat_desc));?></small>
						<?php } ?>
					</span>
				</h2>
				<?php
			}
			if($the100_feat_desc!='' && ($the100_feat_layout!='lay-five')){
				?>
				<div class="section-desc wow fadeInRight"><?php echo wp_kses_post(force_balance_tags($the100_feat_desc));?></div>
				<?php
			}
			if($the100_feat_layout=='lay-seven'){
				echo "</div>";
			}
			if($the100_feat_layout=='lay-four'){
				echo "</div>";
			}
			$the100_feat_cat = (!empty($settings['the100_section_featured_category']))?$settings['the100_section_featured_category']:"";
			if($the100_feat_cat>0){
				$postnum=3;
				if ( $the100_feat_layout=='lay-three') {$postnum=8;}
				elseif ( $the100_feat_layout=='lay-four') {$postnum=6;}
				elseif ( $the100_feat_layout=='lay-six') {$postnum=4;}
				$feat = new WP_Query(array('cat' => $the100_feat_cat,'post_status'=>'publish','posts_per_page' => $postnum));
				if($feat->have_posts()){
					$sn=1;
					echo "<div class='featured-posts-wrap'>";
					while($feat->have_posts()){
						$feat-> the_post();
						echo "<div class='featured-posts wow pulse'>";
						if($the100_feat_layout=='lay-two' || $the100_feat_layout=='lay-four' || $the100_feat_layout=='lay-six' || $the100_feat_layout=='lay-seven'){ echo "<div class='feat-imgtitle-wrap'>";}
						if($the100_feat_layout=='lay-seven'){
							echo '<span>'.$sn.'</span>';
						}
						if(has_post_thumbnail()){
							if($the100_feat_layout=='lay-seven'){
								echo '<div class="image-wrap">';
								the_post_thumbnail('the100-square');
								echo '</div>';
							}else{
								the_post_thumbnail('the100-rectangle');
							}
						}
						if($the100_feat_layout=='lay-four' || $the100_feat_layout=='lay-six' || $the100_feat_layout=='lay-seven'){ echo "</div>";}
						if($the100_feat_layout=='lay-four' || $the100_feat_layout=='lay-six' || $the100_feat_layout=='lay-seven'){ echo "<div class='feat-content-wrap'>";}
						if($the100_feat_layout=='lay-seven'){
							echo '<div class="meta-wrap">';
							the_category();
							echo " / ";
							the100_posted_on();
							echo '</div>';
						}
						the_title( '<h3 class="feat-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
						if($the100_feat_layout=='lay-two'){ echo "</div>";}
						if($the100_feat_layout!='lay-seven'){
							echo "<div class='feat-excerpt'>";
							the_excerpt();
							echo "</div>";
						}
						if($the100_feat_layout=='lay-four' || $the100_feat_layout=='lay-six' || $the100_feat_layout=='lay-seven'){ echo "</div>";}
						echo "</div>";
						$sn++;
					}
					echo "</div>";
					wp_reset_query();
				}
			}
			if($the100_feat_layout!='lay-four'){
				echo "</div>";
			}
			?>
		</section>	
		<?php
	}

	protected function _content_template() {}

}