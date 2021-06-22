<template>
  <div>
    <div class="content-header">
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-12">
                    <select class="form-control rounded-0" id="terminal" v-model="terminal" name="terminal" @change="searchTerminal"> 
                      <option disabled value="">Select Your Terminal</option>
                      <option v-for="option in search_terminal_list" v-bind:value="option.exam_id" :title="option.get_exam.name">
                        {{ option.get_exam.name }}
                      </option>
                    </select>
                  </div>
                </div>
              </div>
              
              <!-- <div class="card-body" v-else>
                <h4 class="text-center">Result.</h4>
              </div> -->

              <div class="card-body" v-if="allstudent == 0">
                <h4 class="text-center">Result has not Published. Contact School office.</h4>
              </div>
             <!--  <div class="card-body" v-else>
                <h4 class="text-center">Result.</h4>
              </div> -->
              <div class="card-body" v-else-if="allobservation.is_published == 0" >
                <h4 class="text-center">Mark has not Published Contact School office.</h4>
              </div>
              
              <div class="card-body" v-else>
                <div class="text-center">
                  <h3 class="text-capitalize">{{schoolinfo.school_name}} School</h3>
                  <h4>{{schoolinfo.address}}</h4>
                  <div>Phone : +977-{{schoolinfo.phone_no}}, Email : {{schoolinfo.email}}, Website :{{schoolinfo.url}}</div>
                </div>
                <div class="text-center my-4">
                  <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">MARK-SHEET</span>
                  <div class="text-capitalize mt-3">
                    <u class="h5 font-weight-bold">{{terminal_name.name}}</u>
                    <span class="position-absolute mr-4" style="right: 0;"><b>Academic Year:</b> {{sbatch.name}}</span>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-borderless table-hover table-sm">
                    <tr>
                      <td width="150px">
                        Student Name
                      </td>
                      <td> : {{userstudent.name}} {{userstudent.middle_name}} {{userstudent.last_name}}</td>
                      <!-- <td width="150px"><b>Academic Year</b> </td>
                      <td width="150px">: {{sbatch}}</td> -->
                      <td width="200px"></td>
                      <td width="150px"></td>
                    </tr>
                    <tr>
                      <td width="150px">
                        Class
                      </td>
                      <td>
                        : {{sclass.name}}
                      </td>
                      <td width="200px">
                        Roll No / Section/Shift
                      </td>
                      <td width="150px">
                        : {{student.roll_no}} ( {{sshift.name}} / {{ssection.name}} )
                      </td>
                    </tr>
                  </table>
                  <table class="table table-bordered table-hover table-sm">
                    <thead>
                      <tr class="text-center">
                      <th rowspan="2" width="10px" style="vertical-align: middle;">SN</th>
                      <th rowspan="2" style="vertical-align: middle;">Subjects</th>
                      <th colspan="2" width="10px" style="vertical-align: middle;">Obtained Grade</th>
                      <th rowspan="2" width="100px" style="vertical-align: middle;">Final Grade</th>
                      <th rowspan="2" width="100px" style="vertical-align: middle;">Grade Point</th>
                      <th rowspan="2"  width="200px" style="vertical-align: middle;">Remarks</th>
                    </tr>
                    <tr class="text-center">
                      <th rowspan="1" width="100px">Tr</th>
                      <th rowspan="1" width="100px">Pr</th>
                    </tr>
                    </thead>
                    <tbody v-for="(exam, index) in allstudent" :key="exam.id">
                      
                      <tr class="text-center">
                        <th>{{index+1}}</th>
                        <td class="text-left">{{exam.get_subject.name}}</td>
                        <td v-if="(exam.type_id) == '1'">{{exam.grade}}</td>
                        <td v-else></td>
                        <td v-if="(exam.type_id) == '2'">{{exam.grade}}</td>
                        <td v-else></td>
                        <td>
                          {{exam.grade}}
                        </td>
                        <td>{{exam.grade_point}}</td>
                        <td>
                          <span v-for="(grade, index) in allgrade" :key="grade.id">
                            <span v-if="grade.name == exam.grade">{{grade.remark}}</span>
                          </span>
                        </td> 
                      </tr>
                    </tbody>
                    <tr class="text-center">

                      <th colspan="4" class="text-right border-right-0">Average grade:</th>
                      <th class="border-left-0 border-right-0">
                       {{ average_grade }}
                      </th>
                      <th colspan="" class="text-left">GPA : {{ gpa }}</th>
                      <th colspan="" class="text-left border-left-0">Remarks : {{ remarks }}</th> 
                    </tr>
                  </table>
                </div>
                <!-- {{allobservation.is_published }}  -->
                <!-- {{ allobservation_mark.length }}  -->
                <div v-if="allobservation.is_published == 1 && allobservation_mark.length != 0">
                  <div class="text-center my-4">
                    <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">Observation</span>
                  </div>
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-6 border" v-for="(observation, index) in allobservation_mark" :key="observation.id">
                        <span>{{observation.get_observation.title}}</span>
                        <span class="ml-3"> : <b>{{observation.get_observation.remark}}</b></span>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="text-center my-4">
                  <span class="px-4 py-2 bg-primary rounded h5 font-weight-bold">Grade System</span>
                </div>
                <table class="table table-bordered table-hover table-sm">
                  <thead class="bg-dark text-center th-sticky-top">
                    <tr>
                      <th width="10">Sn</th>
                      <th width="10">Percentage</th>
                      <th width="10">Grade</th>
                      <th width="10">Grade Point</th>
                    </tr>
                  </thead>
                    <tr class="text-center" v-for="(grade, index) in allgrade" :key="grade.id">
                      <th>{{index+1}}</th>
                      <td>{{grade.max}}% - {{grade.min}}%</td>
                      <td>{{grade.name}}</td>
                      <td>
                        {{grade.grade_point}}
                      </td>
                    </tr>
                  
                </table>
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
    name: "ExamList",
    data(){
      return {
        search_terminal_list:'',
        student:'',
        userstudent:'',
        schoolinfo:'',
        terminal_info:'',
        terminal_name:'',
        sbatch:'',
        sshift:'',
        sclass:'',
        ssection:'',
        allobservation:'',
        allobservation_mark:'',
        allgrade:'',
        average_grade:'',
        gpa:'',
        remarks:'',
        terminal:'',
      }
    },
    mounted(){
      this.fetchStudent();
    },
    computed:{
      allstudent(){
        this.$Progress.start();
        var marksheet = this.$store.getters.getStudent;

        if(marksheet.length == 0) { return [ marksheet.length ]; }

        var avar = marksheet.studentlists; // student_has_mark
        this.search_terminal_list = marksheet.examlist; // Examhasclass

        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }

        this.allgrade = marksheet.gradelist; // grade
        this.allobservation = avar.[0].get_student_observation_published_one; // StudentHasObservation
        this.allobservation_mark = avar.[0].get_student_observation_published_one.get_student_observation_mark; // ObservationHasMark
        this.student = avar.[0].get_student_one; // student
        this.userstudent = avar.[0].get_student_user; //studentuserinfo
        this.schoolinfo = avar.[0].get_school; // setting
        this.terminal_info = avar.[0].get_class_exam.[0]; // exam_has_class
        this.terminal_name = avar.[0].get_class_exam.[0].get_exam; // exam
        this.sbatch = avar.[0].get_student_one.get_batch; // batch
        this.sshift = avar.[0].get_student_one.get_shift; // shift
        this.sclass = avar.[0].get_student_one.get_class; // class
        this.ssection = avar.[0].get_student_one.get_section; // section
        // total result
        console.log(this.allobservation);
        this.average_grade = marksheet.average_grade;
        this.gpa = marksheet.gpa;
        this.remarks = marksheet.remarks;
        
        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchStudent(){
        // debugger;
        this.$store.dispatch('getAllStudent', [this.terminal])
      },
      searchTerminal(){
        this.fetchStudent();
        var terminal = this.terminal;
        Toast.fire({
          icon: 'success',
          title: 'Searching Result.'
        })
      },
    }
  }
</script>

<style scoped>
</style>