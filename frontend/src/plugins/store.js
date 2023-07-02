import Vue from "vue";
import Vuex from "vuex";
import router from "@/router";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loggedIn: false,
  },
  mutations: {
    successfulLogin(state,loggedIn){
      state.loggedIn = loggedIn;
      console.log(state.loggedIn,)
      router.push('/home')
    },
    logout(state,loggedIn){
      state.loggedIn = loggedIn;
      console.log(state.loggedIn,)
      router.push('/')
    }
  },
  actions: {
    loginAttempt({commit}){
      console.log("login")
      commit('successfulLogin',true)
    },
    logoutAttempt({commit}){
      commit('logout',false)
    }
  },
  getters: {
  
  }
});

export default store;
