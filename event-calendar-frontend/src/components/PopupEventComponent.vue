<template>
  <div>
    <div class="popup-modal-overlay"></div>
    <div class="popup-modal-content" >
      <div class="popup-close" @click="closeModal">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0.639864 13.3599C0.789863 13.5099 0.979863 13.5799 1.16986 13.5799C1.35986 13.5799 1.55986 13.5099 1.69986 13.3599L6.99986 8.05986L12.2999 13.3599C12.4499 13.5099 12.6399 13.5799 12.8299 13.5799C13.0199 13.5799 13.2099 13.5099 13.3599 13.3599C13.6499 13.0699 13.6499 12.5899 13.3599 12.2999L8.05986 6.99986L13.3599 1.69986C13.6499 1.40986 13.6499 0.929864 13.3599 0.639864C13.0699 0.349863 12.5899 0.349863 12.2999 0.639864L6.99986 5.93986L1.69986 0.639864C1.40986 0.349863 0.929864 0.349863 0.639864 0.639864C0.349863 0.929864 0.349863 1.40986 0.639864 1.69986L5.93986 6.99986L0.639864 12.2999C0.349863 12.5899 0.349863 13.0699 0.639864 13.3599Z" fill="#FF4E6B"/>
        </svg>
      </div>
      <h2 class="popup-h2">Events</h2>
      <div v-if="events.length === 0">
        <p class="popup-event-content">No events to show</p>
      </div>
      <div class="popup-event" v-for="event in events" :key="event.id">
        <div class="popup-header">
          <h3 class="popup-h3">{{ event.name }}</h3>
          <div class="popup-edit-icon" @click="handleEditClick(event)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M5.53999 21.0469C4.92999 21.0469 4.35999 20.8369 3.94999 20.4469C3.42999 19.9569 3.17999 19.2169 3.26999 18.4169L3.63999 15.1769C3.70999 14.5669 4.07999 13.7569 4.50999 13.3169L11.3421 6.08541C11.3534 6.07256 11.3652 6.06008 11.3774 6.04799L12.72 4.62692C14.77 2.45692 16.91 2.39692 19.08 4.44692C21.25 6.49692 21.31 8.63692 19.26 10.8069L11.05 19.4969C10.63 19.9469 9.84999 20.3669 9.23999 20.4669L6.01999 21.0169C5.95895 21.0205 5.9005 21.0254 5.84324 21.0302C5.74099 21.0387 5.64254 21.0469 5.53999 21.0469ZM5.59999 14.3369L11.5184 8.0653C12.258 10.0344 13.8657 11.5562 15.8709 12.1898L9.94999 18.4569C9.74999 18.6669 9.26999 18.9269 8.97999 18.9769L5.75999 19.5269C5.42999 19.5769 5.15999 19.5169 4.97999 19.3469C4.79999 19.1769 4.71999 18.9069 4.75999 18.5769L5.12999 15.3369C5.16999 15.0469 5.39999 14.5469 5.59999 14.3369ZM18.16 9.76692L17.055 10.9366C14.9019 10.5714 13.1855 8.93318 12.7129 6.79952L13.81 5.63692C14.49 4.91692 15.16 4.43692 15.93 4.43692C16.55 4.43692 17.24 4.75692 18.04 5.52692C19.85 7.22692 19.4 8.44692 18.16 9.76692Z" fill="#797979"/>
            </svg>
          </div>
        </div>
        <p class="popup-event-content">{{ event.description }}</p>
        <p class="popup-event-location">{{event.location}}</p>
        <div class="popup-event-footer">
          <p :class="{ 'popup-event-datetime': true, ['popup-event-datetime-' + event.type]: true }">
            {{ formatDateTime(event.datetime) }}
          </p>
          <p :class="{ 'popup-event-type': true, ['popup-event-type-' + event.type]: true }">{{ event.type }}</p>
        </div>
      </div>
      <div class="popup-footer">
        <button class="popup-add-button" @click="handleAddClick()">Add event</button>
      </div>
    </div>

    <div v-if="showEventAdd" class="add-modal-overlay">
      <AddEventComponent
          :date="dateAdd"
          @close-modal="closeEventAdd"/>
    </div>

    <div v-if="showEventEdit" class="edit-modal-overlay">
      <EditEventComponent
          :event="editedEvent"
          @close-modal="closeEventEdit"/>
    </div>

  </div>
</template>

<script>
import AddEventComponent from "@/components/AddEventComponent.vue";
import EditEventComponent from "@/components/EditEventComponent.vue";


export default {
  components: {EditEventComponent, AddEventComponent},
  props: {
    events: Array,
    date: String,
  },
  data(){
    return{
      editedEvent: Object,
      dateAdd: String,
      showEventAdd: false,
      showEventEdit: false,
    };

  },
  methods: {
    closeModal() {
      this.$emit('close-modal');
    },
    formatDateTime(dateTime) {
      console.log("from PopupEventComponent.formatDateTime" + this.date);

      const date = new Date(dateTime);
      const day = date.getUTCDate();
      const month = this.getMonthName(date.getUTCMonth());
      const hours = date.getUTCHours();
      const minutes = date.getUTCMinutes();

      const formattedDate = `${day} ${month}, ${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;

      return formattedDate;
    },

    getMonthName(monthIndex) {
      const months = [
        'january', 'february', 'march', 'april',
        'may', 'june', 'july', 'august',
        'september', 'october', 'november', 'december'
      ];
      return months[monthIndex];
    },

    handleAddClick() {
      console.log('Кнопка була натиснута!');
      console.log("from PopupEventComponent.handleAddClick" + this.date);
        this.dateAdd = this.date;
        this.showEventAdd = true;
    },
    closeEventAdd() {
      this.showEventAdd = false;
    },
    handleEditClick(event) {
       if (event) {
         this.editedEvent = event
         console.log(event);
         this.showEventEdit = true;
       }
    },
    closeEventEdit() {
      this.showEventEdit = false;
    },
  },
};
</script>


