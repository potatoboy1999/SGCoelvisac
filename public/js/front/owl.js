$(document).ready(function() {
    var owl;
    if ($(window).width() > 600) {
        $('.banner-home-web').css("display", "block");
        owl = $('.banner-home-web');
    } else {
        $('.banner-home-movil').css("display", "block");
        owl = $('.banner-home-movil');
    }
    owl.on("initialized.owl.carousel", () => {
        setTimeout(() => {
            $(".owl-item.active .owl-slide-animated").addClass("is-transitioned");
            $("section").show();
        }, 200);
    });
    owl.owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayTimeout: 8000,
        autoplayHoverPause: true
    });
    owl.on("initialized.owl.carousel", () => {
        setTimeout(() => {
            $(".owl-item.active .owl-slide-animated").addClass("is-transitioned");
            $("section").show();
        }, 200);
    });

    const $owlCarousel = owl.owlCarousel({
        items: 1,
        loop: true,
        nav: true,
        navText: [
            '<svg width="30" height="30" viewBox="0 0 24 24"><path d="M16.67 0l2.83 2.829-9.339 9.175 9.339 9.167-2.83 2.829-12.17-11.996z"/></svg>',
            '<svg width="30" height="30" viewBox="0 0 24 24"><path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z"/></svg>' /* icons from https://iconmonstr.com */
        ]
    });

    $owlCarousel.on("changed.owl.carousel", e => {
        $(".owl-slide-animated").removeClass("is-transitioned");

        const $currentOwlItem = $(".owl-item").eq(e.item.index);
        $currentOwlItem.find(".owl-slide-animated").addClass("is-transitioned");

        const $target = $currentOwlItem.find(".owl-slide-text");
        doDotsCalculations($target);
    });

    $owlCarousel.on("resize.owl.carousel", () => {
        setTimeout(() => {
            setOwlDotsPosition();
        }, 50);
    });

    /*if there isn't content underneath the carousel*/
    //$owlCarousel.trigger("refresh.owl.carousel");

    setOwlDotsPosition();
});

function setOwlDotsPosition() {
    const $target = $(".owl-item.active .owl-slide-text");
    doDotsCalculations($target);
}

function doDotsCalculations(el) {
    if (el.height() !== undefined) {
        const height = el.height();
        const { top, left } = el.position();
        const res = height + top + 0;
        //alert(height + ' '+top+' '+left);
        $(".owl-carousel .owl-dots").css({
            top: `${res}px`,
            left: `${((window.innerWidth-parseInt(el.css("width")))/2)+30}px`
        });
    }
}