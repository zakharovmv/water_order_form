import Vue from "vue";
import Vuex from "vuex";
import WaterOrderForm from "./modules/WaterOrderForm";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    error: null,
    message: null,
    coords: {
      latitude: null,
      longitude: null
    },
    location: null
  },
  mutations: {
    setLocation(s, location) {
      s.location = location;
    },
    setCoords(s, coords) {
      s.coords = coords;
    },
    setMessage(s, message) {
      s.message = message;
    },
    clearMessage(s) {
      s.message = null;
    },
    setError(s, error) {
      s.error = error;
    },
    clearError(s) {
      s.error = null;
    }
  },
  getters: {
    error: s => s.error,
    message: s => s.message
  },
  modules: {
    WaterOrderForm
  }
});
