import axios from "axios";
import langFilter from "@/filters/lang.filter";
import config from "@/system/config";

export default {
  actions: {
    async fetchList({ commit }, list) {
      axios
        .get(config.api_url + list)
        .then(res => {
          const data = res.data.data;
          commit(list + "Update", data);
        })
        .catch(e => {
          commit("setError", e);
        });
    },
    async fetchRegions({ dispatch }) {
      dispatch("fetchList", "regions");
    },
    async fetchWaterbases({ dispatch }) {
      dispatch("fetchList", "waterbases");
    },
    async createOrder({ commit }, order) {
      axios
        .post(config.api_url + "order", order)
        .then(res => {
          if (res.data.status === "ok") {
            commit("setMessage", langFilter("CreateOrderSuccess"));
          } else {
            commit(
              "setError",
              res.data.error || langFilter("CreateOrderSuccess")
            );
          }
        })
        .catch(e => {
          commit("setError", e);
        });
    },
    async fetchLocation({ commit }) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
          let coords = {
            latitude: pos.coords.latitude,
            longitude: pos.coords.longitude
          };
          commit("setCoords", coords);

          // test coords for Saint Petersburg
          // coords.longitude = 30.311698;
          // coords.latitude = 59.868134;

          const url =
            config.yandex_maps_api_url +
            "?apikey=" +
            config.yandex_maps_api_key +
            "&format=json&kind=locality&results=1&geocode=" +
            [coords.longitude, coords.latitude].join();

          axios
            .get(url)
            .then(response => {
              const members =
                response.data.response.GeoObjectCollection.featureMember;
              commit("setLocation", members[0].GeoObject.name);
            })
            .catch(e => {
              commit("setError", e);
            });
        });
      }
    }
  },
  mutations: {
    setRegion(s, id) {
      s.regions.current = id;
    },
    regionsUpdate(s, regions) {
      s.regions.list = regions;
    },
    waterbasesUpdate(s, waterbases) {
      s.waterbases.list = waterbases;
    }
  },
  state: {
    regions: {
      current: localStorage.getItem("location") || null,
      list: []
    },
    waterbases: {
      current: null,
      list: []
    }
  },
  getters: {
    regions: s => s.regions.list,
    waterbases: s => {
      // filter waterbases list to get cases for current region
      return s.waterbases.list.filter(w => {
        return w.region_uuid === s.regions.current;
      });
    },
    currentRegion: s => s.regions.current
  }
};
