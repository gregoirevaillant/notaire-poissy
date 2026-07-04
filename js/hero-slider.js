const slides = document.querySelectorAll(".hero-slide");
const dots = document.querySelectorAll(".hero-dots .dot");
let cur = 0;
function go(i) {
  slides[cur].classList.remove("active");
  dots[cur].classList.remove("active");
  cur = i;
  slides[cur].classList.add("active");
  dots[cur].classList.add("active");
}
dots.forEach((d) => d.addEventListener("click", () => go(+d.dataset.i)));
setInterval(() => go((cur + 1) % slides.length), 5000);
