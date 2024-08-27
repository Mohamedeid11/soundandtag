<script>
    //sticky menu
    $(window).on("scroll", function () {
        var window_top = $(window).scrollTop() + 0;
        if (window_top > 0) {
            $(".classic_header, .fixed_menu ").addClass(
                "menu_fixed animated fadeInDown"
            );
            $(".navbar-brand-img").attr(
                "src",
                `{{asset('images/img/logo-dark.png')}}`
            );
            $(".navbar-drop").addClass("dark");
        } else {
            $(".classic_header, .fixed_menu").removeClass(
                "menu_fixed animated fadeInDown"
            );
            $(".navbar-brand-img").attr(
                "src",
                `{{asset('images/img/logo-1.png')}}`
            );
            $(".navbar-drop").removeClass("dark");
        }
    });
</script>
