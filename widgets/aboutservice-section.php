<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_About_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-aboutsection';
	}

	public function get_title() {
		return __( '8DT - About & Service Section', 'the100' );
	}

	public function get_icon() {
      	// Icon name from the Elementor font file
		return 'eicon-person';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'the100_section_about_section',
			[
				'label' => __( 'About Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_about_page',
			[
				'label' => __( 'Choose About Page', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_page_lists()
			]
		);

		$this->add_control(
			'the100_section_about_btntext',
			[
				'label' => __( 'About Button Text', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __('Learn More','the100'),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_about_category',
			[
				'label' => __( 'Choose Category to show in About Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_about_layout',
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

		$the100_aboutservice_layout = (!empty($settings['the100_section_about_layout']))?$settings['the100_section_about_layout']:"lay-one";
		$the100_about_btn = (!empty($settings['the100_section_about_btntext']))?$settings['the100_section_about_btntext']:__('Learn More','the100');
		?>
		<section class="aboutservice-section <?php echo esc_attr($the100_aboutservice_layout);?>">
			<?php if($the100_aboutservice_layout=='lay-three'){
				$the100_as_cont_class = 'ed-container-about';
			}else{
				$the100_as_cont_class = 'ed-container';
			}?>
			<div class="<?php echo esc_attr($the100_as_cont_class);?>">
				<?php
				$the100_about_page = (!empty($settings['the100_section_about_page']))?$settings['the100_section_about_page']:"0";
				$page = get_post( $the100_about_page );
				if (!empty($the100_about_page)) {
					$about_page_title = $page->post_title;
					$the100_as_bkg = '';
					if($the100_aboutservice_layout=='lay-three' || $the100_aboutservice_layout=='lay-five'){
						$the100_page_thumbid = get_post_thumbnail_id( $page->ID );
						$the100_as_bkg = wp_get_attachment_url( $the100_page_thumbid );
					}
					if($the100_aboutservice_layout!='lay-four' && $the100_aboutservice_layout!='lay-five'){
						?>					
						<div class="about-content-wrap" <?php if(!empty($the100_as_bkg)){ ?> style="background:url(<?php echo esc_url($the100_as_bkg);?>)" <?php } ?>>
							<div class="content-container">
								<h2 class="section-title"><span><?php 
								echo wp_kses_post($about_page_title); ?></span></h2>
								<div class="about-contents section-desc">
									<?php echo esc_html(wp_trim_words($page->post_content,'80')); ?>
								</div>
								<?php if(!empty($the100_about_btn)){ 
									?>
									<a class="btn btn-about" href="<?php echo get_permalink($page);?>"><?php echo esc_html($the100_about_btn);?></a>
									<?php 
								}?>
							</div>
						</div>
						<?php
					}
					if($the100_aboutservice_layout=='lay-one' || $the100_aboutservice_layout=='lay-four' || $the100_aboutservice_layout=='lay-five'){
						echo '<div class="about-imgserv-wrap">';
						?>
						<div class="about-image">
							<?php echo get_the_post_thumbnail( $the100_about_page, 'the100-rectangle' );?>
						</div>
						<?php
					}
				}
				wp_reset_postdata();
				$the100_service_cat = (!empty($settings['the100_section_about_category']))?$settings['the100_section_about_category']:"0";
				if($the100_service_cat>0){
					$the100_service_cate = get_category($the100_service_cat);
					echo '<div class="about-serv-wrap">';
					echo '<div class="content-container">';
					if($the100_service_cate != null && ($the100_aboutservice_layout=='lay-three' || $the100_aboutservice_layout=='lay-four' || $the100_aboutservice_layout=='lay-five')){
						?>
						<div class="title-content-wrap">
							<div class="serv-title-wrap">
								<h2 class="section-title">
									<a href="<?php echo esc_url(get_category_link( $the100_service_cat ))?>"><span>
										<?php
										if($the100_aboutservice_layout=='lay-five'){
											echo wp_kses_post($about_page_title);
										}else{
											echo wp_kses_post($the100_service_cate->name);
										} ?></span>
									</a>
								</h2>
							</div>
							<div class="about-contents section-desc">
								<?php echo esc_html(wp_trim_words($the100_service_cate->description,'80')); ?>
							</div>
						</div>
						<?php
					}
					$serv_num = 4;
					if($the100_aboutservice_layout=='lay-three'){$serv_num = 3;}
					$service = new WP_Query(array('cat' => $the100_service_cat,'post_status'=>'publish','posts_per_page' => $serv_num));
					if($service->have_posts()){
						echo "<div class='service-posts-wrap'>";
						$i=1;
						while($service->have_posts()){
							$service-> the_post();
							$j = $i/2;
							echo "<div class='service-posts wow fadeInDown' data-wow-delay='".esc_attr($j)."s'>";
							if($the100_aboutservice_layout=='lay-one'){
								if(has_post_thumbnail()){
									the_post_thumbnail('the100-square');
								}
							}
							if($i==1){$class = "expanded";}else{$class="collapsed";}
							echo "<div class='service-titledesc-wrap ".esc_attr($class)."'>";
							if($the100_aboutservice_layout=='lay-one'){
								the_title( '<h3 class="service-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
							}else{
								the_title('<h3 class="service-title">','</h3>');
							}
							echo "<div class='service-excerpt'>";
							the_excerpt();
							echo "</div>";
							echo "</div>";
							echo "</div>";
							$i++;
						}
						echo "</div>";
						wp_reset_query();
					}
					echo '</div>';
					echo '</div>';
				}
				if(($the100_aboutservice_layout=='lay-one' || $the100_aboutservice_layout=='lay-four' || $the100_aboutservice_layout=='lay-five') && !empty($the100_about_page)){
					echo '</div>';
				}
				?>
			</div>
		</section>	
		<?php
	}

	protected function _content_template() {}

}