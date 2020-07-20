<template>
  <div class="input-field" :class="[!!error && 'invalid']">
    <i
      class="material-icons prefix"
      :class="[!!error && 'red-text']"
      v-if="!!icon"
      >{{ icon }}</i
    >
    <select
      :name="name"
      ref="select"
      :class="[!!error && 'invalid']"
      @change="$emit('change', $event.target.value)"
      v-if="!!options"
    >
      <option value="" disabled selected>{{ empty }}</option>
      <option
        v-for="option in options"
        :key="option.uuid"
        :value="option.uuid"
        :selected="current && option.uuid == current"
        >{{ option.name }}</option
      >
    </select>
    <label>{{ label }}</label>
    <small
      class="helper-text"
      :class="[!!error && 'red-text']"
      v-if="!!error"
      >{{ error }}</small
    >
  </div>
</template>

<script>
import M from "materialize-css";

export default {
  name: "field-select",
  model: {
    prop: "value",
    event: "change"
  },
  data: () => ({
    instance: null
  }),
  props: {
    name: { required: true },
    value: { required: true },
    options: { required: true },
    label: { required: true },
    icon: { required: false },
    empty: { required: true },
    error: { required: true },
    current: { required: false }
  },
  updated() {
    if (!this.instance) {
      this.init();
    } else {
      this.instance.destroy();
      this.init();
    }
  },
  methods: {
    init() {
      M.FormSelect.init(this.$refs.select, {});
      this.instance = M.FormSelect.getInstance(this.$refs.select);
    }
  },
  destroyed() {
    if (this.instance && this.instance.destroy) {
      this.instance.destroy();
    }
  }
};
</script>
