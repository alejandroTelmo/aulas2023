import Vue from "vue";
import VueRouter from "vue-router";
import AulasHome from "../components/AulasHome";
import AulasAbout from "../components/AulasAbout";
import ListadoCarreras from "../components/ListadoCarreras"
import ListaAulasReservadas from "../components/ListaAulasReservadas.vue"

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
    path: "/reservaaula",
    name: "ListaAulasReservadas",
    component: ListaAulasReservadas,
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
