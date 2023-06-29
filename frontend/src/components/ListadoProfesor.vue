<template>
    <div>
      <v-data-table
        :headers="headers"
        :items="listadoProfesor"
        :options.sync="options"
        :server-items-length="cantTotalListadoProfesor"
        :loading="loading"
        :footer-props="footerProps"
        sort-by="id"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Listado Profesor</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="agregarProfesor">Agregar Profesor</v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editarProfesor(item)">
            mdi-pencil
          </v-icon>
          <v-icon small @click="eliminarProfesor(item)"> mdi-delete </v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmProfesor" max-width="500px">
        <AbmProfesor
          :profesor="profesorSeleccionada"
          :editar="editar"
          @guardar="guardarAbmProfesor"
          @cancelar="cancelarAbmProfesor"
        />
      </v-dialog>
    </div>
  </template>
  
  <script>
  import AbmProfesor from "./AbmProfesor.vue";
  export default {
    components: {
      AbmProfesor,
    },
    data() {
      return {
        cantTotalListadoProfesor: 0,
        loading: true,
        options: {},
        headers: [
          { text: "id", value: "id" },
          { text: "nombre", value: "nombre" },
          { text: "apellido", value: "apellido" },
          { text: "mostrar", value: "mostrar" },
          { text: "Acciones", value: "actions", sortable: false },
        ],
        footerProps:{
          'items-per-page-options': [10, 25, 50]
        },
        listadoProfesor: [],
        mostrarAbmProfesor: false,
        profesorSeleccionada: {},
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
        console.log(sortDesc, sortMode);
        var that = this;
        this.axios
          .get(
            `/apiv1/profesor?per-page=${itemsPerPage}&page=${page}&sort=${sortMode}${sortBy}`
          )
          .then(function (response) {
            if (response.status === 200) {            
              that.listadoProfesor = response.data;
              that.cantTotalListadoProfesor = parseInt(
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
      mostrarFormularioAbmProfesor() {
        this.mostrarAbmProfesor= true;
      },
      agregarProfesor() {
        this.editar = false;
        this.profesorSeleccionada = {};
        this.mostrarFormularioAbmProfesor();
      },
      editarProfesor(profesor) {
        console.log(profesor.nombre);
        this.profesorSeleccionada = {
          id: profesor.id,
          nombre: profesor.nombre,
          apellido : profesor.apellido,
          mostrar : profesor.mostrar
        };
        this.editar = true;
        this.mostrarAbmProfesor = true;
      },
      guardarAbmProfesor() {
        console.log("Profesor Guardado con exito!");
        this.mostrarAbmProfesor = false;
        this.obtenerListadoDeApi();
      },
      eliminarProfesor(profesor) {
        var that = this;
        this.axios
          .delete(`/apiv1/profesor/${profesor.id}`)
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
      cancelarAbmProfesor() {
        this.mostrarAbmProfesor = false;
        this.profesorSeleccionada.id = "";
        this.profesorSeleccionada.nombre = "";
        this.profesorSeleccionada.apellido = "";
      },
    },
  };
  </script>             