import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = ["room", "option"];

  connect() {
    this.initializeRoomOptionsHandling();
  }

  initializeRoomOptionsHandling() {
    this.roomOptionsMap = new Map();

    this.roomTargets.forEach((roomInput) => {
      const roomId = roomInput.value;
      const options = JSON.parse(roomInput.dataset.options || "[]");
      this.roomOptionsMap.set(roomId, new Set(options));
    });

    this.roomTargets.forEach((roomInput) => {
      roomInput.addEventListener("change", () => this.handleRoomSelection());
    });

    this.optionTargets.forEach((optionInput) => {
      optionInput.addEventListener("change", () => this.updateTotalPrice());
    });

    this.handleRoomSelection();
  }

  handleRoomSelection() {
    const selectedRooms = this.roomTargets.filter((room) => room.checked);
    const availableOptions = new Set();

    selectedRooms.forEach((room) => {
      const roomOptions = this.roomOptionsMap.get(room.value);
      if (roomOptions) {
        roomOptions.forEach((optionId) => availableOptions.add(optionId));
      }
    });

    this.optionTargets.forEach((optionInput) => {
      const optionId = Number(optionInput.value);
      const isAvailable = availableOptions.has(optionId);
      optionInput.disabled = !isAvailable;

      if (!isAvailable && optionInput.checked) {
        optionInput.checked = false;
      }

      const optionLabel = optionInput.closest("label");
      if (optionLabel) {
        optionLabel.classList.toggle("opacity-50", !isAvailable);
        optionLabel.classList.toggle("cursor-not-allowed", !isAvailable);
        optionLabel.classList.toggle("cursor-pointer", isAvailable);
      }
    });

    this.updateTotalPrice();
  }

  updateTotalPrice() {
    let total = 0;

    this.roomTargets.forEach((room) => {
      if (room.checked) {
        total += Number(room.dataset.price || 0);
      }
    });

    // TODO: Allow possibility to change the amount of reduction
    // Reduction for location of the 2 rooms
    const totalPriceOfAllRooms = this.roomTargets.reduce((acc, curr) => {
      return acc + Number(curr.dataset.price || 0);
    },0);
    if (total == totalPriceOfAllRooms) {
      total -= 100;
    }

    this.optionTargets.forEach((option) => {
      if (option.checked && !option.disabled) {
        total += Number(option.dataset.price || 0);
      }
    });

    const totalPriceElement = this.element.querySelector("#totalPrice");
    if (totalPriceElement) {
      totalPriceElement.textContent = `${total} €`;
    }
  }
}
