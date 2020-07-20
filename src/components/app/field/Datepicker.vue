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
      ref="datepicker"
      type="text"
      :id="name"
      :value="value"
      :class="[!!error && 'invalid']"
    />
    <label :for="[name]">{{ label }}</label>
    <small
      class="helper-text invalid"
      :class="[!!error && 'red-text']"
      v-if="!!error"
      >{{ error }}</small
    >
  </div>
</template>

<script>
import M from "materialize-css";

export default {
  name: "field-datepicker",
  data: () => ({
    instance: null
  }),
  props: {
    label: { required: true },
    name: { required: true },
    icon: { required: false },
    value: { required: true },
    error: { required: true }
  },
  mounted() {
    this.instance = M.Datepicker.init(this.$refs.datepicker, {
      //setDefaultDate: true,
      minDate: new Date(),
      defaultDate: new Date(),
      // i18n: {
      //   cancel: "Закрыть",
      //   done: "Выбрать",
      //   months: [
      //     "Январь",
      //     "Февраль",
      //     "Март",
      //     "Апрель",
      //     "Май",
      //     "Июнь",
      //     "Июль",
      //     "Август",
      //     "Сентябрь",
      //     "Октябрь",
      //     "Ноябрь",
      //     "Декабрь"
      //   ],
      //   monthsShort: [
      //     "Янв",
      //     "Фев",
      //     "Мар",
      //     "Апр",
      //     "Май",
      //     "Июн",
      //     "Июл",
      //     "Авг",
      //     "Сен",
      //     "Окт",
      //     "Ноя",
      //     "Дек"
      //   ],
      //   weerDays: [
      //     "Воскресенье",
      //     "Понедельник",
      //     "Вторник",
      //     "Среда",
      //     "Четверг",
      //     "Пятница",
      //     "Суббота"
      //   ],
      //   weekdaysShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"]
      // },
      onClose: () => {
        this.$parent.values.date = this.$refs.datepicker.value || null;
        this.$parent.validate("date");
      }
    });
  },
  destroyed() {
    if (this.instance && this.instance.destroy) {
      this.instance.destroy();
    }
  }
};
</script>
