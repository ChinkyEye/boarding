<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Book List</h1>
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
                </div>
              </div><!-- /.card-header -->
              <div class="card-body" v-if="allbook != 0">
                <div>
                  <span>Search Result found <b>{{totallist}}</b> </span>
                </div>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover">
                    <thead class="table-primary">                  
                      <tr>
                        <th width="20">SN</th>
                        <th>Book Name</th>
                        <th>Book No.</th>
                        <th>Class</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(book, index) in allbook" :key="book.id">
                        <td><a>{{index+1}}</a></td>
                        <td>{{book.get_book.name}}</td>
                        <td>{{book.get_book.book_code}}</td>
                        <td>{{book.get_class.name}}</td>
                        <td>{{book.issue_date}}</td>
                        <td>{{book.return_date}}</td>
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
        totallist:''
      }
    },
    mounted(){
      this.fetchBook();
    },
    computed:{
      allbook(){
        this.$Progress.start();
        var book = this.$store.getters.getBook;
        if(book.length == 0) { return [ book.length ]; }
        var avar = book.booklist;
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }
        this.totallist = parseInt(book.totallist);
        this.$Progress.finish();
        return avar;
      },
      allsubject(){
        this.$Progress.start();
        var book = this.$store.getters.getBook;
        if(book.length == 0) { return [ book.length ]; }

        var avar = book.subjectlist;
        // console.log(avar);
        if(avar.length == 0) { this.$Progress.finish(); return [ avar.length ]; }

        this.$Progress.finish();
        return avar;
      }
    },
    methods:{
      fetchBook(){
        this.$store.dispatch('getAllBook', [this.subject,this.search])
      },
      searchBook(){
        this.fetchBook();
        Toast.fire({
          icon: 'success',
          title: 'Signed in successfully'
        })
      },
      searchHomework(){
        this.fetchBook();
        var subject = this.subject;
        Toast.fire({
          icon: 'success',
          title: 'Signed in successfully'
        })
      },
    }
  }
</script>

<style scoped>
</style>