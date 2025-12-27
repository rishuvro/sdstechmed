(() => {
  // Always show hero even if animations fail
  const forceVisible = () => {
    document.querySelectorAll(".hero, .hero *").forEach(el => {
      el.style.opacity = "";
      el.style.transform = "";
      el.style.visibility = "";
    });
  };

  // Run ASAP
  forceVisible();

  // AOS (animate on scroll) - MUST run first
  try {
    if (window.AOS) {
      AOS.init({
        duration: 850,
        easing: "ease-out-cubic",
        offset: 70,
        once: true
      });
    }
  } catch (e) {
    console.log("AOS init error:", e);
    forceVisible();
  }

  // GSAP
  try {
    if (window.gsap) {
      gsap.from(".hero .hero__content > *", {
        y: 18, opacity: 0, duration: 0.9, ease: "power3.out", stagger: 0.08
      });
      gsap.from(".hero .hero__media", {
        x: 18, opacity: 0, duration: 0.9, ease: "power3.out", delay: 0.12
      });
    }
  } catch (e) {
    console.log("GSAP error:", e);
    forceVisible();
  }

  // Tilt
  try {
    if (window.VanillaTilt) {
      VanillaTilt.init(document.querySelectorAll(".js-tilt"), {
        max: 10,
        speed: 500,
        glare: true,
        "max-glare": 0.16,
        scale: 1.02
      });
    }
  } catch (e) {
    console.log("Tilt error:", e);
  }

  // Swiper (safe, no loop)
  try {
    if (window.Swiper) {
      const el = document.querySelector(".swiper-featured");
      if (el) {
        new Swiper(el, {
          slidesPerView: 1.1,
          spaceBetween: 14,
          loop: false,
          watchOverflow: true,
          pagination: { el: ".swiper-pagination", clickable: true },
          navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
          breakpoints: {
            560: { slidesPerView: 2.1 },
            900: { slidesPerView: 3.1 },
            1200:{ slidesPerView: 4.1 }
          }
        });
      }
    }
  } catch (e) {
    console.log("Swiper error:", e);
  }
})();
