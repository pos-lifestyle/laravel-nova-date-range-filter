<template>
    <input
        type="text"
        ref="dateRangePicker"
        :disabled="disabled"
        :class="{ '!cursor-not-allowed': disabled }"
        :placeholder="placeholder"
        :value="value"
    />
</template>

<script>
    import flatpickr from 'flatpickr';
    import 'flatpickr/dist/themes/airbnb.css';

    export default {
        props: {
            disabled: {
                type: Boolean,
                default: false,
            },
            placeholder: {
                type: String,
                default: () => {
                    return moment().format('YYYY-MM-DD');
                },
            },
            value: {
                required: false,
            },
            allowInput: {
                type: Boolean,
                default: false,
            },
            dateFormat: {
                type: String,
                default: 'Y-m-d',
            },
            defaultDate: {
                type: String,
                default: null,
            },
            enableTime: {
                type: Boolean,
                default: false,
            },
            enableSeconds: {
                type: Boolean,
                default: false,
            },
            locale: {
                type: Object | String,
                default: 'default',
            },
            maxDate: {
                type: String,
                default: null,
            },
            minDate: {
                type: String,
                default: null,
            },
            shorthandCurrentMonth: {
                type: Boolean,
                default: false,
            },
            showMonths: {
                type: Number,
                default: 1,
            },
            time24hr: {
                type: Boolean,
                default: true,
            },
            weekNumbers: {
                type: Boolean,
                default: false,
            },
            firstDayOfWeek: {
                type: Number,
                default: 0,
            },
        },

        data: function () {
            return {
                flatpickr: null,
            };
        },

        mounted: function () {
            this.$nextTick(() => {
                this.flatpickr = flatpickr(this.$refs.dateRangePicker, {
                    allowInput: this.allowInput,
                    dateFormat: this.dateFormat,
                    defaultDate: this.defaultDate,
                    enableTime: this.enableTime,
                    enableSeconds: this.enableSeconds,
                    locale: this.locale === 'default'
                        ? {
                            firstDayOfWeek: this.firstDayOfWeek,
                            time_24hr: this.time24hr,
                        }
                        : this.locale,
                    maxDate: this.maxDate,
                    minDate: this.minDate,
                    mode: 'range',
                    shorthandCurrentMonth: this.shorthandCurrentMonth,
                    showMonths: this.showMonths,
                    time_24hr: this.time24hr,
                    weekNumbers: this.weekNumbers,
                    onChange: this.onChange,
                });

                this.$emit('ready', true);
            });
        },

        beforeDestroy: function () {
            this.flatpickr.destroy();
        },

        methods: {
            onChange: function (selectedDates) {
                if (selectedDates.length === 1) {
                    return;
                }

                this.$emit('change', selectedDates);
            },

            getFlatpickrConfig: function () {
                return this.flatpickr.config;
            },

            clear: function () {
                this.flatpickr.clear();
            },
        },
    };
</script>

<style scoped>
    .\!cursor-not-allowed {
        cursor: not-allowed !important;
    }
</style>
