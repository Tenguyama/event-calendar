<template>
  <div class="calendar">
    <div v-for="(month, index) in months" :key="index" class="month">
      <h2 class="month-name">{{ month.name }}</h2>
      <div class="day-names">
        <div v-for="dayName in weekdays" :key="dayName" class="day-name">{{ dayName }}</div>
      </div>
      <div class="weeks" v-for="(week, weekIndex) in month.weeks" :key="weekIndex">
        <div class="day" v-for="(day, dayIndex) in week" :key="dayIndex" :class="{ 'active': day.active, 'disabled': day.disabled }" @click="handleDayClick(day)">
<!--             @click="handleDayClick($event, day)">-->
          {{ day.day }}
          <div v-if="day.active" class="circles">
            <div v-for="(filter, index) in filters" :key="index" :class="[getCircleClass(filter, day.activeCircles)]" ></div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showEventPopup" class="popup-overlay">
      <PopupEventComponent
          :events="eventsForClickedDate"
          :date="dateForClickedDate"
          @close-modal="closeEventPopup"/>
    </div>
  </div>
</template>

<script>
import PopupEventComponent from './PopupEventComponent.vue';
import axios from 'axios';

export default {
  props: {
    months: Array,
    events: {
      type: Array,
      default: () => [],
    },
  },
  components: {
    PopupEventComponent,
  },
  watch: {
    events: {
      immediate: true,
      handler(newEvents) {
        this.localEvents = newEvents;
        this.updateEvents(); // Викликаємо при зміні localEvents
      },
    },
  },
  data() {
    return {
      loading: false,
      localEvents: [],
      eventsForClickedDate: [], // дані про події для передачі в PopupEventComponent, з якого один з них в EditEventComponent
      dateForClickedDate: String, // дані про події для передачі в PopupEventComponent, з якого в AddEventComponent
      showEventPopup: false,
      weekdays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
      filters: ["expert", "qa", "conference", "webinar"],
    };
  },
  mounted() {
    this.fetchDataFromApi();
  },
  methods: {
    async fetchDataFromApi() {
      this.loading = true;
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/v1/calendar');
        this.$emit('update-events', response.data);
      } catch (error) {
        console.error('Помилка при отриманні даних календаря з API:', error);
      } finally {
        this.loading = false;
      }
    },
    getCircleClass(filter,activeCircles) {
      return {
        'circle': true,
        [`${filter}-circle`]: true,
        [`${filter}-circle-active`]: activeCircles && activeCircles[filter]
      };
    },
    //handleDayClick(event, day) {
     handleDayClick(day) {
      const clickedDate = day.date;
       console.log("from CalendarComponent.handleDayClick" + this.date);

      //щоб не було можливим додати події до днів що виходять за рамки календарю, але відображаються
       // для "повних тижнів", типу останні дні останнього місяця який не закінчується суботою
       // і аналогічно дні першого місяця який починається не в неділю
       // коротко - дати що виходять за API/date-range
      if(day.active) {
        const eventsForDate = this.localEvents.filter((eventForDate) => eventForDate.datetime.split('T')[0] === clickedDate);
        this.eventsForClickedDate = eventsForDate;
        this.dateForClickedDate = clickedDate;
        // Проблема в відображенні, типу він залізає за край екрана і або калхозити або забути
        // const { clientX, clientY } = event;
        // const rect = event.currentTarget.getBoundingClientRect();
        // const top = rect.top + window.scrollY;
        // const left = rect.left + window.scrollX;
        // const relativeTop = clientY - rect.top;
        // const relativeLeft = clientX - rect.left;
        // this.popupPosition = { top: top + relativeTop - 85, left: left + relativeLeft - 15 };
        this.showEventPopup = true;
      }
    },
    closeEventPopup() {
      this.showEventPopup = false;
    },
    updateEvents() {
      this.resetActiveCircles();
      this.localEvents.forEach((event) => {
        const datetime = event.datetime;
        const eventType = event.type;
        const day = this.findDayByDate(datetime);
        if (day) {
          day.activeCircles = day.activeCircles || {};
          day.activeCircles[eventType] = true;
        }
      });
    },
    resetActiveCircles() {
      this.months.forEach((month) => {
        if (month.weeks && Object.keys(month.weeks).length > 0) {
          const flatWeeks = Object.values(month.weeks).flat();
          flatWeeks.forEach((day) => {
            if (typeof day === 'object') {
              day.activeCircles = {
                'expert-circle-active': false,
                'qa-circle-active': false,
                'conference-circle-active': false,
                'webinar-circle-active': false,
              };
            }
          });
        }
      });
    },
    findDayByDate(date) {
      const formattedDate = new Date(date).toISOString().split('T')[0];
      for (const month of this.months) {
        if (month && month.weeks && Object.keys(month.weeks).length > 0) {
          const flatWeeks = Object.values(month.weeks).flat();
          const foundDay = flatWeeks.find(day => day.date === formattedDate);
          if (foundDay) {
            return foundDay;
          }
        }
      }
      return null;
    },
  },
};
</script>

