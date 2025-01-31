import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    events: Array,
  };
  static targets = [
    "calendar",
    "reservationDateInput",
    "reservationDateDisplay",
  ];

  connect() {
    let selectedDateEvent = null;

    const dateClick = (info) => {
      const clickedDate = new Date(info.dateStr);
      const isDateTaken = this.eventsValue.some((event) => {
        if (event.type === "closure") {
          const start = new Date(event.start);
          const end = new Date(event.end);
          return clickedDate >= start && clickedDate < end;
        } else if (event.type === "reservation") {
          return event.start === info.dateStr;
        }
      });

      if (isDateTaken) {
        return;
      }

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

      this.reservationDateInputTarget.value = info.dateStr;
      this.reservationDateDisplayTarget.innerText =
        clickedDate.toLocaleDateString("fr-FR", {
          weekday: "long",
          day: "numeric",
          month: "long",
          year: "numeric",
        });
    };

    const calendarOptions = {
      initialView: "dayGridMonth",
      locale: "fr",
      firstDay: 1,
      events: this.eventsValue,
      validRange: {
        start: new Date().toISOString().split("T")[0],
      },
      eventBackgroundColor: "#ffcccc",
      eventBorderColor: "#ff0000",
      headerToolbar: {
        right: "prev,next",
      },
      dateClick,
    };

    this.calendar = new FullCalendar.Calendar(
      this.calendarTarget,
      calendarOptions
    );
    this.calendar.render();
  }

  disconnect() {
    if (this.calendar) {
      this.calendar.destroy();
    }
  }
}
