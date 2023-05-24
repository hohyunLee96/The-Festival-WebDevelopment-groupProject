let carouselWidth = $(".carousel-inner")[0].scrollWidth;
let cardWidth = $(".carousel-item").width();
let scrollPosition = 0;
$(".carousel-control-next").on("click", function () {
    if (scrollPosition < (carouselWidth - cardWidth * 4)) { //check if you can go any further
        scrollPosition += cardWidth;  //update scroll position
        $(".carousel-inner").animate({scrollLeft: scrollPosition}, 600); //scroll left
    }
});
$(".carousel-control-prev").on("click", function () {
    if (scrollPosition > 0) {
        scrollPosition -= cardWidth;
        $(".carousel-inner").animate(
            {scrollLeft: scrollPosition},
            600
        );
    }
});
let multipleCardCarousel = document.querySelector(
    "#carouselExampleControls"
);
if (window.matchMedia("(min-width: 768px)").matches) {
    //rest of the code
    let carousel = new bootstrap.Carousel(multipleCardCarousel, {
        interval: false
    });
} else {
    $(multipleCardCarousel).addClass("slide");
}
let carousel = new bootstrap.Carousel(multipleCardCarousel, {
    interval: false,
    wrap: false,
});