<template>
  <div>
    <div class="add-modal-content">
      <div class="add-header">
        <h2 class="add-h2">Add event</h2>
        <div class="add-close" @click="closeModal">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.639864 13.3599C0.789863 13.5099 0.979863 13.5799 1.16986 13.5799C1.35986 13.5799 1.55986 13.5099 1.69986 13.3599L6.99986 8.05986L12.2999 13.3599C12.4499 13.5099 12.6399 13.5799 12.8299 13.5799C13.0199 13.5799 13.2099 13.5099 13.3599 13.3599C13.6499 13.0699 13.6499 12.5899 13.3599 12.2999L8.05986 6.99986L13.3599 1.69986C13.6499 1.40986 13.6499 0.929864 13.3599 0.639864C13.0699 0.349863 12.5899 0.349863 12.2999 0.639864L6.99986 5.93986L1.69986 0.639864C1.40986 0.349863 0.929864 0.349863 0.639864 0.639864C0.349863 0.929864 0.349863 1.40986 0.639864 1.69986L5.93986 6.99986L0.639864 12.2999C0.349863 12.5899 0.349863 13.0699 0.639864 13.3599Z" fill="#FF4E6B"/>
          </svg>
        </div>
      </div>
      <input class="name-input" v-model="eventName" type="text" :maxlength="maxNameLength" :minlength="minNameLength"  placeholder="Event name...">
      <textarea class="description-input" v-model="eventDescription"  rows="4" :maxlength="maxDescriptionLength"  placeholder="Event description..."></textarea>
      <input class="location-input" v-model="eventLocation" type="text" :maxlength="maxLocationLength" :minlength="minLocationLength" placeholder="Location...">
      <div class="date-input">
        <h2 class="text-date">{{ formatDateForTextDate(date) }}</h2>
        <select class="select-input" v-model="selectedTime">
          <option value="" selected disabled>Select time</option>
          <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
        </select>
      </div>
      <select class="select-input" v-model="selectedCategory">
        <option value="" selected disabled >Select event type</option>
        <option value="expert">Meeting with an expert</option>
        <option value="qa">Question-answer</option>
        <option value="conference">Conference</option>
        <option value="webinar">Webinar</option>
      </select>

      <div class="add-footer">
        <button class="cancel-add-button" @click="closeModal">Cancel</button>
        <button class="event-add-button" @click="handleAddClick">Add</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  props: {
    date: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      maxNameLength: 128,
      minNameLength: 3,
      maxLocationLength: 128,
      minLocationLength: 3,
      maxDescriptionLength: 512,

      selectedTime: '', // Обраний час
      hours: [], // Масив для годин
      eventName: '',
      eventDescription: '',
      eventLocation: '',
      selectedCategory: '',
    };
  },
  mounted() {
    this.generateHours();
  },
  methods: {
    formatDateForTextDate(date) {
      console.log("from AddEventComponent.formatDateForTextDate" + this.date);
      const dateNew = new Date(date);
      const day = dateNew.getDate();
      const month = this.getMonthName(dateNew.getMonth());

      return `${day} ${month} at `;
    },
    getMonthName(monthIndex) {
      const months = [
        'january', 'february', 'march', 'april',
        'may', 'june', 'july', 'august',
        'september', 'october', 'november', 'december'
      ];
      return months[monthIndex];
    },
    generateHours() {
      for (let hour = 0; hour < 24; hour++) {
        for (let minute = 0; minute < 60; minute += 30) {
          const formattedHour = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
          this.hours.push(formattedHour);
        }
      }
    },
    validateEvent() {
      if (!this.eventName || !this.eventDescription || !this.eventLocation || !this.selectedCategory || !this.date || !this.selectedTime) {
        console.error('All fields must be filled in.');
        return false;
      }
      return true;
    },
    handleAddClick() {
      console.log("from AddEventComponent.handleAddClick" + this.date);
      if (!this.validateEvent()) {
        this.closeModal();
        return;
      }

      const newEvent = {
        name: this.eventName,
        description: this.eventDescription,
        location: this.eventLocation,
        type: this.selectedCategory,
        datetime: this.date +'T'+ this.selectedTime+':00+00:00',
      };
      console.log('New Event:', newEvent);

      axios.post('http://127.0.0.1:8000/api/v1/event/create', newEvent)
          .then(response => {
            //console.log('Server response:', response.data);
            location.reload();
            //this.$emit('event-added', newEvent);
          })
          .catch(error => {
            console.error('Error adding event:', error);
          });
    },
    closeModal() {
      this.$emit('close-modal');
    },
  },
};
</script>
