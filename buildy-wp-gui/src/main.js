/*eslint no-undef: 0*/
import Vue from 'vue'
import App from './App.vue'
import store from './store'
import { labelUCFirst } from './functions/helpers'
// import 'prismjs'
// import 'prismjs/themes/prism.css'

import { Hooks } from './functions/hooks'
// App-wide hooks
const hooks = new Hooks()
// Set it on the Vue prototype so that all components can use it. e.g this.$hmw_hook.run(name, ...args)
Object.defineProperty(Vue.prototype, '$hmw_hook', {
  get: function () {
    return hooks
  }
})

Vue.config.productionTip = false

import '@/assets/css/tailwind.css'

import VModal from 'vue-js-modal'
Vue.use(VModal)

const files = require.context('./components', false, /\.vue$/i)
const filesRecursive = require.context('./components', true, /\.vue$/i)

filesRecursive.keys().map(key => {
  let name = key.split('/').pop().split('.')[0];
  Vue.component(name, filesRecursive(key).default)
})

const validComponents = []
files.keys().map(key => {
  let component = files(key).default;

  if (component.name) {
    validComponents.push({
      name: component.data().alias ? labelUCFirst(component.data().alias) : labelUCFirst(component.name),
      type: component.name,
      icon: component.data().icon,
      customSettings: component.data().customSettings
    })
  }
})

let config = '';

if (document.getElementById('config')) {
  const configElement = document.getElementById('config');
  config = configElement.innerHTML;
}

let content;
if (document.getElementById('content') && document.getElementById('content').value) {
  content = JSON.parse(document.getElementById('content').value)
}

new Vue({
  store,
  render: h => h(App, { props: { config: config || [], content: content, validComponents: validComponents } })
}).$mount('#app')

