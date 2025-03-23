document.addEventListener("DOMContentLoaded", function () {
    // Smooth Scrolling for Navigation
    document.querySelectorAll("nav a").forEach(anchor => {
      anchor.addEventListener("click", function (e) {
        if (this.getAttribute("href").startsWith("#")) {
          e.preventDefault();
          document.querySelector(this.getAttribute("href")).scrollIntoView({
            behavior: "smooth"
          });
        }
      });
    });
  
    // Fade-in Animation on Scroll
    const fadeElements = document.querySelectorAll(".fade-in");
    function fadeInOnScroll() {
      fadeElements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight * 0.8) {
          el.classList.add("opacity-100", "translate-y-0");
        }
      });
    }
    window.addEventListener("scroll", fadeInOnScroll);
    fadeInOnScroll();
  
    // Button Hover Effects
    document.querySelectorAll("a.bg-blue-500").forEach(button => {
      button.addEventListener("mouseenter", () => {
        button.classList.add("bg-yellow-500");
      });
      button.addEventListener("mouseleave", () => {
        button.classList.remove("bg-yellow-500");
      });
    });
  
    // Improved Slideshow Navigation
    let slides = document.querySelectorAll(".mySlides");
    let slideIndex = 0;
  
    function showSlides() {
      slides.forEach(slide => slide.classList.add("hidden"));
      slideIndex = (slideIndex + 1) % slides.length;
      slides[slideIndex].classList.remove("hidden");
    }
    setInterval(showSlides, 3000);
  
    document.querySelectorAll(".slideshow-container button").forEach(btn => {
      btn.addEventListener("click", () => {
        showSlides();
      });
    });
  });
  