<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Profile List</h1>
          </div>
          
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3" v-for="(profile, index) in allprofile" :key="profile.id">
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                  alt="User profile picture" :src="'https://manara.techware.com.np/images/student/' + profile.slug+'/'+profile.image">
                </div>

                <h3 class="profile-username text-center">{{profile.get_student_user.name}} {{profile.get_student_user.middle_name}} {{profile.get_student_user.last_name}}</h3>
                <div class="d-block text-center text-muted">
                  <span>email:{{profile.get_student_user.email}}</span>
                  <br>
                  <span>student_code:{{profile.student_code}}</span>
                </div>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Class</b> <a class="float-right">{{profile.get_class.name}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Shift</b> <a class="float-right">{{profile.get_shift.name}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Section</b> <a class="float-right">{{profile.get_section.name}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Roll No</b> <a class="float-right">{{profile.roll_no}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Register ID</b> <a class="float-right">{{profile.register_id}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Register Date</b> <a class="float-right">{{profile.register_date}}</a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">About Parent</h3>
              </div>

              <div class="card-body">
                <strong><i class="fas fa-user mr-1"></i> Father Name</strong>
                <p class="text-muted">
                  {{profile.student_has_parent.father_name}}
                </p>
                <hr> 
                <strong><i class="fas fa-user mr-1"></i> Mother Name</strong>
                <p class="text-muted">
                  {{profile.student_has_parent.mother_name}}
                </p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                <p class="text-muted">{{profile.student_has_parent.address}}</p>
                <hr>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-body">

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
  export default {
    name: "ProfileList",
    data(){
      return {

      }
    },
    mounted(){
      this.fetchProfile();
    },
    computed:{
      allprofile(){
        this.$Progress.start();
        var avar = this.$store.getters.getProfile;
        // console.log(avar);
        if(avar.length == 0) { return []; }
        return avar;
        this.$Progress.finish();
      }
    },
    methods:{
      fetchProfile(){
        this.$store.dispatch('getAllProfile')
      }
  }
}
</script>


<style scoped>
</style>