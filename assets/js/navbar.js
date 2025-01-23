const toggleButton = document.getElementById("mobile-menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");

toggleButton.addEventListener("click", () => {
  mobileMenu.classList.toggle("hidden");
  mobileMenu.classList.toggle("flex");
});

document.addEventListener("click", (event) => {
  if (
    !mobileMenu.classList.contains("hidden") &&
    !toggleButton.contains(event.target) &&
    !mobileMenu.contains(event.target)
  ) {
    mobileMenu.classList.add("hidden");
    mobileMenu.classList.remove("flex");
  }
});
