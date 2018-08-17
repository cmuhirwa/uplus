$(document).ready(function(){
	//Getting slider's content container
	promo_cont = $(".promocont");

	//Adding a slider class for slicking
	promo_cont.addClass("slider");

	//Getting slides and classifying them
	if(slides = promo_cont.find(".promo_container")){
		//Here we marks slides
		slides.addClass("slide");

		//Activating the slider controllers
		$(".slider-controllers").show(2000);

		//Checking if container has slides and have been marked successfully
		if(promo_cont.find(".slide")){
			//Here we found slides we can slick them
			promo_cont.slick({
				infinite: true,
				slidesToShow: 2,
				autoplay: true,
				autoplaySpeed: 5000,
				slidesToScroll: 1,
				adaptiveHeight: true,
				prevArrow: '.slick-btn.slick-prev-btn',
				nextArrow: '.slick-btn.slick-next-btn',
				responsive: [
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1
						}
					}
			 ]
			});
		}
	}
});