<template>
  <div id="app">
    <div class="container">
      <GeoLocation
        :location="this.$store.state.location"
        v-if="isGeoLocation"
      />
      <WaterOrderForm :key="sended" />
    </div>
  </div>
</template>

<script>
import WaterOrderForm from "./components/app/WaterOrderForm";
import GeoLocation from "./components/app/GeoLocation";
import langFilter from "@/filters/lang.filter";
import { mapGetters, mapActions } from "vuex";

export default {
  data: () => ({
    sended: false
  }),
  components: {
    WaterOrderForm,
    GeoLocation
  },
  mounted() {
    this.fetchLocation();
  },
  computed: {
    ...mapGetters(["error", "message"]),
    isGeoLocation() {
      return (
        this.$store.state.location && !localStorage.getItem("closeLocation")
      );
    }
  },
  watch: {
    error() {
      this.$error(this.$store.state.error || langFilter("DefaultErrorMessage"));
    },
    message() {
      this.$message(this.$store.state.message);
    }
  },
  methods: {
    ...mapActions(["fetchLocation"])
  }
};
</script>

<style lang="scss">
@import "assets/css/style.scss";
@import "~materialize-css/dist/css/materialize.min.css";
</style>
