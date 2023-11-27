<template>
  <main>
    <div class="main">
      <div class="main-header">
        <h1 >Calendar</h1>
        <div class="main-filters">
          <label class="filter filter-expert" :class="{ 'active': filters.expert }">
            <input type="checkbox" v-model="filters.expert" value="expert" @change="handleChange"> Meeting with an expert
          </label>
          <label class="filter filter-qa" :class="{ 'active': filters.qa }">
            <input type="checkbox" v-model="filters.qa" value="qa" @change="handleChange"> Question-answer
          </label>
          <label class="filter filter-conference" :class="{ 'active': filters.conference }">
            <input type="checkbox" v-model="filters.conference" value="conference" @change="handleChange"> Conference
          </label>
          <label class="filter filter-webinar" :class="{ 'active': filters.webinar }">
            <input type="checkbox" v-model="filters.webinar" value="webinar" @change="handleChange"> Webinar
          </label>
        </div>
      </div>
      <div class="main-content">


        <Calendar :months="monthsData" :events="calendarEvents" @update-events="updateMonthsData" />

      </div>
    </div>
  </main>
</template>

<script>
import Calendar from './CalendarComponent.vue';
import axios from 'axios';

export default {
  components: {
    Calendar,
  },
  data() {
    return {
      filters: {
        expert: false,
        qa: false,
        conference: false,
        webinar: false,
      },
      monthsData: [],
      calendarEvents: [],
    };
  },
  mounted() {
    this.fetchDataFromApi();
  },
  watch: {
    filters: {
      deep: true,
      handler: 'updateFilters',
    },
  },
  computed: {
    selectedFilters() {
      return Object.keys(this.filters).filter((filter) => this.filters[filter]);
    },
    anyFilterSelected() {
      return Object.keys(this.filters);
    },
  },
  methods: {
    updateMonthsData(data) {
      this.monthsData = data;
    },
    updateFilters() {
      this.fetchDataFromApi();
    },
    fetchDataFromApi() {
      const apiQuery =
          this.selectedFilters.length > 0
              ? this.selectedFilters
              : this.anyFilterSelected;

      console.log(apiQuery);
      const dateRangeApiUrl = 'http://127.0.0.1:8000/api/v1/date-range';
      axios.get(dateRangeApiUrl, { headers: { 'Cache-Control': 'no-cache' }})
          .then((dateResponse) => {
        const dateRange = dateResponse.data;
        axios
            .post('http://127.0.0.1:8000/api/v1/events', {
              'filters': apiQuery,
              'date_range': dateRange,
            })
            .then((eventResponse) => {
              const eventsData = eventResponse.data;
              console.log(eventsData);
              this.calendarEvents = eventsData;
            })
            .catch((error) => {
              console.error('Помилка при отриманні даних про події:', error);
            });
      }).catch((dateError) => {
        console.error('Помилка при отриманні даних про дати:', dateError);
      });
    },
  },
};
</script>


