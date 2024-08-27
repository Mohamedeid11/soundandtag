(function ($) {
    "use strict";

    //offcanvus menu js
    $(".offcanvas_overlay").on("click", function () {
        $(".offcanvas_overlay").removeClass("active");
    });

    // dropdown-toggle class not added for submenus by current WP Bootstrap Navwalker as of November 15, 2017.
    $(".dropdown-menu > .dropdown > a").addClass("dropdown-toggle");

    $(".dropdown-menu a.dropdown-toggle").on("click", function (e) {
        if (!$(this).next().hasClass("show")) {
            $(this)
                .parents(".dropdown-menu")
                .first()
                .find(".show")
                .removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass("show");
        $(this)
            .parents("li.nav-item.dropdown.show")
            .on(".dropdown", function (e) {
                $(".dropdown-menu > .dropdown .show").removeClass("show");
            });
        $(".dropdown-menu a.dropdown-toggle").removeClass("show_dropdown");
        if ($(this).next().hasClass("show")) {
            $(this).addClass("show_dropdown");
        }
        return false;
    });

    if ($(window).innerWidth() <= 991) {
        $(".classic_header .dropdown-menu > .dropdown > .dropdown-toggle").on(
            "click",
            function () {
                $(
                    ".classic_header .dropdown-menu > .dropdown > .dropdown-toggle"
                ).removeClass("active_icon");
                if ($(this).next().hasClass("show")) {
                    $(this).addClass("active_icon");
                }
            }
        );
    } else {
        $(".classic_header .dropdown-menu > .dropdown").hover(function () {
            $(this).find(".dropdown-toggle").toggleClass("active_icon");
        });
    }

    //scorl animation js
    var $single_portfolio_img = $(".overlay_effect");
    var $window = $(window);

    function scroll_addclass() {
        var window_height = $(window).height() - 200;
        var window_top_position = $window.scrollTop();
        var window_bottom_position = window_top_position + window_height;

        $.each($single_portfolio_img, function () {
            var $element = $(this);
            var element_height = $element.outerHeight();
            var element_top_position = $element.offset().top;
            var element_bottom_position = element_top_position + element_height;

            //check to see if this current container is within viewport
            if (
                element_bottom_position >= window_top_position &&
                element_top_position <= window_bottom_position
            ) {
                $element.addClass("is_show");
            }
        });
    }

    $window.on("scroll resize", scroll_addclass);
    $window.trigger("scroll");
})(jQuery);
