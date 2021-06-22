<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Exam List</h1>
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
                <!-- <div class="row">
                  <div class="col-md-6">
                    <select class="form-control rounded-0" id="subject" v-model="subject" name="subject" @change="searchHomework"> 
                      <option disabled value="">Select Subject</option>
                      <option v-for="option in allsubject" v-bind:value="option.id" :title="option.name">
                        {{ option.name }}
                      </option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" @keyup="searchBook" v-model="search" placeholder="Search by name">
                  </div>
                </div> -->
              </div><!-- /.card-header -->
              <div class="card-body" v-if="allexam != 0">
                <div>
                  <span>Search Result found <b>{{totallist}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="20">SN</th>
                        <th>Exam Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Result Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(exam, index) in allexam" :key="exam.id">
                        <td><a>{{index+1}}</a></td>
                        <td>{{exam.get_exam.name}}</td>
                        <td>{{exam.get_exam.start_date}}</td>
                        <td>{{exam.get_exam.end_date}}</td>
                        <td>{{exam.start_time}}</td>
                        <td>{{exam.end_time}}</td>
                        <td>{{exam.result_date}}</td>
                        <td class="text-center">
                          <router-link :to="`/exam/${exam.id}`"><i class="far fa-eye" title="View Marksheet"></i></router-link>
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
  export default {
    name: "BookList",
    data(){
      return {
        subject:'',
        search:'',
        totallist:'',
        terminal:''
      }
    },
    mounted(){
      this.fetchExam();
    },
    computed:{
      allexam(){
        this.$Progress.start();
        var exam = this.$store.getters.getStudent;
        var avar = exam.examlist;
        // console.log(exam);
        this.$Progress.finish();
        return avar;
      },
    },
    methods:{
      fetchExam(){
        // this.$store.dispatch('getAllStudent')
        this.$store.dispatch('getAllStudent')
        // this.$store.dispatch('getAllBook', [this.subject,this.search])
      }
    }
  }
</script>

<style scoped>
</style>