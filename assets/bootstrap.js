import { startStimulusApp } from "@symfony/stimulus-bundle";
import HeaderMenuController from "./controllers/header_menu_controller.js";
import RoomReservationDateController from "./controllers/room_reservation_date_controller.js";
import RoomReservationOptionsController from "./controllers/room_reservation_options_controller.js";

const app = startStimulusApp();
app.register("header-menu", HeaderMenuController);
app.register("room-reservation-calendar", RoomReservationDateController);
app.register("room-reservation-options", RoomReservationOptionsController);