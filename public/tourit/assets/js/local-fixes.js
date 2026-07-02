(function () {
  "use strict";

  var imageMap = {
    "assets/img/logo/logo.png": "assets/img/logo.webp",
    "assets/img/logo/logo-dark.png": "assets/img/logo.webp",
    "assets/img/hero/hero-1.jpg": "assets/img/slider.webp",
    "assets/img/about/01.jpg": "assets/img/02.webp",
    "assets/img/about/02.jpg": "assets/img/03.webp",
    "assets/img/feature/01.jpg": "assets/img/01.webp",
    "assets/img/destination/01.jpg": "assets/img/01.webp",
    "assets/img/destination/02.jpg": "assets/img/02.webp",
    "assets/img/destination/03.jpg": "assets/img/03.webp",
    "assets/img/destination/04.jpg": "assets/img/04.webp",
    "assets/img/destination/05.jpg": "assets/img/05.webp",
    "assets/img/flight/01.jpg": "assets/img/01 (1).webp",
    "assets/img/flight/02.jpg": "assets/img/01 (2).webp",
    "assets/img/flight/03.jpg": "assets/img/01 (3).webp",
    "assets/img/flight/04.jpg": "assets/img/01 (4).webp",
    "assets/img/flight/05.jpg": "assets/img/01 (5).webp",
    "assets/img/flight/06.jpg": "assets/img/01 (6).webp",
    "assets/img/flight/07.jpg": "assets/img/01 (8).webp",
    "assets/img/flight/08.jpg": "assets/img/01 (9).webp",
    "assets/img/hotel/01.jpg": "assets/img/02 (1).webp",
    "assets/img/hotel/02.jpg": "assets/img/02 (2).webp",
    "assets/img/hotel/03.jpg": "assets/img/02 (3).webp",
    "assets/img/hotel/04.jpg": "assets/img/02 (4).webp",
    "assets/img/hotel/05.jpg": "assets/img/02 (5).webp",
    "assets/img/hotel/06.jpg": "assets/img/02 (6).webp",
    "assets/img/video/01.jpg": "assets/img/01 (10).webp",
    "assets/img/banner/01.jpg": "assets/img/03 (1).webp",
    "assets/img/banner/02.jpg": "assets/img/03 (2).webp",
    "assets/img/banner/03.jpg": "assets/img/03 (3).webp",
    "assets/img/banner/04.jpg": "assets/img/03 (4).webp",
    "assets/img/tour/01.jpg": "assets/img/04.webp",
    "assets/img/tour/02.jpg": "assets/img/05.webp",
    "assets/img/tour/03.jpg": "assets/img/06.webp",
    "assets/img/tour/04.jpg": "assets/img/07.webp",
    "assets/img/tour/05.jpg": "assets/img/08.webp",
    "assets/img/tour/06.jpg": "assets/img/04 (1).webp",
    "assets/img/tour/07.jpg": "assets/img/04 (2).webp",
    "assets/img/tour/08.jpg": "assets/img/04 (3).webp",
    "assets/img/cta/01.jpg": "assets/img/01 (10).webp",
    "assets/img/choose/01.jpg": "assets/img/05 (1).webp",
    "assets/img/choose/02.jpg": "assets/img/06 (1).webp",
    "assets/img/car/01.jpg": "assets/img/01.webp",
    "assets/img/car/02.jpg": "assets/img/02.webp",
    "assets/img/car/03.jpg": "assets/img/03.webp",
    "assets/img/car/04.jpg": "assets/img/04.webp",
    "assets/img/car/05.jpg": "assets/img/05.webp",
    "assets/img/car/06.jpg": "assets/img/06.webp",
    "assets/img/team/01.jpg": "assets/img/02 (7).webp",
    "assets/img/team/02.jpg": "assets/img/03 (1).webp",
    "assets/img/team/03.jpg": "assets/img/03 (2).webp",
    "assets/img/team/04.jpg": "assets/img/03 (3).webp",
    "assets/img/download/01.png": "assets/img/01 (8).webp",
    "assets/img/testimonial/01.jpg": "assets/img/02.webp",
    "assets/img/testimonial/02.jpg": "assets/img/03.webp",
    "assets/img/testimonial/03.jpg": "assets/img/04.webp",
    "assets/img/testimonial/04.jpg": "assets/img/05.webp",
    "assets/img/blog/01.jpg": "assets/img/03.webp",
    "assets/img/blog/02.jpg": "assets/img/04.webp",
    "assets/img/blog/03.jpg": "assets/img/05.webp"
  };

  function svgData(markup) {
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(markup);
  }

  function circleIcon(label) {
    return svgData(
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">' +
      '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">' +
      '<stop stop-color="#24BDC7"/><stop offset="1" stop-color="#0C2C7A"/></linearGradient></defs>' +
      '<circle cx="48" cy="48" r="44" fill="url(#g)"/>' +
      '<text x="48" y="55" font-size="14" text-anchor="middle" fill="#fff" font-family="Arial, sans-serif">' + label + '</text>' +
      '</svg>'
    );
  }

  function pillLogo(label, bg, fg) {
    return svgData(
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 60">' +
      '<rect width="180" height="60" rx="14" fill="' + bg + '"/>' +
      '<text x="90" y="37" font-size="22" font-weight="700" text-anchor="middle" fill="' + fg + '" font-family="Arial, sans-serif">' + label + '</text>' +
      '</svg>'
    );
  }

  var svgMap = {
    "assets/img/icon/deal.svg": circleIcon("Deal"),
    "assets/img/icon/booking.svg": circleIcon("Book"),
    "assets/img/icon/world.svg": circleIcon("World"),
    "assets/img/icon/quality.svg": circleIcon("Best"),
    "assets/img/icon/support.svg": circleIcon("Help"),
    "assets/img/icon/booking-confirm.svg": circleIcon("Done"),
    "assets/img/icon/destination.svg": circleIcon("Trip"),
    "assets/img/icon/rating.svg": circleIcon("Rate"),
    "assets/img/icon/partner.svg": circleIcon("Pro"),
    "assets/img/icon/safety.svg": circleIcon("Safe"),
    "assets/img/icon/price.svg": circleIcon("Save"),
    "assets/img/icon/quote.svg": circleIcon("Quote"),
    "assets/img/flight/airline-1.png": pillLogo("SkyJet", "#EAFBFC", "#24BDC7"),
    "assets/img/flight/airline-2.png": pillLogo("AirGo", "#EEF4FF", "#0C2C7A"),
    "assets/img/flight/airline-3.png": pillLogo("FlyNow", "#FFF3F4", "#F96768"),
    "assets/img/flight/airline-4.png": pillLogo("TripAir", "#EAFBFC", "#24BDC7"),
    "assets/img/flight/airline-5.png": pillLogo("BlueSky", "#EEF4FF", "#0C2C7A"),
    "assets/img/flight/airline-6.png": pillLogo("Aero", "#FFF3F4", "#F96768"),
    "assets/img/download/google-play.png": pillLogo("Google Play", "#111827", "#FFFFFF"),
    "assets/img/download/app-store.png": pillLogo("App Store", "#111827", "#FFFFFF"),
    "assets/img/partner/01.png": pillLogo("showe", "#FFFFFF", "#F97316"),
    "assets/img/partner/02.png": pillLogo("Tavlo", "#FFFFFF", "#24BDC7"),
    "assets/img/partner/03.png": pillLogo("Travel", "#FFFFFF", "#2563EB"),
    "assets/img/partner/04.png": pillLogo("Shavio", "#FFFFFF", "#EF4444"),
    "assets/img/payment/paypal.svg": pillLogo("PayPal", "#FFFFFF", "#003087"),
    "assets/img/payment/mastercard.svg": pillLogo("Mastercard", "#FFFFFF", "#EA580C"),
    "assets/img/payment/visa.svg": pillLogo("Visa", "#FFFFFF", "#1D4ED8"),
    "assets/img/payment/discover.svg": pillLogo("Discover", "#FFFFFF", "#F97316"),
    "assets/img/payment/american-express.svg": pillLogo("AmEx", "#FFFFFF", "#0EA5E9")
  };

  function normalize(path) {
    return path ? path.replace(/^\.?\//, "") : "";
  }

  function encodeAssetPath(path) {
    return encodeURI(path).replace(/\(/g, "%28").replace(/\)/g, "%29");
  }

  function swapImage(img) {
    var current = normalize(img.getAttribute("src"));
    if (imageMap[current]) {
      img.setAttribute("src", imageMap[current]);
      return;
    }
    if (svgMap[current]) {
      img.setAttribute("src", svgMap[current]);
    }
  }

  function replaceInlineBackgrounds() {
    document.querySelectorAll("[style]").forEach(function (node) {
      var style = node.getAttribute("style");
      if (!style) {
        return;
      }

      Object.keys(imageMap).forEach(function (key) {
        if (style.indexOf(key) !== -1) {
          style = style.split(key).join(encodeAssetPath(imageMap[key]));
        }
      });

      node.setAttribute("style", style);
    });
  }

  function backgroundFallbacks() {
    var backgroundNodes = [
      { selector: ".video-content", path: "assets/img/anh2.webp" }
    ];

    backgroundNodes.forEach(function (item) {
      document.querySelectorAll(item.selector).forEach(function (node) {
        node.style.backgroundImage = 'url("' + encodeAssetPath(item.path) + '")';
      });
    });
  }

  function makeSvg(viewBox, pathMarkup, stroke) {
    return (
      '<svg viewBox="' + viewBox + '" aria-hidden="true" focusable="false">' +
      pathMarkup +
      "</svg>"
    );
  }

  var iconMap = {
    "fa-facebook-f": makeSvg("0 0 24 24", '<path d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.5 1.6-1.5H17V5c-.4-.1-1.5-.2-2.8-.2-2.8 0-4.7 1.7-4.7 4.8V11H7v3h2.5v8z"/>'),
    "fa-x-twitter": makeSvg("0 0 24 24", '<path d="M18.9 3H22l-6.8 7.8L23 21h-6.1l-4.8-6.3L6.6 21H3.5l7.3-8.4L1.5 3h6.2l4.4 5.8z"/>'),
    "fa-instagram": makeSvg("0 0 24 24", '<path d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4m5 4.5A4.5 4.5 0 1 0 16.5 12 4.5 4.5 0 0 0 12 7.5m5.3-.9a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1M12 9a3 3 0 1 1-3 3 3 3 0 0 1 3-3"/>'),
    "fa-linkedin-in": makeSvg("0 0 24 24", '<path d="M5.5 8.5H2.8V21h2.7zM4.1 3A1.6 1.6 0 1 0 5.7 4.6 1.6 1.6 0 0 0 4.1 3m7.1 5.5H8.7V21h2.6v-6.6c0-1.7.3-3.3 2.4-3.3s2.1 1.9 2.1 3.4V21h2.6v-7.4c0-3.2-.7-5.6-4.4-5.6-1.8 0-3 .9-3.5 1.9z"/>'),
    "fa-phone-arrow-down-left": makeSvg("0 0 24 24", '<path d="M6.6 10.8a15.6 15.6 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.2 11.1 11.1 0 0 0 3.5.6 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.6a1 1 0 0 1 1 1 11.1 11.1 0 0 0 .6 3.5 1 1 0 0 1-.2 1zM14 4h6v6h-2V7.4l-4.3 4.3-1.4-1.4L16.6 6H14z"/>'),
    "fa-envelopes": makeSvg("0 0 24 24", '<path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h7A2.5 2.5 0 0 1 15 7.5v7a2.5 2.5 0 0 1-2.5 2.5h-7A2.5 2.5 0 0 1 3 14.5zm13-1A2.5 2.5 0 0 1 18.5 4h.5A2.5 2.5 0 0 1 21.5 6.5v7a2.5 2.5 0 0 1-2.5 2.5h-.5A2.5 2.5 0 0 1 16 13.5zm-11.7.2L9 10l4.7-3.3H4.3M17.5 7l2 1.5V6h-2z"/>'),
    "fa-sign-in": makeSvg("0 0 24 24", '<path d="M10 4h7a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-7v-2h7V6h-7zm1.3 3.3 1.4 1.4-1.8 1.8H18v2h-7.1l1.8 1.8-1.4 1.4L7 11.4z"/>'),
    "fa-user-tie": makeSvg("0 0 24 24", '<path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4m-2 1.5h4L13 16l1.8 5.5H9.2L11 16zm2 8.5H4v-1.3c0-3.2 2.8-5.7 6-5.7h4c3.2 0 6 2.5 6 5.7V22z"/>'),
    "fa-bars": makeSvg("0 0 24 24", '<path d="M4 7h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/>'),
    "fa-plane-departure": makeSvg("0 0 24 24", '<path d="M2 16v-2l8-3.5V4.5a1.5 1.5 0 1 1 3 0v6L22 14v2l-9-1.8V20l2 1v1.5l-3.5-1-3.5 1V21l2-1v-5.8z"/>'),
    "fa-plane-arrival": makeSvg("0 0 24 24", '<path d="M2 19h20v2H2zm0-5v-2l8-3.5V2.5a1.5 1.5 0 1 1 3 0v6L22 12v2l-9-1.8V18l2 1v1.5l-3.5-1-3.5 1V19l2-1v-5.8z"/>'),
    "fa-hotel": makeSvg("0 0 24 24", '<path d="M4 21V5h3v5h10a3 3 0 0 1 3 3v8h-2v-3H6v3zm3-9V7H6v5zm2 4h9v-3a1 1 0 0 0-1-1H9z"/>'),
    "fa-car-building": makeSvg("0 0 24 24", '<path d="M3 20V9h7v11zm9 0v-8h9v8zm2-12V4h5v4zm-9 0V4h5v4zM5 11h1v2H5zm0 4h1v2H5zm9-1h1.5a2.5 2.5 0 0 1 5 0H22v4h-1.5a2 2 0 0 1-4 0h-5a2 2 0 0 1-4 0H6v-4h1.5a2.5 2.5 0 0 1 5 0H14z"/>'),
    "fa-car": makeSvg("0 0 24 24", '<path d="M5.5 16A1.5 1.5 0 1 0 7 17.5 1.5 1.5 0 0 0 5.5 16m13 0a1.5 1.5 0 1 0 1.5 1.5 1.5 1.5 0 0 0-1.5-1.5M5 6h14l2.2 6.5V18h-1.7a3 3 0 0 1-5 0H9.7a3 3 0 0 1-5 0H3V12.5zm1.4 2L5 12h14l-1.4-4z"/>'),
    "fa-ship": makeSvg("0 0 24 24", '<path d="M12 2l5 3v6h2v4l-2 3a8.6 8.6 0 0 1-5-1.7A8.6 8.6 0 0 1 7 18L5 15v-4h2V5zm0 2.3L9 6v1h6V6zm-5 9v1l1.1 1.5a6.7 6.7 0 0 0 3.9-1.5 6.7 6.7 0 0 0 3.9 1.5L17 14.3v-1z"/>'),
    "fa-earth-americas": makeSvg("0 0 24 24", '<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m6.9 9h-3.1l-1-2.5 1.5-1.5A8.1 8.1 0 0 1 18.9 11M12 4a8 8 0 0 1 2.3.3L12.8 6H10L8.8 8 10 10v2l-2 2v3.7A8 8 0 0 1 12 4m-5.8 2.5L7.5 8 6 11H3.1a8.1 8.1 0 0 1 3.1-4.5M12 20a8 8 0 0 1-2-.3V15l2-2h2l2 2v2.7A8 8 0 0 1 12 20"/>'),
    "fa-calendar-days": makeSvg("0 0 24 24", '<path d="M7 2h2v2h6V2h2v2h2a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2zm12 8H5v8h14zM5 6v2h14V6z"/>'),
    "fa-user-tie-hair": makeSvg("0 0 24 24", '<path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4m-2.2 1h4.4L13 14.7 14.8 21H9.2l1.8-6.3zm2.2-8c2 0 3 1.1 3.4 2.4a4.8 4.8 0 0 0-6.8 0C9 5.1 10 4 12 4M4 22v-1.4c0-3 2.7-5.6 6-5.6h4c3.3 0 6 2.6 6 5.6V22z"/>'),
    "fa-plus-circle": makeSvg("0 0 24 24", '<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m5 11h-4v4h-2v-4H7v-2h4V7h2v4h4z"/>'),
    "fa-location-dot": makeSvg("0 0 24 24", '<path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7m0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5"/>'),
    "fa-clock": makeSvg("0 0 24 24", '<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m1 5h-2v6l5 3 1-1.7-4-2.3z"/>'),
    "fa-repeat": makeSvg("0 0 24 24", '<path d="M7 7h11l-2-2 1.4-1.4L22 8l-4.6 4.4L16 11l2-2H7a3 3 0 0 0-3 3v1H2v-1a5 5 0 0 1 5-5m10 10H6l2 2-1.4 1.4L2 16l4.6-4.4L8 13l-2 2h11a3 3 0 0 0 3-3v-1h2v1a5 5 0 0 1-5 5"/>'),
    "fa-plane": makeSvg("0 0 24 24", '<path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9L2 14v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5z"/>'),
    "fa-arrow-right": makeSvg("0 0 24 24", '<path d="M13 5l7 7-7 7-1.4-1.4 4.6-4.6H4v-2h12.2l-4.6-4.6z"/>'),
    "fa-backpack": makeSvg("0 0 24 24", '<path d="M9 5a3 3 0 0 1 6 0h2a3 3 0 0 1 3 3v9a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8a3 3 0 0 1 3-3zm2 0h2a1 1 0 0 0-2 0m-3 6h8v2H8z"/>'),
    "fa-star": makeSvg("0 0 24 24", '<path d="M12 3.5l2.6 5.3 5.8.9-4.2 4.1 1 5.8L12 16.8l-5.2 2.8 1-5.8L3.6 9.7l5.8-.9z"/>'),
    "fa-heart": makeSvg("0 0 24 24", '<path d="M12 21s-7-4.6-9.5-9A5.7 5.7 0 0 1 12 5.3 5.7 5.7 0 0 1 21.5 12C19 16.4 12 21 12 21"/>'),
    "fa-exchange": makeSvg("0 0 24 24", '<path d="M7 7h10l-2.5-2.5L16 3l5 5-5 5-1.5-1.5L17 9H7zm10 10H7l2.5 2.5L8 21l-5-5 5-5 1.5 1.5L7 15h10z"/>'),
    "fa-play": makeSvg("0 0 24 24", '<path d="M8 5v14l11-7z"/>'),
    "fa-tag": makeSvg("0 0 24 24", '<path d="M3 11V4h7l10 10-7 7zm5-5a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8 6"/>'),
    "fa-plus": makeSvg("0 0 24 24", '<path d="M11 5h2v14h-2zm-6 6h14v2H5z"/>'),
    "fa-minus": makeSvg("0 0 24 24", '<path d="M5 11h14v2H5z"/>'),
    "fa-comments": makeSvg("0 0 24 24", '<path d="M4 5h11a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H9l-4 3v-3H4a3 3 0 0 1-3-3V8a3 3 0 0 1 3-3m15 2h1a3 3 0 0 1 3 3v9l-4-2h-2a3 3 0 0 1-3-3v-1h1a4 4 0 0 0 4-4z"/>'),
    "fa-lock": makeSvg("0 0 24 24", '<path d="M7 10V8a5 5 0 0 1 10 0v2h1a2 2 0 0 1 2 2v8H4v-8a2 2 0 0 1 2-2zm2 0h6V8a3 3 0 0 0-6 0z"/>'),
    "fa-paper-plane": makeSvg("0 0 24 24", '<path d="M2 12 21 3l-4.5 18-5.3-6.2-4.7 2.9 1.5-5.3z"/>'),
    "fa-youtube": makeSvg("0 0 24 24", '<path d="M21 8.5a3 3 0 0 0-2.1-2.1C17 6 12 6 12 6s-5 0-6.9.4A3 3 0 0 0 3 8.5 31.6 31.6 0 0 0 3 12a31.6 31.6 0 0 0 .1 3.5 3 3 0 0 0 2.1 2.1C7 18 12 18 12 18s5 0 6.9-.4a3 3 0 0 0 2.1-2.1A31.6 31.6 0 0 0 21 12a31.6 31.6 0 0 0-.1-3.5M10 15V9l5 3z"/>'),
    "fa-search": makeSvg("0 0 24 24", '<path d="M10 4a6 6 0 1 0 4.2 10.2l4.3 4.3 1.4-1.4-4.3-4.3A6 6 0 0 0 10 4m0 2a4 4 0 1 1-4 4 4 4 0 0 1 4-4"/>'),
    "fa-check": makeSvg("0 0 24 24", '<path d="M9.5 16.2 5.3 12l-1.4 1.4 5.6 5.6L20.1 8.4 18.7 7z"/>'),
    "fa-share-alt": makeSvg("0 0 24 24", '<path d="M18 16a3 3 0 0 0-2.4 1.2l-6-3a3.5 3.5 0 0 0 0-1.4l6-3A3 3 0 1 0 15 8a3.5 3.5 0 0 0 .1.9l-6 3a3 3 0 1 0 0 4.2l6 3A3 3 0 1 0 18 16"/>'),
    "fa-angle-up": makeSvg("0 0 24 24", '<path d="M6.7 14.7 12 9.4l5.3 5.3 1.4-1.4L12 6.6l-6.7 6.7z"/>'),
    "fa-angle-left": makeSvg("0 0 24 24", '<path d="M14.7 6.7 9.4 12l5.3 5.3-1.4 1.4L6.6 12l6.7-6.7z"/>'),
    "fa-angle-right": makeSvg("0 0 24 24", '<path d="M9.3 6.7 14.6 12l-5.3 5.3 1.4 1.4 6.7-6.7-6.7-6.7z"/>'),
    "fa-long-arrow-left": makeSvg("0 0 24 24", '<path d="M10 6 4 12l6 6 1.4-1.4L7.8 13H20v-2H7.8l3.6-3.6z"/>'),
    "fa-long-arrow-right": makeSvg("0 0 24 24", '<path d="M14 6l-1.4 1.4 3.6 3.6H4v2h12.2l-3.6 3.6L14 18l6-6z"/>'),
    "fa-arrow-circle-right": makeSvg("0 0 24 24", '<path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2m1 5-1.4 1.4 2.6 2.6H7v2h7.2l-2.6 2.6L13 17l5-5z"/>'),
    "fa-map-marker-alt": makeSvg("0 0 24 24", '<path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7m0 9.5A2.5 2.5 0 1 1 14.5 9 2.5 2.5 0 0 1 12 11.5"/>'),
    "fa-headset": makeSvg("0 0 24 24", '<path d="M12 4a8 8 0 0 0-8 8v4a3 3 0 0 0 3 3h2v-6H6v-1a6 6 0 0 1 12 0v1h-3v6h2a3 3 0 0 0 3-3v-4a8 8 0 0 0-8-8"/>'),
    "fa-gas-pump": makeSvg("0 0 24 24", '<path d="M6 3h8a2 2 0 0 1 2 2v14H4V5a2 2 0 0 1 2-2m0 3v4h8V6zm12 2 2 2v9a2 2 0 0 1-2 2h-1v-2h1V11.5l-1.4-1.4A2 2 0 0 1 16 8.7V6h2z"/>'),
    "fa-steering-wheel": makeSvg("0 0 24 24", '<path d="M12 4a8 8 0 1 0 8 8 8 8 0 0 0-8-8m0 2a6 6 0 0 1 5.7 4H6.3A6 6 0 0 1 12 6m-5.7 6h4.7v6a6 6 0 0 1-4.7-6m6.7 6v-6h4.7a6 6 0 0 1-4.7 6"/>'),
    "fa-person-biking-mountain": makeSvg("0 0 24 24", '<path d="M8 6.5A1.5 1.5 0 1 0 9.5 8 1.5 1.5 0 0 0 8 6.5M6 18a3 3 0 1 0 0-2h2l1.5-4 1.8 2H14a3 3 0 1 0 2.8-2h-3.2l-2.1-2.6A2 2 0 0 0 9.9 9H8l-1 3H5l-2 4h2.2A3 3 0 0 0 6 18m12 0a1 1 0 1 1-1-1 1 1 0 0 1 1 1"/>')
  };

  function renderSvgIcons() {
    document.querySelectorAll("i, span").forEach(function (icon) {
      var classes = Array.from(icon.classList);
      var iconClass = classes.find(function (cls) {
        return cls.indexOf("fa-") === 0 && iconMap[cls];
      });

      if (!iconClass) {
        return;
      }

      icon.classList.add("svg-icon");
      if (/fa-phone|fa-plane|fa-arrow|fa-repeat|fa-location|fa-calendar|fa-clock|fa-check|fa-search/.test(iconClass)) {
        icon.classList.add("icon-stroke");
      }
      icon.setAttribute("aria-hidden", "true");
      icon.innerHTML = iconMap[iconClass];
    });
  }

  function renderSvgIconsIn(root) {
    if (!root) {
      return;
    }

    root.querySelectorAll("i, span").forEach(function (icon) {
      var classes = Array.from(icon.classList);
      var iconClass = classes.find(function (cls) {
        return cls.indexOf("fa-") === 0 && iconMap[cls];
      });

      if (!iconClass) {
        return;
      }

      icon.classList.add("svg-icon");
      if (/fa-phone|fa-plane|fa-arrow|fa-repeat|fa-location|fa-calendar|fa-clock|fa-check|fa-search|fa-angle/.test(iconClass)) {
        icon.classList.add("icon-stroke");
      }
      icon.setAttribute("aria-hidden", "true");
      icon.innerHTML = iconMap[iconClass];
    });
  }

  window.renderSvgIconsIn = renderSvgIconsIn;

  function disableMissingPageLinks() {
    document.querySelectorAll('a[href$=".html"]').forEach(function (link) {
      var href = link.getAttribute("href");
      if (!href || href === "index.html") {
        return;
      }

      link.setAttribute("href", "#");
      link.setAttribute("data-disabled-page-link", "true");
      link.classList.add("is-disabled-link");
    });
  }

  function installErrorFallbacks() {
    document.querySelectorAll("img").forEach(function (img) {
      swapImage(img);
      img.addEventListener("error", function () {
        swapImage(img);
        if (!img.getAttribute("src")) {
          img.style.visibility = "hidden";
        }
      });
    });
  }

  function rebuildSlider(selector, options) {
    if (!window.jQuery || !jQuery.fn || !jQuery.fn.owlCarousel) {
      return;
    }

    var $slider = jQuery(selector);
    if (!$slider.length) {
      return;
    }

    if ($slider.hasClass("owl-loaded")) {
      $slider.trigger("destroy.owl.carousel");
      $slider.removeClass("owl-loaded owl-hidden");
      $slider.find(".owl-stage-outer").children().unwrap();
      $slider.find(".owl-stage").children().unwrap();
      $slider.find(".owl-item").children().unwrap();
    }

    $slider.owlCarousel(options);
    renderSvgIconsIn($slider.get(0));
  }


  document.addEventListener("DOMContentLoaded", function () {
    installErrorFallbacks();
    replaceInlineBackgrounds();
    backgroundFallbacks();
    renderSvgIcons();
    disableMissingPageLinks();
    rebuildSlider(".hotel-slider", {
      loop: true,
      margin: 20,
      nav: true,
      dots: true,
      center: false,
      autoplay: false,
      smartSpeed: 600,
      navText: [
        "<i class='far fa-angle-left'></i>",
        "<i class='far fa-angle-right'></i>"
      ],
      responsive: {
        0: { items: 1 },
        576: { items: 2 },
        992: { items: 3 },
        1200: { items: 4 }
      }
    });
    rebuildSlider(".car-slider", {
      loop: true,
      margin: 20,
      nav: true,
      dots: true,
      center: false,
      autoplay: false,
      smartSpeed: 600,
      navText: [
        "<i class='far fa-angle-left'></i>",
        "<i class='far fa-angle-right'></i>"
      ],
      responsive: {
        0: { items: 1 },
        576: { items: 2 },
        992: { items: 3 },
        1200: { items: 4 }
      }
    });
    rebuildSlider(".banner-slider", {
      loop: true,
      margin: 20,
      nav: true,
      dots: true,
      autoplay: false,
      smartSpeed: 600,
      navText: [
        "<i class='far fa-angle-left'></i>",
        "<i class='far fa-angle-right'></i>"
      ],
      responsive: {
        0: { items: 1 },
        768: { items: 2 },
        1200: { items: 3 }
      }
    });
    document.addEventListener("click", function (event) {
      var disabledLink = event.target.closest('a[data-disabled-page-link="true"]');
      if (disabledLink) {
        event.preventDefault();
      }

      var categoryParentLink = event.target.closest(".navbar .dropdown > .dropdown-toggle, .navbar .dropdown-submenu > .dropdown-toggle");
      if (categoryParentLink && window.innerWidth >= 992) {
        var href = categoryParentLink.getAttribute("href");
        if (href && href !== "#") {
          event.preventDefault();
          event.stopPropagation();
          window.location.href = href;
        }
      }
    });
  });
})();
