<template>
  <div>
    <div class="content-header">
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card card-outline card-primary" v-for="homework in description" :key="homework.id">
              <div class="card-header">
                <h3 class="card-title mb-0">
                  <i class="fas fa-text-width"></i>
                  {{homework.get_user.name}}
                </h3>
              </div>
              <div class="card-body">
                <h4 class="font-weight-bolder"> Homework </h4>
                <ckeditor :config="editorConfig" :value="homework.description" :read-only="true"></ckeditor>
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
    name: "NoticeReadMore",
    data(){
      return{
        description:'',
        detail:'',
        editorConfig: {
                            toolbar: [ [ 'Bold','Italic','Format','List', 'undo','redo' ] ]
                        }
      }
    },
    created(){
      axios.get(`/student/homework/${this.$route.params.homeworkid}`)
      .then((response)=>{
        this.description = response.data.homeworks;
        // this.detail = response.data.homeworks.[0].description;
      })
    },
  }
</script>
<style scoped>
</style>