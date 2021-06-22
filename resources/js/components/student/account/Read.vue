<template>
  <div>
    <div class="content-header">
    </div>
    <section class="content">
      <div class="container-fluid">
        <button class="btn btn-info btn-sm rounded-0" onclick="PrintDiv('printTable')"><i class="fas fa-print"></i> Print</button>
        <div class="row" id="printTable">
          <div class="col-12">
            <div class="invoice p-3 mb-3">
              <div class="row">
                <div class="col-12">
                  <h4>
                    <small class="float-right">Date: {{(billtotal.created_at_np)}}</small>
                  </h4>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-12 text-center mb-2">
                  <div><small> {{schoolinfo.address}} </small></div>
                  <h3 class="m-0"> {{schoolinfo.school_name}}</h3>
                  <div><small><b>Phone:</b> {{schoolinfo.phone_no}}</small> | <small><b>Email:</b> {{schoolinfo.email}}</small></div>
                </div>
                <div class="col-sm-6 invoice-col">
                  To
                  <address>
                    <strong>{{userinfo.name}} {{userinfo.middle_name}} {{userinfo.last_name}}({{studentinfo.student_code}})</strong><br>
                    Phone/Email: {{studentinfo.phone_no}}/{{userinfo.email}}<br>
                    Roll no: {{studentinfo.roll_no}}
                  </address>
                </div>
                <div class="col-sm-6 text-right">
                  <b>Invoice Number: {{billtotal.invoice_id}}</b><br>
                  <br>
                </div>
              </div>
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped table-sm">
                    <tr>
                      <th width="10px">SN</th>
                      <th width="100px">Description</th>
                      <th width="100px" class="text-right">Price</th>
                    </tr>
                    <tr v-for="(accounts, index) in description" :key="accounts.id">
                      <td>{{index+1}}</td>
                      <td>{{accounts.get_topic.topic}}</td>
                      <td class="text-right">{{accounts.amount}}</td>
                    </tr>
                    <tr class="text-right">
                      <th colspan="2">Subtotal:</th>
                      <td>Rs. {{billtotal.total}}</td>
                    </tr>
                    <tr class="text-right">
                      <th colspan="2">Discount (Rs.)</th>
                      <td>Rs. {{billtotal.discount}}</td>
                    </tr>
                    <tr class="text-right">
                      <th colspan="2">Fine:</th>
                      <td>Rs. {{billtotal.fine}} </td>
                    </tr>
                    <tr class="text-right">
                      <th colspan="2">Total:</th>
                      <td>Rs.{{billtotal.nettotal}}</td>
                    </tr>
                    <tr class="text-right">
                      <th colspan="2">Words:</th>
                      <td class="text-capitalize">{{ billtotal.nettotal | toWords }} Only</td>
                    </tr>
                  </table>
              </div>
            </div>
            
            <div class="row no-print">
              <div class="col-12">
                <span>Bill Printed By : {{getbilluser.name}}</span>
                <span class="float-right">Bill Date: {{billtotal.created_at_np}}</span>
              </div>
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
    name: "NoticeReadMore",
    data(){
      return{
        description:'',
        schoolinfo:'',
        userinfo:'',
        studentinfo:'',
        billtotal:'',
        getbilluser:'',
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
      axios.get(`/student/account/${this.$route.params.accountid}`)
      .then((response)=>{
        this.description = response.data.accounts;
        this.schoolinfo = response.data.accounts.[0].get_school_info;
        this.userinfo = response.data.accounts.[0].get_user_info;
        this.studentinfo = response.data.accounts.[0].get_student;
        this.billtotal = response.data.accounts.[0].get_bill_info;
        this.getbilluser = response.data.accounts.[0].get_user;
      })
    },
    methods:{
      getDate(datetime) {
        let date = new Date(datetime).toJSON().slice(0,10).replace(/-/g,'/')
        return date
      },
    },
    // filters: {
    //   toWords: function (value) {
    //     if (!value) return ''
    //     return converter.toWords(value);
    //   }
    // }
  }
</script>
<style scoped>
</style>