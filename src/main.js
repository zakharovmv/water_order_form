import Vue from "vue";
import App from "./App.vue";
import store from "./store";
import langFilter from "@/filters/lang.filter";
import messagePlugin from "@/utils/message.plugin";
import Loader from "@/components/app/Loader";
import "materialize-css/dist/js/materialize.min.js";

Vue.config.productionTip = false;

Vue.use(messagePlugin);
Vue.filter("lang", langFilter);
Vue.component("Loader", Loader);

new Vue({
  store,
  render: h => h(App)
}).$mount("#app");
