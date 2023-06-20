<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="listadoCarreras"
      :options.sync="options"
      :server-items-length="cantTotalListadoCarreras"
      :loading="loading"
      :footer-props="footerProps"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Listado Carreras</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="agregarCarrera">Agregar Carrera</v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editarCarrera(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="eliminarCarrera(item)"> mdi-delete </v-icon>
      </template>
    </v-data-table>

    <v-dialog v-model="mostrarAbmCarreras" max-width="500px" persistent>
      <AbmCarreras
        :carrera="carreraSeleccionada"
        :editar="editar"
        @guardar="guardarAbmCarrera"
        @cancelar="cancelarAbmCarrera"
      />
    </v-dialog>
  </div>
</template>

<script>
import AbmCarreras from "./AbmCarreras.vue";
export default {
  components: {
    AbmCarreras,
  },
  data() {
    return {
      cantTotalListadoCarreras: 0,
      loading: true,
      options: {},
      headers: [
        { text: "ID", value: "id" },
        { text: "Nombre", value: "nombre" },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      footerProps:{
        'items-per-page-options': [10, 25, 50]
      },
      listadoCarreras: [],
      mostrarAbmCarreras: false,
      carreraSeleccionada: {
        id:"",
        nombre:"",
      },
      editar: false,
    };
  },
  watch: {
    options: {
      handler() {
        this.obtenerListadoDeApi();
      },
      deep: true,
    },
  },
  methods: {
    obtenerListadoDeApi() {
      const { sortBy, sortDesc, page, itemsPerPage } = this.options;
      let sortMode = "";
      if (sortDesc[0]) {
        sortMode = "-";
      }
      var that = this;
      this.axios
        .get(
          `/apiv1/carrera?per-page=${itemsPerPage}&page=${page}&sort=${sortMode}${sortBy}`
        )
        .then(function (response) {
          if (response.status === 200) {            
            that.listadoCarreras = response.data;
            that.cantTotalListadoCarreras = parseInt(
              response.headers["x-pagination-total-count"]
            );
          }
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.loading = false;
        });
    },
    mostrarFormularioAbmCarreras() {
      this.mostrarAbmCarreras = true;
    },
    agregarCarrera() {
      this.editar = false;
      this.carreraSeleccionada = {
        id: "",
        nombre: "",
      };
      this.mostrarFormularioAbmCarreras();
    },
    editarCarrera(carrera) {
      this.carreraSeleccionada = {
        id: carrera.id,
        nombre: carrera.nombre,
      };
      this.editar = true;
      this.mostrarAbmCarreras = true;
    },
    guardarAbmCarrera() {
      console.log("Carrera Guardada con exito!");
      this.mostrarAbmCarreras = false;
      this.obtenerListadoDeApi();
    },
    eliminarCarrera(carrera) {
      var that = this;
      this.axios
        .delete(`/apiv1/carrera/${carrera.id}`)
        .then(function (response) {
          console.log(response);
          alert("Registro Eliminado!!");
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.obtenerListadoDeApi();
        });
    },
    cancelarAbmCarrera() {
      this.carreraSeleccionada.nombre = "";
      this.carreraSeleccionada.id = "";
      this.mostrarAbmCarreras = false;
    },
  },
};
</script>
