export default{
	state:{
		profilelist:[],
		attendancelist:[],
		homeworklist:[],
		booklist:[],
		studentlist:[],
		accountlist:[],
		noticelist:[],
		eventlist:[]
	},
	getters:{
		getProfile(state){
			return state.profilelist
		},
		getAttendance(state){
			return state.attendancelist
		},
		getHomework(state){
			return state.homeworklist
		},
		getBook(state){
			return state.booklist
		},
		getStudent(state){
			return state.studentlist
		},
		getAccount(state){
			return state.accountlist
		},
		getNotice(state){
			return state.noticelist
		},
		getEvent(state){
			return state.eventlist
		}
	},
	actions:{
		getAllProfile(content, params){
			axios.get('/student/dashboard')
			.then((response)=>{
				content.commit('allprofilelist', response.data.profilelist)
			})
		},
		getAllAttendance(content, params){
			axios.get("/student/attendance?startdate="+params[0]+"&&enddate="+params[1]+"&&status="+params[2])
			.then((response)=>{
				content.commit('attendancelists', response.data)
			})
		},
		getAllHomework(content, params){
			axios.get("/student/homework?date="+params[0]+"&&search="+params[1])
			.then((response)=>{
				content.commit('allhomeworklist', response.data)
			})
		},
		getAllBook(content, params){
			axios.get("/student/book?subject="+params[0]+"&&search="+params[1])
			.then((response)=>{
				content.commit('allbooklist', response.data)
			})
		},

		getAllStudent(content, params){
			axios.get('/student/exam')
			.then((response)=>{
				content.commit('allstudentlists', response.data)
			})
		},

		getAllAccount(content, params){
			axios.get("/student/account?month="+params[0]+"&&status="+params[1])
			.then((response)=>{
				content.commit('allaccountlist', response.data)
			})
		},
		
		getAllNotice(content, params){
			axios.get("/student/notice?startdate="+params[0]+"&&enddate="+params[1]+"&&search="+params[2])
			.then((response)=>{
				content.commit('allnoticelist', response.data)
			})
		},
		getAllEvent(content, params){
			axios.get("/student/event")
			.then((response)=>{
				content.commit('alleventlist', response.data)
			})
		}
	},
	mutations:{
		allprofilelist(state, data){
			return state.profilelist = data
		},
		attendancelists(state, data){
			return state.attendancelist = data
		},
		allhomeworklist(state, data){
			return state.homeworklist = data
		},
		allbooklist(state, data){
			return state.booklist = data
		},
		allstudentlists(state, data){
			return state.studentlist = data
		},
		allaccountlist(state, data){
			return state.accountlist = data
		},
		allnoticelist(state, data){
			return state.noticelist =data
		},
		alleventlist(state, data){
			return state.eventlist =data
		}
	}

}