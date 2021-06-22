<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Password Change</h1>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card">
              <form role="form" enctype="multipart/form-data" @submit.prevent="changePassword()">
                <div class="card-body">
                  <div class="form-group">
                    <label for="current_password">Old Password</label>
                    <input type="password" class="form-control" id="current_password" placeholder="Please enter old Password" v-model="form.current_password" name="current_password" :class="{ 'is-invalid': form.errors.has('current_password') }">
                    <has-error :form="form" field="current_password"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control" id="new_password" placeholder="Please enter new Password" v-model="form.new_password" name="new_password" :class="{ 'is-invalid': form.errors.has('new_password') }">
                    <has-error :form="form" field="new_password"></has-error>
                  </div>
                  <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" placeholder="please enter same as new Password" v-model="form.new_password_confirmation" name="new_password_confirmation" :class="{ 'is-invalid': form.errors.has('new_password_confirmation') }">
                    <has-error :form="form" field="new_password_confirmation"></has-error>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" :disabled="state.isSending">{{state.isSending ? "Loading..." : "Add User"}}</button>
                </div>
              </form>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  export default {
    name: "ChangePassword",
    data(){
      return {
        form: new Form({
          current_password: '',
          new_password:'',
          new_password_confirmation:'',
        }),
        state: {
          isSending: false
        }
      }
    },
    methods:{
      changePassword(){
        this.form.post('/student/change-password')
        .then((response)=>{
          if (response.data.status) {
            Toast.fire({
              icon: 'success',
              title: response.data.message
            });
            location.reload();
          }else{
            Toast.fire({
              icon: 'error',
              title: response.data.message
            });
          }
        })
        .catch((errors)=>{
          console.log(errors);

        })
      }
    }
  }
</script>

<style scoped>
</style>