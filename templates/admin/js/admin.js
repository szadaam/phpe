// +SB ADMIN CORE

(function ($) {
  "use strict"; // Start of use strict

  // Toggle the side navigation
  $("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {
    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");
    if ($(".sidebar").hasClass("toggled")) {
      $('.sidebar .collapse').collapse('hide');
    }
    ;
  });

  // Close any open menu accordions when window is resized below 768px
  $(window).resize(function () {
    if ($(window).width() < 768) {
      $('.sidebar .collapse').collapse('hide');
    }
    ;
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function (e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
              delta = e0.wheelDelta || -e0.detail;
      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button appear
  $(document).on('scroll', function () {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });

  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function (e) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    e.preventDefault();
  });

})(jQuery); // End of use strict

// -SB ADMIN CORE

// EVENT LISTENERS

$(document).on("click", ".edit-card", function () {
  console.log(this);
});

// dropdown events on the navbar

$(document).on("click", "#alerts-dropdown-button", function (ev) {
  $("#messages-dropdown-container").hide();
  $("#user-dropdown-container").hide();
  $("#alerts-dropdown-container").show();
});

$(document).on("click", "#messages-dropdown-button", function () {
  $("#alerts-dropdown-container").hide();
  $("#user-dropdown-container").hide();
  $("#messages-dropdown-container").show();
});

$(document).on("click", "#user-dropdown-button", function () {
  $("#alerts-dropdown-container").hide();
  $("#messages-dropdown-container").hide();
  $("#user-dropdown-container").show();
});

$(document).on("click", function (ev) {
  let target_class = $(ev.target).attr("class");
  if (target_class != null) {
    let close_dropdown = !target_class.includes(" hc-and");
    if (close_dropdown) {
      $("#alerts-dropdown-container").hide();
      $("#messages-dropdown-container").hide();
      $("#user-dropdown-container").hide();
    }
  }
});

// MAIN

$(document).ready(function () {
  $(".container-fluid").css("min-height", $(window).height() + "px");
});