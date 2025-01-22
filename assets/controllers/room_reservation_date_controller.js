import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    reservations: Array,
    reservationDateId: String,
  };

  connect() {
    const calendarEl = this.element.querySelector("#calendar");

    const reservationDateInput = document.getElementById(
      this.reservationDateIdValue
    );
    const reservationDateDisplay = document.getElementById(
      "reservationDateDisplay"
    );
    let selectedDateEvent = null;

    const calendarOptions = {
      initialView: "dayGridMonth",
      locale: "fr",
      firstDay: 1,
      events: this.reservationsValue,
      validRange: {
        start: new Date().toISOString().split("T")[0],
      },
      eventBackgroundColor: "#ffcccc",
      eventBorderColor: "#ff0000",
      headerToolbar: {
        right: "prev,next",
      },
      dateClick: (info) => {
        const isDateTaken = this.reservationsValue.some(
          (event) => event.start === info.dateStr
        );

        if (isDateTaken) return;

        if (selectedDateEvent) {
          this.calendar.getEventById(selectedDateEvent.id)?.remove();
        }

        selectedDateEvent = this.calendar.addEvent({
          id: "selected-date",
          start: info.dateStr,
          end: info.dateStr,
          display: "background",
          backgroundColor: "#16A34A",
        });

        reservationDateInput.value = info.dateStr;
        reservationDateDisplay.innerText = info.date.toLocaleDateString(
          "fr-FR",
          {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric",
          }
        );
      },
    };

    this.calendar = new FullCalendar.Calendar(calendarEl, calendarOptions);
    this.calendar.render();
  }

  disconnect() {
    if (this.calendar) {
      this.calendar.destroy();
    }
  }
}
