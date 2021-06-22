<template>
  <div>
    <div class="content-header">
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <section class="col-lg-12">
            <div class="card card-outline card-primary" v-for="notice in description" :key="notice.id">
              <div class="card-header">
                <h3 class="card-title mb-0">
                  <i class="fas fa-text-width"></i>
                  {{notice.get_user.name}}
                </h3>
              </div>
              <div class="card-body">
                <h4 class="font-weight-bolder">{{ notice.get_notice.title }}</h4> 
                <ckeditor :config="editorConfig" :value="notice.get_notice.description" :read-only="true"></ckeditor>
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
                        toolbar: [ 
                                  [ 'Bold', 'Italic','Align' ],
                                  [ 'Styles', 'Format', 'Font', 'FontSize' ],
                                  [ 'NumberedList', 'BulletedList', '-' , 'Outdent', 'Indent' , '-', 'Blockquote' ],
                                  [ 'Alignment' ],
                                  [ 'Paste', 'PasteText', 'PasteFromWord' ],
                                  [ 'Table', 'HorizontableRule', 'SpecialChar' ],
                                ]
                      }
      }
    },
    created(){
      axios.get(`/student/notice/${this.$route.params.noticeid}`)
      .then((response)=>{
        this.description = response.data.notices;
        this.detail = response.data.notices.[0].get_notice.description;
        console.log(this.description);
      })
    },
  }
</script>
<style scoped>
</style>