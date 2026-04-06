import $ from "jquery";
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel';

export function initializeCarousel() {
    console.log("Initializing carousel...");
    $(document).ready(function () {
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: false, // Disable built-in nav as you have custom buttons
            dots: false, // Assuming you still want to disable dots based on previous discussions
            responsive: {
                0: {
                    items: 1, // On screens of size 0px and up, show 1 item
                },
                600: {
                    items: 2, // On screens of size 600px and up, show 2 items
                },
                1000: {
                    items: 3, // On screens of size 1000px and up, show 3 items
                },
            },
        });

        // Custom navigation buttons
        $(".customNextBtn").click(function () {
            $(".owl-carousel").trigger("next.owl.carousel");
        });
        $(".customPrevBtn").click(function () {
            $(".owl-carousel").trigger("prev.owl.carousel");
        });
    });
}

