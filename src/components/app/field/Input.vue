<template>
  <div class="input-field">
    <i
      class="material-icons prefix"
      :class="[!!error && 'red-text']"
      v-if="!!icon"
      >{{ icon }}</i
    >
    <input
      :name="name"
      :type="type"
      :id="name"
      :value="value"
      :class="[!!error && 'invalid']"
      @input="$emit('input', $event.target.value)"
      @blur="$emit('validate')"
      @keypress="$emit('validate')"
    />
    <label :for="[name]">{{ label }}</label>
    <small
      class="helper-text"
      :class="[!!error && 'red-text']"
      v-if="!!error"
      >{{ error }}</small
    >
  </div>
</template>

<script>
export default {
  name: "field-input",
  props: {
    type: { required: true },
    label: { required: true },
    name: { required: true },
    icon: { required: false },
    value: { required: true },
    error: { required: true }
  }
};
</script>
