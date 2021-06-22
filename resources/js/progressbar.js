import VueProgressBar from 'vue-progressbar';

Vue.use(VueProgressBar, {
  color: 'rgb(206, 16, 42)',
  failedColor: 'red',
  thickness: '10px',
  height: '10px',
  transition: {
    speed: '0.4s',
    opacity: '0.6s',
    termination: 300
  },
})