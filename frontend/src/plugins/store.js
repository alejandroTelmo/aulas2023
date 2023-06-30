import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loggedIn: false,
  },
  mutations: {
    successfulLogin(state,loggedIn){
      state.loggedIn = loggedIn;
      console.log(state.loggedIn,this.$route)
      /* this.$router.push('/home' ) */
    }
  },
  actions: {
    loginAttempt({commit}){
      console.log("login")
      commit('successfulLogin',true)
    }
  },
  getters: {
  
  }
});

export default store;
