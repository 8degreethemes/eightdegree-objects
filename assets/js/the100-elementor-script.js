jQuery(document).ready(function($){
	
	//alert('test');

	var WidgetHelloWorldHandler = function( $scope, $ ) {

	};
	
	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/hello-world.default', WidgetHelloWorldHandler );
	} );

	$('.load-morecat').click(function(){
		$('.cat-promo').addClass('hidden');
		var lmId = $(this).attr('id');
		$('.cp-'+lmId).removeClass('hidden');
		$('.load-morecat').html('<i class="fa fa-circle-o"></i>');
		$(this).html('<i class="fa fa-circle"></i>');
	});

	var rtoleft = false;
	if($('body').hasClass('rtl')){
		var rtoleft = true;
	}

	$(".product-slider-wrap").owlCarousel({
		items:1,
		rtl: rtoleft,
		loop: true,
		autoplay:false,
		autoplayTimeout:5000,
		autoplaySpeed:1000,
		autoplayHoverPause:true,
		nav: true,
		dots: false,
		animateOut: 'slideOutLeft'
	});
	$(".the100-product-categories.lay-one .cat-promo-slide").owlCarousel({
		items:3,
		rtl: rtoleft,
		margin:10,
		dots: true,
		animateOut: 'slideOutLeft',
		responsive: {
			0:{
				items:1
			},
			640:{
				items:2
			},
			768:{
				items:3
			}
		}
	});
	$(".the100-product-categories.lay-two .cat-promo-slide").owlCarousel({
		items:3,
		rtl: rtoleft,
		margin:10,
		nav: true,
		dots: false,
		animateOut: 'slideOutLeft',
		responsive: {
			0:{
				items:1
			},
			640:{
				items:2
			},
			768:{
				items:3
			}
		}
	});

	$('.tabcat').click(function(){
		var currTab = $(this).parents().eq(2);//('.tab-prod-slider');
		var tabId = $(this).attr('class');
		var tabIdarr = tabId.split(' ');
		var tabId = tabIdarr[0];
		currTab.find('.tabcat').removeClass('active');
		$(this).addClass('active');
		currTab.find('.tabprod-content .tabprod').css({'opacity':'0','visibility':'hidden','height':'0'}).removeClass('active');
		currTab.find('.tabprod-content .tabprod.tabprod-'+tabId).css({'opacity':'1','visibility':'visible','height':'auto'}).addClass('active');
	});

	$(".product-tab-wrap").owlCarousel({
		items:4,
		rtl: rtoleft,
		loop: true,
		autoplay:false,
		autoplayTimeout:5000,
		autoplaySpeed:1000,
		autoplayHoverPause:true,
		margin:10,
		nav: true,
		dots: true,
		animateOut: 'slideOutLeft',
		responsive: {
			0:{
				items:1
			},
			640:{
				items:2
			},
			768:{
				items:3
			},
			1024:{
				items:4
			}
		}
	});

	$(".ytvideosl-section.lay-slider .the100-pro-video-thumbnails").owlCarousel({
		items:5,
		rtl: rtoleft,
		loop: false,
		autoplay:false,
		autoplayTimeout:5000,
		autoplaySpeed:1000,
		autoplayHoverPause:true,
		margin:20,
		nav: true,
		dots: false,
		stagePadding: 20,
		animateOut: 'slideOutLeft',
		responsive: {
			0:{
				items:1
			},
			640:{
				items:2
			},
			768:{
				items:3
			},
			1024:{
				items:5
			}
		}
	});	
});