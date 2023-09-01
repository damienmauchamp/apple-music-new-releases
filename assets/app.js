import Vue from 'vue'
import App from './components/App.vue'

const vm = new Vue({
	render: h => h(App)
})

vm.$mount('#app')
