<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_The100_Ytvideosl_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ed-ytvideosl';
	}

	public function get_title() {
		return __( '8DT - YouTube Videos', 'the100' );
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
			'the100_ytvideosl_section',
			[
				'label' => __( 'YouTube Videos', 'the100' ),
				'type' => \Elementor\Controls_Manager::SECTION,
			]
		);

		$this->add_control(
			'the100_ytvideosl_title',
			[
				'label' => __( 'Widget Title', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_ytvideosl_description',
			[
				'label' => __( 'Widget Description', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_ytvideosl_channel',
			[
				'label' => __( 'YouTube Channel ID', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'UCalTPpsFaf34ABbfJbhegRQ',
				'frontend_available' => true,
				'description' => 'You can find your channel id in your channel page url. Like This is url of Youtube Channel of 8DegreeThemes "https://www.youtube.com/channel/UCalTPpsFaf34ABbfJbhegRQ", part after channel/ is channel id = "UCalTPpsFaf34ABbfJbhegRQ"',
			]
		);

		$this->add_control(
			'the100_ytvideosl_key',
			[
				'label' => __( 'YouTube API key', 'the100' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'AIzaSyCgUNoo_CeiRhOPCCPHtLbDeo7NVWkbtVw',
				'frontend_available' => true,
				'description' => 'This is the api key for fetching information. You can find how to create the key <a target="_blank" href="https://developers.google.com/youtube/registering_an_application">here</a>.'
			]
		);

		$this->add_control(
			'the100_ytvideosl_number',
			[
				'label' => __( 'Number of Videos', 'the100' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '10',
				'frontend_available' => true,
			]
		);
		$this->add_control(
			'the100_ytvideosl_sort',
			[
				'label' => __( 'Sort By', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => __( 'Date', 'the100' ),
					'rating' => __( 'Rating', 'the100' ),
					'title' => __( 'Title - Alphabetically', 'the100' ),
					'viewCount' => __( 'View Count', 'the100' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'the100_ytvideosl_lay',
			[
				'label' => __( 'Layout', 'the100' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'scroller',
				'options' => [
					'scroller' => __( 'Scroller', 'the100' ),
					'slider' => __( 'Slider', 'the100' ),
				],
				'frontend_available' => true,
			]
		);
		

		$this->end_controls_section();

	}

	protected function render() {

		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();

		$the100_ytvideosl_title = (!empty($settings['the100_ytvideosl_title']))?$settings['the100_ytvideosl_title']:"";
		$the100_ytvideosl_description = (!empty($settings['the100_ytvideosl_description']))?$settings['the100_ytvideosl_description']:"";
		$the100_ytvideosl_lay = (!empty($settings['the100_ytvideosl_lay']))?$settings['the100_ytvideosl_lay']:"scroller";
		$the100_ytvideosl_slidr = "";
		if($the100_ytvideosl_lay=='slider'){
			$the100_ytvideosl_slidr = " owl-slider owl-carousel owl-theme";
		}
		
		?>
		<section class="ytvideosl-section lay-<?php echo esc_attr($the100_ytvideosl_lay);?>">
			<div class="ed-container">
				<?php
				if($the100_ytvideosl_title!='' || $the100_ytvideosl_description!=''){
					echo '<div class="title-wrap">';
					?>
					<?php if($the100_ytvideosl_title!=''){ ?>
						<h2 class="section-title wow fadeInLeft"><span><?php echo wp_kses_post($the100_ytvideosl_title);?></span></h2>
					<?php } ?>
					<?php if($the100_ytvideosl_description!=''){ ?>
						<div class="section-desc wow fadeInRight"><?php echo wp_kses_post(force_balance_tags($the100_ytvideosl_description));?></div>
					<?php }
					$the100_channel_link = "#";
				}

				/** Dynamic Listing of video IDs starts here */
				$player_video_id = array();
				$channel_id = $settings['the100_ytvideosl_channel'];
				$key = $settings['the100_ytvideosl_key'];
				$maxVids = $settings['the100_ytvideosl_number'];
				$order = $settings['the100_ytvideosl_sort'];

				$video_list_array = wp_remote_get( 'https://www.googleapis.com/youtube/v3/search?key='.$key.'&channelId='.$channel_id.'&part=snippet,id&order='.$order.'&maxResults='.$maxVids );
				$listFromYouTube = json_decode( wp_remote_retrieve_body( $video_list_array ), true );
				foreach($listFromYouTube['items'] as $vd){
					if(isset($vd['id']['videoId'])){
						$player_video_id[] = $vd['id']['videoId'];
					}
				}
				/** Dynamic Listing of video IDs ends here */
				$video_list = array();
				if ( is_array( $player_video_id ) ) {
					foreach ( $player_video_id as $video_id ) {
						$video_list[] = $video_id;
					}
				}
				$new_video_list = $video_list;

				$new_video_list = implode( ',', $new_video_list );
				?>

				<div class="the100-pro-yt-player">

					<div class="the100-pro-video-holder clearfix"> 
						<div class="big-video">
							<div class="big-video-inner">
								<i class="fa fa-close"></i>
								<div id="the100-pro-video-placeholder"></div>
							</div>
							<div class="video-controls">
								<div class="video-control-holder">
									<div class="video-play-pause stopped"><i class="fa fa-play" aria-hidden="true"></i><i class="fa fa-pause" aria-hidden="true"></i></div>
								</div>
								<div class="video-track">
									<div class="video-current-playlist section-title">
										<?php esc_html_e( 'Fetching Video Title..', 'the100-pro' ) ?>
									</div>
									<div class="video-duration-time section-desc wow fadeInRight">
										<span class="video-current-time">0:00</span>
										/
										<span class="video-duration"><?php esc_html_e( 'Loading..', 'the100-pro' ) ?></span>     
									</div>
								</div>
							</div>
						</div>

						<div class="video-thumbs">
							<div class="the100-pro-video-thumbnails <?php echo esc_attr($the100_ytvideosl_slidr);?>">
								<?php
								$video_title = $video_thumb_url = $video_duration = "";
								$key = 'AIzaSyCgUNoo_CeiRhOPCCPHtLbDeo7NVWkbtVw';
								$i = 1;
								foreach ( $video_list as $video ) {
									$video_api = wp_remote_get( 'https://www.googleapis.com/youtube/v3/videos?id=' . $video . '&key='.$key.'&part=snippet,contentDetails', array(
										'sslverify' => false
									) );

									$video_api_array = json_decode( wp_remote_retrieve_body( $video_api ), true );
									if ( is_array( $video_api_array ) && !empty( $video_api_array[ 'items' ] ) ) {
										$snippet = $video_api_array[ 'items' ][ 0 ][ 'snippet' ];
										$video_title = $snippet[ 'title' ];
										$video_thumb_url = $snippet[ 'thumbnails' ][ 'medium' ][ 'url' ];
										$video_duration = $video_api_array[ 'items' ][ 0 ][ 'contentDetails' ][ 'duration' ];

										?>
										<div class="the100-pro-video-list" data-index="<?php echo absint($i); ?>" data-video-id="<?php echo esc_attr($video); ?>"> 
											<img alt="<?php echo esc_attr( $video_title ); ?>" src="<?php echo esc_url( $video_thumb_url ); ?>">
											<div class="video-title-duration">
												<h4><?php echo esc_html( $video_title ); ?></h4>
												<div class="video-list-duration"><?php //echo  the100_pro_youtube_duration($video_duration);  ?></div>
											</div>
										</div>
										<?php
									} else {
										?>  
										<div class="the100-pro-video-list clearfix">  
											<div class="video-title-duration">
												<h4><i><?php _e( 'Either this video has been removed or you don\'t have access to watch this video', 'the100-pro' ); ?></i></h4>
											</div>
										</div>
										<?php
									}
									$i++;
								}
								?>
							</div>
						</div>
					</div>
				</div>

				<?php
				wp_enqueue_script( 'youtube-api' );
				?>
				<script type="text/javascript">

					var player;
					var time_update_interval;

					function onYouTubeIframeAPIReady() {
						player = new YT.Player('the100-pro-video-placeholder', {
							videoId: '<?php echo esc_html($video_list[0]); ?>',
							playerVars: {
								color: 'white',
								playlist: '<?php echo esc_html($new_video_list); ?>',
							},
							events: {
								onReady: initialize,
								onStateChange: onPlayerStateChange
							}
						});

					}

					function initialize() {

				        // Update the controls on load
				        updateTimerDisplay();

				        jQuery('.video-current-playlist').text(jQuery('.the100-pro-video-list:first').text());
				        jQuery('.the100-pro-video-list:first').addClass('video-active')

				        // Clear any old interval.
				        clearInterval(time_update_interval);

				        // Start interval to update elapsed time display and
				        // the elapsed part of the progress bar every second.
				        time_update_interval = setInterval(function () {
				        	updateTimerDisplay();
				        }, 1000);

				    }

    // This function is called by initialize()
    function updateTimerDisplay() {
        // Update current time text display.
        jQuery('.video-current-time').text(formatTime(player.getCurrentTime()));
        jQuery('.video-duration').text(formatTime(player.getDuration()));
    }

    function formatTime(time) {
    	time = Math.round(time);
    	var minutes = Math.floor(time / 60),
    	seconds = time - minutes * 60;
    	seconds = seconds < 10 ? '0' + seconds : seconds;
    	return minutes + ":" + seconds;
    }

    function onPlayerStateChange(event) {
    	updateButtonStatus(event.data);
    }

    function updateButtonStatus(playerStatus) {
        //console.log(playerStatus);
        if (playerStatus == -1) {
            jQuery('.video-play-pause').removeClass('playing').addClass('stopped'); // unstarted
            var currentIndex = player.getPlaylistIndex();

            var currentElement = jQuery('.the100-pro-video-list').map(function () {
            	if (currentIndex == jQuery(this).attr('data-index')) {
            		return this;
            	}
            });

            var videoTitle = currentElement.find('h4').text();

            currentElement.siblings().removeClass('video-active');
            currentElement.addClass('video-active');

            jQuery('.video-current-playlist').html(videoTitle);

            player.setLoop(true);

        } else if (playerStatus == 0) {
            jQuery('.video-play-pause').removeClass('playing').addClass('stopped'); // ended
        } else if (playerStatus == 1) {
            jQuery('.video-play-pause').removeClass('stopped').addClass('playing'); // playing
        } else if (playerStatus == 2) {
            jQuery('.video-play-pause').removeClass('playing').addClass('stopped'); // paused
        } else if (playerStatus == 3) {
            jQuery('.video-play-pause').removeClass('playing').addClass('stopped'); // buffering
        } else if (playerStatus == 5) {
            // video cued
        }
    }

    jQuery(function ($) {

    	$('body').on('click', '.video-play-pause.stopped', function () {
    		$('.big-video-inner').show();
    		player.playVideo();
    		$(this).removeClass('stopped').addClass('playing');
    	});

    	$('body').on('click', '.video-play-pause.playing', function () {
    		player.pauseVideo();
    		$(this).removeClass('playing').addClass('stopped');
    	});

    	$('body').on('click', '.big-video-inner .fa-close', function(){
    		player.pauseVideo();
    		$(this).removeClass('playing').addClass('stopped');
    		$('.big-video-inner').hide();
    	});

    	$('.video-next').on('click', function () {
    		player.nextVideo();
    	});

    	$('.video-prev').on('click', function () {
    		player.previousVideo()
    	});

    	$('body').on('click','.the100-pro-video-list', function () {
    		var videoIndex = $(this).attr('data-index');
    		player.playVideoAt(videoIndex);
    		player.setLoop(true);
    		$('.big-video-inner').show();
    	});

    });

</script>
</div>
</section>
<?php
}

protected function _content_template() {}

}

/**
* Youtube video time convert 
*
*/
if ( !function_exists( 'the100_pro_youtube_duration' ) ) {
	function the100_pro_youtube_duration( $duration ) {

		preg_match_all( '/(\d+)/', $duration, $parts );
       //Put in zeros if we have less than 3 numbers.
		if ( count( $parts[ 0 ] ) == 1 ) {

			array_unshift( $parts[ 0 ], "0", "0" );
		} elseif ( count( $parts[ 0 ] ) == 2 ) {
			array_unshift( $parts[ 0 ], "0" );
		}

		$sec_init = $parts[ 0 ][ 2 ];
		$seconds = $sec_init % 60;
		$seconds = str_pad( $seconds, 2, "0", STR_PAD_LEFT );
		$seconds_overflow = floor( $sec_init / 60 );

		$min_init = $parts[ 0 ][ 1 ] + $seconds_overflow;
		$minutes = ( $min_init ) % 60;
		$minutes = str_pad( $minutes, 2, "0", STR_PAD_LEFT );
		$minutes_overflow = floor( ( $min_init ) / 60 );

		$hours = $parts[ 0 ][ 0 ] + $minutes_overflow;

		if ( $hours != 0 ) {
			return $hours . ':' . $minutes . ':' . $seconds;
		} else {
			return $minutes . ':' . $seconds;
		}
	}

}