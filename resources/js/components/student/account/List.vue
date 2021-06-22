<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Account List</h1>
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
                   <!--  <template>
                      <month-picker-input :no-default="true"></month-picker-input>
                    </template> -->
                    <template>
                        <!-- <p>{{ date.month }}</p> -->
                      <month-picker-input @change="showDate" v-model="month" v-on:input="searchDateRange" ></month-picker-input>
                    <!--   <vue-monthly-picker v-model="month" v-on:input="searchDateRange"  >
                      </vue-monthly-picker> -->
                    </template>

                    <!-- <template>
                      <vue-monthly-picker v-model="month" v-on:input="searchDateRange"  >
                      </vue-monthly-picker>
                    </template> -->

                    <!-- <template>
                      <month-picker-input :no-default="true" v-model="month" v-on:input="searchDateRange"></month-picker-input>
                    </template> -->
                  </div>
                  <div class="col-md-6">
                    <select class="form-control rounded-0" id="status" v-model="status" name="status" @change="searchStatus"> 
                      <option value="">Select Your Status</option>
                      <option value="1"> School Bill</option>
                      <option value="2"> Paid Bill</option>
                    </select>
                  </div>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body" v-if="allaccount != 0">
                <div>
                  <span>Search Result found <b>{{totallist}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="20">SN</th>
                        <th>Invoice No.</th>
                        <th>Discount</th>
                        <th>Fine</th>
                        <th>Total</th>
                        <th>NetTotal</th>
                        <th>Bill Date</th>
                        <th>Bill Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(account, index) in allaccount" :key="account.id">
                        <td><a>{{index+1}}</a></td>
                        <td>
                          <router-link :to="`/account/${account.invoice_id}`"  :disabled="true"
                            :event="account.bill_type == '1' ? 'click' : ''">{{account.invoice_id}}</router-link>
                       
                        </td>
                        <td>{{account.discount}}</td>
                        <td>{{account.fine}}</td>
                        <td>{{account.total}}</td>
                        <td>{{account.nettotal}}</td>
                        <td>{{account.bill_date}}</td>
                        <td v-if="(account.bill_type) == '2'">Paid</td>
                        <td v-if="(account.bill_type) == '1'">School Bill</td>
                      </tr>
                    </tbody>
                    <tr class="text-center">
                      <th colspan="6" class="text-right border-right-0">Remaining Fee : {{remainedlist}}</th>
                      <!-- <th colspan="" class="text-left">GPA : </th> -->
                    </tr>
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
  import VueMonthlyPicker from 'vue-monthly-picker';
  import { MonthPickerInput } from 'vue-month-picker';
  export default {
    name: "AccountList",
    components: {
      DatePicker,
      VueMonthlyPicker,
      MonthPickerInput
    },
    data(){
      return {
        status:'',
        totallist:'',
        remainedlist:'',
        date: {
          from: null,
          to: null,
          month: 13,
          year: null
        },
        month:new Date().getMonth(),
      }
    },
    mounted(){
      this.fetchAccount();
    },
    computed:{
      allaccount(){
        this.$Progress.start();
        var account = this.$store.getters.getAccount;
        if(account.length == 0) { return [ account.length ]; }
        var avar = account.accountlist;
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }
        this.totallist = parseInt(account.totallist);
        this.remainedlist = parseInt(account.remainedlist);
        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchAccount(){
        this.$store.dispatch('getAllAccount', [this.month.monthIndex,this.status])
      },
      searchDateRange(){
        this.fetchAccount();
        var date_start = this.month.monthIndex;
        // console.log(this.);
        Toast.fire({
          icon: 'success',
          title: 'Search '+this.month.month+' || '+' Result.'
        })
      },
      searchAccount(){
        this.fetchAccount();
        Toast.fire({
          icon: 'success',
          title: 'Signed in successfully'
        })
      },
      searchStatus(){
        this.fetchAccount();
        var data_status = moment(this.status);
        if (data_status) { data_status = 'Present'} else { data_status = 'Absent'}
        Toast.fire({
          icon: 'success',
          title: 'Search '+data_status+' Result.'
        })
      },
      showDate (date) {
       this.date = date
     }
    }
  }
</script>

<style scoped>
</style>