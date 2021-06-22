<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Event List</h1>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <FullCalendar :options="calendarOptions" />
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
  import FullCalendar from '@fullcalendar/vue'
  import dayGridPlugin from '@fullcalendar/daygrid'
  import interactionPlugin from '@fullcalendar/interaction'

  export default {
    components: {
      FullCalendar,
    },
    name: "NoticeList",
    data() {
      return {
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        events: [
          { title: 'event 1', date: '2020-12-01' },
          { title: 'event 2', date: '2020-12-02' },
          { title:'holiday', start:'2020-12-05', end: '2020-12-15'}
        ]
      }
    }
  },
  mounted(){
    // this.fetchHome();
  },
  computed:{
    allnotice(){
      // return this.$store.getters.getNotice;
    }
  },
  methods:{
    fetchHome(){
      // this.$store.dispatch('getAllNotice')
    },
    searchNotice(){
      this.fetchHome();
      Toast.fire({
        icon: 'success',
        title: 'Signed in successfully'
      })
    }
  }
}
</script>



<style scoped>
  
</style>
