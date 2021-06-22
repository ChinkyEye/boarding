<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Notice List</h1>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" class="form-control" @keyup="searchNotice" v-model="search" placeholder="Search by name">
                  </div>
                  <div class="col-md-6">
                    <date-picker v-model="range" is-range v-on:input="searchDateRange"  :max-date='new Date()'>
                      <template v-slot="{ inputValue, inputEvents }">
                        <div class="d-flex justify-center items-center">
                          <input
                          :value="inputValue.start"
                          v-on="inputEvents.start"
                          class="form-control rounded-0"
                          name="start"
                          />
                          
                          <input
                          :value="inputValue.end"
                          v-on="inputEvents.end"
                          class="form-control rounded-0"
                          name="end"
                          />
                        </div>
                      </template>
                    </date-picker>
                  </div>
                </div>
              </div>
              <div class="card-body" v-if="allnotice != 0">
                <div>
                  <span>Search Result found <b>{{totallist}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="10px">SN</th>
                        <th>Name</th>
                        <th>Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(notice, index) in allnotice" :key="notice.id">
                        <td>{{index+1}}</td>
                        <td>{{notice.get_notice.title}}</td>
                        <td>
                          <router-link :to="`/notice/${notice.get_notice.slug}`"><i class="fas fa-pencil-alt mr-2" title="Read More"></i></router-link>
                          {{ notice.get_notice.description | strippedContent }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import DatePicker from 'v-calendar/lib/components/date-picker.umd';
  import moment from 'moment';
  export default {
    name: "NoticeList",
    components: {
      DatePicker
    },
    data(){
      return {
        search:'',
        range:{
          start: new Date(),
          end: new Date()
        },
        totallist:''

      }
    },
    mounted(){
      this.fetchNotice();
     
    },
    computed:{
      allnotice(){
        this.$Progress.start();
        var notice = this.$store.getters.getNotice;
        if(notice.length == 0) { return [ notice.length ]; }
        var avar = notice.noticelist;
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }
        this.totallist = parseInt(notice.totallist);
        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchNotice(){
        this.$store.dispatch('getAllNotice',[moment(this.range.start).format('YYYY-MM-DD'),moment(this.range.end).format('YYYY-MM-DD'),this.search])
      },
      searchDateRange(){
        this.fetchNotice();
        var date_start = moment(this.range.start).format('YYYY-MM-DD'),
            date_end = moment(this.range.end).format('YYYY-MM-DD');
        Toast.fire({
          icon: 'success',
          title: 'Search '+date_start+' || '+date_end+' Result.'
        })
      },
      searchNotice(){
        this.fetchNotice();
        Toast.fire({
          icon: 'success',
          title: 'Signed in successfully'
        })
      }
    },
    filters: {
      strippedContent: function(string) {
       return (string.substring(0,20)+"..").replace(/<\/?[^>]+>/ig, " "); 
     }
    }
  }
</script>

<style scoped>
</style>