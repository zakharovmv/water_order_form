<template>
  <div class="modal" ref="modal">
    <div class="modal-content">
      <h4>{{ "YourCity" | lang }} {{ location }}?</h4>
      <p v-if="!finded">{{ "WaterbasesNoFindedText" | lang }}</p>
      <p v-else>{{ "WaterbasesFindedText" | lang }} {{ waterbasesCount }}.</p>
    </div>
    <div class="modal-footer" v-if="finded">
      <a
        href="#!"
        class="modal-close btn blue"
        @click="cansel"
        style="margin-right:10px;"
        >{{ "LocationChooseNo" | lang }}</a
      >
      <a href="#!" class="modal-close btn blue" @click="choose">{{
        "LocationChooseYes" | lang
      }}</a>
    </div>
    <div class="modal-footer" v-else>
      <a href="#!" class="modal-close btn blue" @click="cansel">{{
        "SelfChoose" | lang
      }}</a>
    </div>
  </div>
</template>

<script>
import M from "materialize-css";
import { mapMutations } from "vuex";

export default {
  props: {
    location: {
      required: true
    }
  },
  data: () => ({
    instanse: null,
    finded: false,
    waterbasesCount: null
  }),
  mounted() {
    M.Modal.init(this.$refs.modal, {});
    this.instanse = M.Modal.getInstance(this.$refs.modal);
    this.instanse.open();

    let region = this.$store.state.WaterOrderForm.regions.list.find(r =>{
      return r.area_names.includes(this.location);
    });

    if (region && region.uuid) {
      this.finded = true;
      let waterbases = this.$store.state.WaterOrderForm.waterbases.list.filter(
        w => {
          return w.region_uuid == region.uuid;
        }
      );
      this.waterbasesCount = waterbases.length;
    }
  },
  methods: {
    ...mapMutations(["setRegion"]),
    choose() {
      let region = this.$store.state.WaterOrderForm.regions.list.find(r =>{
        return r.area_names.includes(this.location);
      });
      this.setRegion(region.uuid);

      localStorage.setItem("location", region.uuid);
      localStorage.setItem("closeLocation", 1);
    },
    cansel() {
      localStorage.setItem("closeLocation", 1);
    }
  }
};
</script>
