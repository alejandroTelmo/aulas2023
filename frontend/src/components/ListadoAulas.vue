<template>
    <div>
      <v-data-table
        :headers="headers"
        :items="listadoAulas"
        :options.sync="options"
        :server-items-length="cantTotalListadoAulas"
        :loading="loading"
        :footer-props="footerProps"
        sort-by="id"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Listado Aulas</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="agregarAula">Agregar Aulas</v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editarAula(item)">
            mdi-pencil
          </v-icon>
          <v-icon small @click="mostrarConfirmacionEliminar(item)"> mdi-delete </v-icon>
        </template>
      </v-data-table>
  
      <v-dialog v-model="mostrarAbmAula" max-width="500px">
        <AbmAulas
          :aula="aulaSeleccionada"
          :editar="editar"
          @guardar="guardarAbmAula"
          @cancelar="cancelarAbmAula"
        />
      </v-dialog>

      <v-dialog v-model="mostrarConfirmDialog" max-width="320px" persistent>
      <ConfirmDialog
        :id="aulaSeleccionada.id"
        @confirmar="eliminarAula(aulaSeleccionada)"
        @cancelar="mostrarConfirmDialog = false"
      />
    </v-dialog>

    </div>
  </template>
  
  <script>
  import AbmAulas from "./AbmAulas.vue";
  import ConfirmDialog from "./ConfirmDialog.vue";
  export default {
    components: {
    AbmAulas,
    ConfirmDialog
},
    data() {
      return {
        cantTotalListadoAulas: 0,
        loading: true,
        options: {},
        headers: [
          { text: "ID", value: "id" },
          { text: "Descripcion", value: "descripcion" },
          { text: "Ubicacion", value: "ubicacion"},
          { text: "Cantidad de PC's", value: "cant_pcs" },
          { text: "Cantidad de Proyectores", value: "cant_proyectores" },
          { text: "Es Climatizada", value: "es_climatizada"},
          { text: "Aforo", value: "aforo"},
          { text: "Acciones", value: "actions", sortable: false },
        ],
        footerProps:{
          'items-per-page-options': [10, 25, 50]
        },
        listadoAulas: [],
        mostrarAbmAula: false,
        aulaSeleccionada: {
          id:0,
          descripcion: "",
          ubicacion: "",
          cant_pcs: 0,
          cant_proyectores: 0,
          es_climatizada: false,
          aforo: 0,
        },
        editar: false,
        mostrarConfirmDialog: false,
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
            `/apiv1/aula?per-page=${itemsPerPage}&page=${page}&sort=${sortMode}${sortBy}`
          )
          .then(function (response) {
            if (response.status === 200) {            
              that.listadoAulas = response.data;
              that.cantTotalListadoAulas = parseInt(
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
      mostrarFormularioAbmAulas() {
        this.mostrarAbmAula = true;
      },
      agregarAula() {
        this.editar = false;
        this.aulaSeleccionada = {};
        this.mostrarFormularioAbmAulas();
      },
      editarAula(aula) {
        console.log(aula);
        this.aulaSeleccionada = {
          id: aula.id,
          descripcion: aula.descripcion,
          ubicacion: aula.ubicacion,
          cant_pcs: aula.cant_pcs,
          cant_proyectores: aula.cant_proyectores,
          es_climatizada: aula.es_climatizada,
          aforo: aula.aforo,
        };
        this.editar = true;
        this.mostrarFormularioAbmAulas()
      },
      guardarAbmAula() {
        console.log("Aula Guardada con exito!");
        this.mostrarAbmAula = false;
        this.obtenerListadoDeApi();
      },
      eliminarAula(aula) {
        var that = this;
        this.axios
        .delete(`/apiv1/aula/${aula.id}`)
        .then(function (response) {
          console.log(response);
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.mostrarConfirmDialog = false;
          that.obtenerListadoDeApi();
        });
      },
      cancelarAbmAula() {
        this.mostrarAbmAula = false;
        this.aulaSeleccionada.id = "";
        this.aulaSeleccionada.descripcion = "";
        this.aulaSeleccionada.ubicacion = "";
        this.aulaSeleccionada.cant_pcs = "";
        this.aulaSeleccionada.cant_proyectores = "";
        this.aulaSeleccionada.es_climatizada = "";
        this.aulaSeleccionada.aforo = "";

      },
      mostrarConfirmacionEliminar(aula) {
      this.aulaSeleccionada = aula;
      this.mostrarConfirmDialog = true;
    },
  },
  };
  </script>