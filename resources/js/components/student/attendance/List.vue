<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Attendance List</h1>
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
                  <div class="col-md-6">
                    <select class="form-control rounded-0" id="status" v-model="status" name="status" @change="searchStatus"> 
                      <option value="">Select Your Status</option>
                      <option value="1"> Present</option>
                      <option value="0"> Absent</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="card-body" v-if="allattendance != 0">
                <div>
                  <span v-if="status == ''">
                    Search Result out of <b>{{total}}</b> : 
                    <b>Present : <span class="text-success">{{totalpresentlist}}</span></b> | 
                    <b>Absent : <span class="text-danger">{{totalabsentlist}} </span></b> 
                  </span>
                  <span v-else>Search Result found <b>{{total}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="10px">SN</th>
                        <th width="150px">Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(attendance, index) in allattendance" :key="attendance.id">
                        <td>{{index+1}}</td>
                        <td>{{attendance.date}}</td>
                       <td>{{getStatusName(attendance.status)}} <span class="badge badge-success">{{getStatusName(attendance.restatus)}}</span></td>
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
    name: "AttendanceList",
    components: {
      DatePicker
    },
    data(){
      return {
        date:'',
        status:'',
        total:'',
        totalpresentlist:'',
        totalabsentlist:'',
        totalstatuslist:'',
        range:{
          start: new Date(),
          end: new Date()
        },
        a: true,
        selectedValue : 1,
      }
    },
    mounted(){
      this.fetchAttendance();
    },
    computed:{
      allattendance(){
        this.$Progress.start();
        var attendance = this.$store.getters.getAttendance;
        if (attendance.length == 0) { return [attendance.length] }

        var avar = attendance.attendancelists;
        if (avar.length == 0) { this.$Progress.finish(); return [avar.length] }
        
        this.total = parseInt(attendance.totallist);
        this.totalpresentlist = parseInt(attendance.totalpresentlist);
        this.totalabsentlist = parseInt(attendance.totalabsentlist);
        this.totalstatuslist = parseInt(attendance.totalstatuslist);
        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchAttendance(){
        this.$store.dispatch('getAllAttendance', [moment(this.range.start).format('YYYY-MM-DD'),moment(this.range.end).format('YYYY-MM-DD'),this.status])
      },
      searchDateRange(){
        this.fetchAttendance();
        var date_start = moment(this.range.start).format('YYYY-MM-DD'),
            date_end = moment(this.range.end).format('YYYY-MM-DD');
        Toast.fire({
          icon: 'success',
          title: 'Search '+date_start+' || '+date_end+' Result.'
        })
      },
      searchStatus(){
        this.fetchAttendance();
        var data_status = moment(this.status);
        if (data_status) { data_status = 'Present'} else { data_status = 'Absent'}
        Toast.fire({
          icon: 'success',
          title: 'Search '+data_status+' Result.'
        })
      },
      getStatusName: function(status, restatus) {
         if (status === 1) {
           return 'Present';
         } else if (status === 0) {
           return 'Absent';
         } 
         if (restatus == 1) { return 'Present'} else if (restatus == 0) { return 'Absent' }
       }
    }
  }
</script>

<style scoped>
</style>