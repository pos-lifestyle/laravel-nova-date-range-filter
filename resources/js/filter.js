import DateRangeFilter from './components/DateRangeFilter'

Nova.booting((app, store) => {
    app.component('date-range-filter', DateRangeFilter)
})
