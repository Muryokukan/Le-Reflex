import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["btn"];

  connect() {
    this.hideButtonIfAtTop();
    window.addEventListener("scroll", this.checkScrollPosition.bind(this));
  }

  disconnect() {
    window.removeEventListener("scroll", this.checkScrollPosition.bind(this));
  }

  checkScrollPosition() {
    this.hideButtonIfAtTop();
  }

  hideButtonIfAtTop() {
    if (window.scrollY === 0) {
      this.btnTarget.classList.add("hidden");
    } else {
      this.btnTarget.classList.remove("hidden");
    }
  }

  scrollToTop() {
    window.scrollTo({
      top: 0,
      left: 0,
      behavior: "smooth",
    });
  }
}
