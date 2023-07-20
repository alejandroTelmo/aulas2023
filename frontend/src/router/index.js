import Vue from "vue";
import VueRouter from "vue-router";
import AulasHome from "../components/AulasHome";
import AulasAbout from "../components/AulasAbout";
import ListadoCarreras from "../components/ListadoCarreras";
import ListadoAulas from "../components/ListadoAulas";
import ListaAulasReservadas from "../components/ListaAulasReservadas.vue";
import ListadoHorarioMaterias from "../components/ListadoHorarioMaterias";
import ListadoMaterias from "../components/ListadoMaterias";
import ListadoProfesor from "../components/ListadoProfesor";
import CalendarioReservasAulas from "../components/CalendarioReservasAulas";
import ChangePassword from "../components/ChangePassword.vue";
import store from "../plugins/store";

Vue.use(VueRouter);

const routes = [
  {
    path: "/home",
    name: "AulasHome",
    component: AulasHome,
  },
  {
    path: "/about:id",
    name: "AulasAbout",
    component: AulasAbout,
  },
  {
    path: "/carreras",
    name: "ListadoCarreras",
    component: ListadoCarreras,
  },
  {
    path: "/aulas",
    name: "ListadoAulas",
    component: ListadoAulas,
  },
  {
    path: "/reservaaula",
    name: "ListaAulasReservadas",
    component: ListaAulasReservadas,
  },
  {
    path: "/horarioMaterias",
    name: "ListadoHorarioMaterias",
    component: ListadoHorarioMaterias,
  },
  {
    path: "/materias",
    name: "ListadoMaterias",
    component: ListadoMaterias,
  },
  {
    path: "/profesor",
    name: "ListadoProfesor",
    component: ListadoProfesor,
  },
  {
    path: "/calendario",
    name: "CalendarioReservasAulas",
    component: CalendarioReservasAulas,
  },
  {
    path: "/changePasswordUser",
    name: "ChangePassword",
    component: ChangePassword,
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(route => route.path) && !store.state.loggedIn) {
    next('/')
  } else {
    next()
  }
})

export default router;
