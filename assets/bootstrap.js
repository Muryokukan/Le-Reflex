import { startStimulusApp } from "@symfony/stimulus-bundle";
import RoomReservationDateController from "./controllers/room_reservation_date_controller.js";
import RoomReservationOptionsController from "./controllers/room_reservation_options_controller.js";

const app = startStimulusApp();
app.register("room-reservation-calendar", RoomReservationDateController);
app.register("room-reservation-options", RoomReservationOptionsController);