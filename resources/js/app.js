import Vue from 'vue';
import VueRouter from 'vue-router';
import master from '../js/views/master';
import login from '../js/views/login';
import register from '../js/views/register';
import home from '../js/views/home';
import Vuetify from 'vuetify';
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(Vuex)

const store = new Vuex.Store({
    state: {
        logged_in: false
    },
    mutations: {
        increment (state,logged_in) {
            state.logged_in = logged_in;
        }
    }
})
if(localStorage.getItem('token')){
    store.state.logged_in = true;
}

const Authenticated = (to, from, next) => {
    if (localStorage.getItem('token')) {
        next()
        return
    }
    next('/')
}
const NotAuthenticated = (to, from, next) => {
    if (!localStorage.getItem('token')) {
        next()
        return
    }
    next('/')
}
const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        {path: '/login',name:'login', component: login,beforeEnter:NotAuthenticated},
        {path: '/register',name:'register', component: register,beforeEnter:NotAuthenticated},
        {path: '/',name:'home', component: home}
    ]
});

Vue.use({
    install (Vue) {
        Vue.prototype.$api = axios.create({
            baseURL: '/api'
        })
    }
})

const app = new Vue({
    router,
    vuetify: new Vuetify(),
    store: store,
    render: h => h(master)
}).$mount('#app');
export default app