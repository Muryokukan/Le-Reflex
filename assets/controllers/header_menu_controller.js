import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["mobileMenu"];

  connect() {}

  toggle(event) {
    event.stopPropagation();
    if (this.mobileMenuTarget.classList.contains("hidden")) {
      this.mobileMenuTarget.classList.remove("hidden");
      this.mobileMenuTarget.style.display = "flex";
    } else {
      this.mobileMenuTarget.classList.add("hidden");
      this.mobileMenuTarget.style.display = "none";
    }
  }

  clickOutside(event) {
    if (
      !this.mobileMenuTarget.classList.contains("hidden") &&
      !event.target.closest("#mobile-menu-toggle") &&
      !this.mobileMenuTarget.contains(event.target)
    ) {
      this.mobileMenuTarget.classList.add("hidden");
      this.mobileMenuTarget.style.display = "none";
    }
  }
}
