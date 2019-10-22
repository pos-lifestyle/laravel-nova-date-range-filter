Nova.booting((Vue, router, store) => {
    Vue.component('date-range-filter', require('./components/DateRangeFilter'));
    Vue.component('date-range-picker', require('./components/DateRangePicker'));
});
