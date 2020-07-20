<template>
  <div class="section">
    <div class="row">
      <div class="col s12 center-align">
        <h2>{{ "WaterOrderFormTitle" | lang }}</h2>
        <p>{{ "WaterOrderFormDescription" | lang }}</p>
      </div>
    </div>
    <Loader v-if="loading" />
    <form class="form" :class="[!!loading && 'blur']" @submit.prevent="request">
      <div class="form-fields">
        <div class="row">
          <div class="col s12">
            <Select
              name="region"
              v-model="values.region"
              v-on:change="onRegionSelect"
              :error="errors.region"
              :options="regions"
              v-bind:label="'RegionTitle' | lang"
              v-bind:empty="'RegionChoose' | lang"
              icon="location_city"
              :current="currentRegion"
            />
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <Select
              name="waterbase"
              v-model="values.waterbase"
              v-on:change="onWaterbaseSelect"
              :error="errors.waterbase"
              :options="waterbases"
              v-bind:label="'WaterbaseTitle' | lang"
              v-bind:empty="'WaterbaseChoose' | lang"
              icon="format_color_fill"
            />
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <Input
              v-model="values.quantity"
              type="text"
              v-bind:label="'QuantityTitle' | lang"
              name="quantity"
              icon="exposure_plus_1"
              @validate="validate('quantity')"
              :error="errors.quantity"
            />
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <Input
              v-model="values.address"
              type="text"
              v-bind:label="'AddressTitle' | lang"
              name="address"
              icon="place"
              @validate="validate('address')"
              :error="errors.address"
            />
          </div>
        </div>
        <div class="row">
          <div class="col s12">
            <Datepicker
              v-model="values.date"
              v-bind:label="'DateTitle' | lang"
              name="date"
              icon="date_range"
              @validate="validate('date')"
              :error="errors.date"
            />
          </div>
        </div>
        <!--
        <div class="row">
          <div class="file-field input-field col s12">
            <div class="btn blue" :disabled="loading">
              <span>{{ "FileChoose" | lang }}</span>
              <input type="file" name="documents" multiple />
            </div>
            <div class="file-path-wrapper">
              <input
                class="file-path validate"
                type="text"
                v-bind:placeholder="'FileTitle' | lang"
              />
            </div>
            <small
              class="helper-text"
              :class="[!!errors.documents && 'red-text']"
              v-if="!!errors.documents"
              >{{ errors.documents }}</small
            >
          </div>
        </div>-->
        <div class="row">
          <div class="input-field col s12 center-align">
            <button type="submit" class="btn blue" :disabled="loading">
              {{ "SubmitTitle" | lang }}<i class="material-icons right">send</i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import Input from "./field/Input";
import Select from "./field/Select";
import Datepicker from "./field/Datepicker";
import langFilter from "@/filters/lang.filter";

import { mapActions, mapMutations, mapGetters } from "vuex";

let yup = require("yup");

let currentDate = new Date();
currentDate.setHours(0, 0, 0, 0);

const validationSchema = yup.object({
  region: yup.string().required(langFilter("ValidateRequired")),
  waterbase: yup.string().required(langFilter("ValidateRequired")),
  address: yup.string().required(langFilter("ValidateRequired")),
  quantity: yup
    .number()
    .typeError(langFilter("ValidateQuantityType"))
    .positive(langFilter("ValidateQuantityPositive"))
    .required(langFilter("ValidateRequired"))
    .default(null),
  date: yup
    .date()
    .typeError(langFilter("ValidateDateFormat"))
    .min(currentDate, langFilter("ValidateDateMin"))
    .nullable()
    .default(null)
});

export default {
  name: "water-order-form",
  components: { Input, Select, Datepicker },
  data: () => ({
    loading: true,
    values: {
      region: "",
      waterbase: "",
      quantity: null,
      address: "",
      date: null
    },
    errors: {
      region: "",
      waterbase: "",
      quantity: "",
      address: "",
      date: ""
    },
    lists: {
      regions: [],
      waterbases: []
    }
  }),
  async mounted() {
    this.fetchRegions();
    this.fetchWaterbases();

    // forse timeout to emulate loading time
    setTimeout(() => {
      this.loading = false;
    }, 1000);

    // clear form component sended status to update it after form sending
    if (this.$parent.sended) this.$parent.sended = false;
  },
  computed: {
    ...mapGetters(["regions", "waterbases", "currentRegion"])
  },
  methods: {
    ...mapActions(["fetchRegions", "fetchWaterbases", "createOrder"]),
    ...mapMutations(["setRegion", "clearMessage"]),
    request() {
      //this.loading = true;
      this.clearMessage();
      validationSchema
        .validate(this.values, { abortEarly: false })
        .then(() => {
          this.$parent.sended = true;
          this.createOrder(this.values);
        })
        .catch(err => {
          err.inner.forEach(error => {
            this.errors = { ...this.errors, [error.path]: error.message };
          });
        });
    },
    validate(field) {
      validationSchema
        .validateAt(field, this.values)
        .then(() => {
          this.errors[field] = "";
        })
        .catch(err => {
          this.errors[err.path] = err.message;
        });
    },
    onRegionSelect() {
      this.validate("region");

      // set region for get waterbases list for current region and update select
      this.setRegion(this.values.region);
    },
    onWaterbaseSelect() {
      this.validate("waterbase");
    }
  }
};
</script>
