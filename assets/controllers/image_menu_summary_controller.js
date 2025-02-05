import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["summary"];

  timeout = null;
  breakpoint = 768; // md breakpoint of Tailwind

  connect() {
    this.handleResize();
    window.addEventListener("resize", this.handleResize.bind(this));
  }

  disconnect() {
    window.removeEventListener("scroll", this.showSummary.bind(this));
    window.removeEventListener("resize", this.handleResize.bind(this));
    if (this.timeout) {
      clearTimeout(this.timeout);
    }
  }

  handleResize() {
    if (window.innerWidth < this.breakpoint) {
      this.summaryTarget.style.display = "flex";
      this.summaryTarget.style.opacity = "25%";
      window.addEventListener("scroll", this.showSummary.bind(this));
    } else {
      this.summaryTarget.style.display = "flex";
      this.summaryTarget.style.opacity = "75%";
      window.removeEventListener("scroll", this.showSummary.bind(this));
    }
  }

  showSummary(event) {
    if (window.innerWidth < this.breakpoint) {
      if (this.timeout) {
        clearTimeout(this.timeout);
      }

      const duration = event?.params?.duration || 2500;

      this.summaryTarget.style.opacity = "75%";

      this.timeout = setTimeout(() => {
        this.hideSummary();
      }, duration);
    }
  }

  hideSummary() {
    if (window.innerWidth < this.breakpoint) {
      this.summaryTarget.style.opacity = "25%";
    }
  }
}
