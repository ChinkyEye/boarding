<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Homework List</h1>
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
                    <select class="form-control rounded-0" id="search" v-model="search" name="search" @change="searchHomework"> 
                      <option disabled value="">Select Subject</option>
                      <option v-for="option in allsubject" v-bind:value="option.id" :title="option.name">
                        {{ option.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <date-picker v-model="date" mode="date" :max-date='new Date()' v-on:input="searchDate">
                      <template v-slot="{ inputValue, inputEvents }">
                        <input
                          class="form-control rounded-0"
                          :value="inputValue"
                          v-on="inputEvents"
                          name="date"
                        />
                      </template>
                    </date-picker>
                  </div>
                </div>
              </div>
              <div class="card-body" v-if="allhomework != 0">
                <div>
                  <span>Search Result found <b>{{totallist}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="20">ID</th>
                        <th width="150">Date</th>
                        <th width="150">Eng Date</th>
                        <th width="200">Subject Name</th>
                        <th width="200">Shift Name</th>
                        <th>Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(homework, index) in allhomework" :key="homework.id">
                        <td>{{index+1}}</td>
                        <td>{{homework.date}}</td>
                        <td>{{homework.date_eng}}</td>
                        <td>{{homework.get_subject.name}} <span class="badge badge-success">{{homework.get_teacher.name}}</span></td>
                        <td>{{homework.get_shift.name}}</td>
                        <td>
                         <router-link :to="`/homework/${homework.id}`"><i class="fas fa-pencil-alt mr-2" title="Read More"></i></router-link>
                          {{homework.description |strippedContent}}
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
    name: "HomeworkList",
    components: {
      DatePicker
    },
    data(){
      return {
        search:'',
        date: new Date,
        totallist:'',
        subjects:''
      }
    },
    mounted(){
      this.fetchHomework();
     
    },
    computed:{
      allhomework(){
        this.$Progress.start();
        var homework = this.$store.getters.getHomework;
        if(homework.length == 0) { return [ homework.length ]; }

        var avar = homework.homeworklist;
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }

        this.subjects = homework.subjectlist;
        // console.log(this.subjects);
        this.totallist = parseInt(homework.totallist);
        this.$Progress.finish();
        return avar;
      },
      allsubject(){
        this.$Progress.start();
        var homework = this.$store.getters.getHomework;
        if(homework.length == 0) { return [ homework.length ]; }

        var avar = homework.subjectlist;
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }

        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchHomework(){
        this.$store.dispatch('getAllHomework',[moment(this.date).format('YYYY-MM-DD'),this.search])
      },
      searchDate(){
        this.fetchHomework();
        // console.log(this.fetchHomework());
        var date = moment(this.date).format('YYYY-MM-DD');
        Toast.fire({
          icon: 'success',
          title: 'Search '+date+' Result.'
        })
      },
      searchHomework(){
        this.fetchHomework();
        var search = this.search;
        Toast.fire({
          icon: 'success',
          title: 'Signed in successfully'
        })
      },
      
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