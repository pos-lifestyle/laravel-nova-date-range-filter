<template>
	<FilterContainer>
		<span>{{ filter.name }}</span>

		<template #filter>
			<div class="relative">
				<input type="text" autofocus="autofocus" style="display:none" />
				<date-range-picker
					:dusk="`${filter.name}`"
					name="date-range-filter"
					ref="dateRangePickerComponent"
					class="w-full form-control form-input form-input-bordered"
					:class="{'pr-10' : isValidCurrentValue}"
					style="cursor: pointer !important;"
					autocomplete="off"
					:disabled="disabled"
					:placeholder="placeholder"
					:allow-input="allowInput"
					:date-format="dateFormat"
					:enable-time="enableTime"
					:enable-seconds="enableSeconds"
					:locale="locale"
					:value="value"
					:max-date="maxDate"
					:min-date="minDate"
					:shorthand-current-month="shorthandCurrentMonth"
					:show-months="showMonths"
					:time24hr="time24hr"
					:week-numbers="weekNumbers"
					:first-day-of-week="firstDayOfWeek"
					@input.prevent=""
					@change="debouncedHandleChange"
					@ready="setDisplayValue"
				/>

				<div v-show="isValidCurrentValue"
					 class="absolute inset-y-0 right-0 top-0 p-2 z-20 flex items-center">
					<button @click="resetFilter" class="text-gray-400">
						<icon type="x-circle" view-box="0 0 22 22" width="22" height="22" />
					</button>
				</div>
			</div>
		</template>
	</FilterContainer>
</template>

<script>
import debounce        from 'lodash/debounce'
import DateRangePicker from './DateRangePicker'
import moment          from 'moment/moment'
import flatpickr       from 'flatpickr'

export default {
	components: { DateRangePicker },

	emits: ['change'],

	props: {
		resourceName: {
			type    : String,
			required: true,
		},
		filterKey   : {
			type    : String,
			required: true,
		},
		lens        : String,
	},

	data: () => ( {
		displayValue         : '',
		debouncedHandleChange: null,
	} ),

	created () {
		this.debouncedHandleChange = debounce((value) => this.handleChange(value), 100)
	},

	mounted () {
		Nova.$on('filter-reset', this.resetFilter)
	},

	beforeUnmount () {
		Nova.$off('filter-reset', this.resetFilter)
	},

	methods: {
		handleChange (value) {

			/*
			 * Remove repetition and provide empty value when clear button is pressed.
			 * This tells Nova that the filter is no longer enabled, changing the filter
			 * icon colour from blue to regular colour. Without this fix, you can press
			 * the clear button, but the filter icon still looks as if a filter is applied.
			 */
			value = Array.isArray(value) && value.length === 2
				? value.map((value) => moment(value).format('YYYY-MM-DD'))
				: []

			this.$store.commit(`${ this.resourceName }/updateFilterState`, {
				filterClass: this.filterKey,
				value      : value,
			})

			this.setDisplayValue()

			this.$emit('change')
		},

		setDisplayValue () {
			const flatpickrConfig = this.$refs.dateRangePickerComponent.getFlatpickrConfig()
			const rangeSeparator = flatpickrConfig.locale.rangeSeparator || 'to'

			if (!this.isValidCurrentValue) {
				return
			}

			this.displayValue = this.filter.currentValue.map((value) => flatpickr.formatDate(flatpickr.parseDate(value), this.dateFormat)).join(` ${ rangeSeparator } `)
		},

		resetFilter () {
			this.$refs.dateRangePickerComponent.clear()
			this.displayValue = ''
		},
	},

	computed: {
		filter () {
			return this.$store.getters[ `${ this.resourceName }/getFilter` ](
				this.filterKey,
			)
		},

		isValidCurrentValue () {
			return Array.isArray(this.filter.currentValue) && this.filter.currentValue.length === 2
		},

		disabled: function () {
			return this.filter.disabled !== undefined ? this.filter.disabled : false
		},

		placeholder: function () {
			return this.filter.placeholder || this.__('Choose date range')
		},

		value: function () {
			return this.displayValue
		},

		allowInput: function () {
			return this.filter.allowInput !== undefined ? this.filter.allowInput : false
		},

		dateFormat: function () {
			return this.filter.dateFormat || 'Y-m-d'
		},

		enableTime: function () {
			return this.filter.enableTime !== undefined ? this.filter.enableTime : false
		},

		enableSeconds: function () {
			return this.filter.enableSeconds !== undefined ? this.filter.enableSeconds : false
		},

		locale: function () {
			// There is no english language file.
			if (!this.filter.locale || this.filter.locale === 'en') {
				return 'default'
			}

			return require(`flatpickr/dist/l10n/${ this.filter.locale }.js`).default[ this.filter.locale ]
		},

		maxDate: function () {
			return this.filter.maxDate || null
		},

		minDate: function () {
			return this.filter.minDate || null
		},

		shorthandCurrentMonth: function () {
			return this.filter.shorthandCurrentMonth !== undefined ? this.filter.shorthandCurrentMonth : false
		},

		showMonths: function () {
			return this.filter.showMonths || 1
		},

		time24hr: function () {
			return this.filter.time24hr !== undefined ? this.filter.time24hr : false
		},

		weekNumbers: function () {
			return this.filter.weekNumbers !== undefined ? this.filter.weekNumbers : false
		},

		firstDayOfWeek: function () {
			return this.filter.firstDayOfWeek || 0
		},
	},
}
</script>
