// profile
import Profile from './components/student/profile/List.vue'

//attendance
import Attendance from './components/student/attendance/List.vue'

// homework
import Homework from './components/student/homework/List.vue'
//homework readmore
import HommeworkReadMore from './components/student/homework/Read.vue'

// book
import Book from './components/student/book/List.vue'

// exam
import Exam from './components/student/exam/Exam.vue'
//exam view marksheet
import ExamMarksheet from './components/student/exam/Marksheet.vue'

//account
import Account from './components/student/account/List.vue'

//notice readmore 
import AccountReadMore from './components/student/account/Read.vue'

// notice
import Notice from './components/student/notice/List.vue'
//notice readmore 
import NoticeReadMore from './components/student/notice/Read.vue'

// event
import Event from './components/student/event/List.vue'

// changepassword
import ChangePassword from './components/student/Password.vue'



export const routes = [
	{
		path: '/',
		name: 'dashboard',
		component: Profile	
	},
	{
		path: '/attendance',
		component: Attendance	
	},
	{
		path: '/homework',
		component: Homework	
	},
	{
		path:'/homework/:homeworkid',
		component: HommeworkReadMore
	},
	{
		path:'/book',
		component: Book
	},
	{
		path:'/exam',
		component: Exam
	},
	{
		path:'/exam/:examid',
		component: ExamMarksheet
	},
	{
		path:'/account',
		component: Account
	},
	{
		path:'/notice',
		component: Notice
	},
	{
		path:'/notice/:noticeid',
		component: NoticeReadMore
	},
	{
		path:'/account/:accountid',
		component: AccountReadMore
	},
	{
		path:'/event',
		component: Event
	},
	{
		path:'/changepassword',
		component: ChangePassword
	}
]