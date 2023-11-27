<template>
  <div id="app" class="app">
    <Header />
    <Main />
    <Footer />

  </div>
</template>

<script>
import Header from './components/HeaderComponent.vue';
import Main from './components/MainComponent.vue';
import Footer from './components/FooterComponent.vue';
import CalendarComponent from './components/CalendarComponent.vue';
import PopupEventComponent from './components/PopupEventComponent.vue';
import AddEventComponent from "./components/AddEventComponent.vue";
import EditEventComponent from "./components/EditEventComponent.vue";

export default {
  components: {
    Header,
    Main,
    Footer,
    CalendarComponent,
    PopupEventComponent,
    AddEventComponent,
    EditEventComponent,
  },
};
</script>

