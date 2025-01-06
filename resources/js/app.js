import "./bootstrap";

import { createApp } from "vue";
import { Dialog, Notify, Quasar } from "quasar";
import { createPinia } from "pinia";

import router from "./router";
import api from "./boot/api";

// Import icon libraries
import "@quasar/extras/roboto-font/roboto-font.css";
import "@quasar/extras/material-icons/material-icons.css";
import "@quasar/extras/fontawesome-v6/fontawesome-v6.css";

// Import Quasar css
import "quasar/src/css/index.sass";
import "../scss/quasar.scss";

import App from "./App.vue";

const app = createApp(App);

app.use(Quasar, {
  plugins: {
    Dialog: Dialog,
    Notify: Notify,
  }, // import Quasar plugins and add here

  config: {
    notify: {
      position: "bottom-right",
    },
  },
});

const pinia = createPinia();

app.use(pinia);
app.use(router);
app.use(api);

app.mount("#app");

app.config.errorHandler = (err, vm, info) => {
  console.error(err, vm, info);
};
