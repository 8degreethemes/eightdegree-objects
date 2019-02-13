<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Blog_Section_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-blogsection';
	}

	public function get_title() {
		return __( '8DT - Blog Section', 'the100' );
	}

	public function get_icon() {
      // Icon name from the Elementor font file
		return 'eicon-post-list';
	}

	public function get_categories() {
		return [ '8degreethemes' ];
	}

	public function get_script_depends() {
		return [ 'elementor-the100-script' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'the100_section_blog_section',
			[
				'label' => __( 'Blog Section', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_section_blog_title',
			[
				'label' => __( 'Blog Section Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_blog_description',
			[
				'label' => __( 'Blog Section Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_section_blog_category',
			[
				'label' => __( 'Choose Category', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => the100_elementor_category_lists()
			]
		);

		$this->add_control(
			'the100_section_blog_layout',
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

		$this->add_control(
			'the100blog_viewmore_text',
			[
				'label' => __( 'View More Button Text', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_blog_layout = (!empty($settings['the100_section_blog_layout']))?$settings['the100_section_blog_layout']:"lay-one";
		$the100_blog_title = (!empty($settings['the100_section_blog_title']))?$settings['the100_section_blog_title']:"";
		$the100_blog_desc = (!empty($settings['the100_section_blog_description']))?$settings['the100_section_blog_description']:"";
		$the100_blog_cat = (!empty($settings['the100_section_blog_category']))?$settings['the100_section_blog_category']:"";
		if($the100_blog_cat!='0'){
			?>
			<section class="blog-section <?php echo esc_attr($the100_blog_layout);?>">
				<div class="ed-container">
					<?php if($the100_blog_layout=='lay-one'){ ?><div class="blog-title-desc-wrap"> <?php } 
					if($the100_blog_title!=''){
						?>
						<h2 class="section-title wow fadeInLeft"><span><?php echo wp_kses_post($the100_blog_title);
						if($the100_blog_desc!='' && ($the100_blog_layout=='lay-five' || $the100_blog_layout=='lay-six')){
							?>
							<small><?php echo wp_kses_post(force_balance_tags($the100_blog_desc));?></small>
							<?php
						}
						?></span></h2>
					<?php } 
					if($the100_blog_desc!='' && $the100_blog_layout!='lay-five' && $the100_blog_layout!='lay-six'){ ?>
						<div class="section-desc wow fadeInRight"><?php echo wp_kses_post(force_balance_tags($the100_blog_desc));?></div>
						<?php
					}
					if($the100_blog_layout=='lay-three'){ ?>
					</div>
				<?php } ?>
				<?php if($the100_blog_layout=='lay-one'){ ?></div> <?php } ?>
				<?php						
				if($the100_blog_cat>0){
					$post_num = 4;
					if($the100_blog_layout=='lay-one' || $the100_blog_layout=='lay-two'){$post_num = 3;}
					elseif($the100_blog_layout=='lay-five' || $the100_blog_layout=='lay-six'){$post_num = 2;}
					$blog = new WP_Query(array('cat' => $the100_blog_cat,'post_status'=>'publish','posts_per_page' => $post_num));
					if($blog->have_posts()){
						echo "<div class='blog-posts-wrap'>";
						$i=1;
						while($blog->have_posts()){
							$blog-> the_post();
							$j = $i/2;
							echo "<div class='blog-posts wow fadeInDown' data-wow-delay='".esc_attr($j)."s'>";
							if($the100_blog_layout=='lay-two' || $the100_blog_layout=='lay-three' || $the100_blog_layout=='lay-five' || $the100_blog_layout=='lay-six' || $the100_blog_layout=='lay-seven'){
								if(has_post_thumbnail()){
									if($the100_blog_layout=='lay-two' || $the100_blog_layout=='lay-five' || $the100_blog_layout=='lay-six'){
										echo "<div class='blog-imgtitle-wrap'>";
										the_post_thumbnail('the100-rectangle');
										if($the100_blog_layout!='lay-five' && $the100_blog_layout!='lay-six'){
											the_title( '<h3 class="blog-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
										}
										echo "</div>";
									}elseif($the100_blog_layout=='lay-seven'){
										the_post_thumbnail('the100-vh-large');
									}else{
										the_post_thumbnail('the100-square');
									}
								}
								echo "<div class='blog-titledesc-wrap'>";
								if($the100_blog_layout=='lay-six'){
									echo '<p class="blog-date">'.get_the_date('F j, l').'</p>';
									$cargs = array('post_id' => get_the_ID(),'count' => true);
									$comments = get_comments($cargs);
									echo '<span class="blog-comment">'.esc_attr($comments).' '.esc_html('Comments','the100-pro').'</span>';
								}
								if($the100_blog_layout!='lay-two'){
									the_title( '<h3 class="blog-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
								}
								if($the100_blog_layout=='lay-five'){
									echo '<p class="blog-date">'.get_the_date('F j, l').'</p>';
									$cargs = array('post_id' => get_the_ID(),'count' => true);
									$comments = get_comments($cargs);
									echo '<span class="blog-comment">'.esc_attr($comments).' '.esc_html('Comments','the100-pro').'</span>';
								}elseif($the100_blog_layout!='lay-six' && $the100_blog_layout!='lay-seven'){
									echo '<p class="blog-date">'.get_the_date('F j,Y').'</p>';
								}
								if($the100_blog_layout=='lay-two' || $the100_blog_layout=='lay-six'){
									echo "<div class='blog-excerpt'>";
									the_excerpt();
									echo "</div>";
								}
								echo "</div>";
							}else{
								echo "<div class='blog-titledesc-wrap'>";
								echo "<div class='blog-date'>";
								echo '<span class="date-day">'.get_the_date('j').'</span>';
								echo "<div class='blog-date-comment'>";
								if($the100_blog_layout=='lay-one'){
									echo '<span class="date-my">'.get_the_date('M Y',get_the_ID()).'</span>';
									$cargs = array('post_id' => get_the_ID(),'count' => true);
									$comments = get_comments($cargs);
									echo '<span class="blog-comment">'.esc_attr($comments).' '.esc_html('Comments','the100-pro').'</span>';
								}else{
									echo '<span class="date-my">'.get_the_date('M',get_the_ID()).'</span>';
								}
								echo "</div>";
								echo "</div>";
								echo "<div class='blog-titledesc-inside-wrap'>";
								if($the100_blog_layout=='lay-four'){
									$cargs = array('post_id' => get_the_ID(),'count' => true);
									$comments = get_comments($cargs);
									echo '<span class="blog-comment">'.esc_attr($comments).' '.esc_html('Comments','the100-pro').'</span>';
								}
								the_title( '<h3 class="blog-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
								if($the100_blog_layout=='lay-four'){
									echo "<div class='blog-excerpt'>";
									the_excerpt();
									echo "</div>";
								}
								echo "</div>";
								echo "</div>";
							}
							echo "</div>";
							$i++;
						}
						echo "</div>";
						wp_reset_postdata();
					}
					$the100blog_viewmore_text = $settings['the100blog_viewmore_text'];
					if($the100_blog_cat>0 && $the100blog_viewmore_text!=''){
						$the100_cat_link = get_category_link( $the100_blog_cat );
						echo '<div class="blog-btn-wrap"><a class="btn btn-blog" href="'.esc_url( $the100_cat_link ).'">'.$the100blog_viewmore_text.'</a></div>';
					}
				}
				?>
				<?php if($the100_blog_layout!='lay-three'){ ?>
				</div>
			<?php } ?>
		</section>	
		<?php
	}
}

protected function _content_template() {}

}