<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="listaReservaAulas"
      :options.sync="options"
      :server-items-length="cantTotalListaAulasReservadas"
      :loading="loading"
      :footer-props="footerProps"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Reservas de Aulas</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="abrirReservaMateria"
            >Reservar Materia</v-btn
          >
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="agregarReserva">Reservar Aula</v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editarReserva(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="mostrarConfirmacionEliminar(item)">
          mdi-delete
        </v-icon>

        <!-- <v-icon small @click="eliminarReserva(item)"> mdi-delete </v-icon> -->
      </template>
    </v-data-table>

    <!--RESERVA AULA-->
    <v-dialog v-model="mostrarAbmReservaAula" max-width="500px">
      <AbmReservaAula
        :reservaaula="reservaSeleccionada"
        :editar="editar"
        @guardar="guardarAbmReservaAula"
        @cancelar="cancelarAbmReservaAula"
      />
    </v-dialog>

    <!--RESERVA MATERIA-->
    <v-dialog v-model="mostrarAbmReservaMateria" max-width="500px">
      <AbmReservaMateria
        @guardar="guardarAbmReservaMateria"
        @cancelar="cancelarAbmReservaMateria"
      />
    </v-dialog>

    <v-dialog v-model="mostrarConfirmDialog" max-width="320px" persistent>
      <ConfirmDialog
        :id="reservaSeleccionada.id"
        @confirmar="eliminarReserva(reservaSeleccionada)"
        @cancelar="mostrarConfirmDialog = false"
      />
    </v-dialog>
  </div>
</template>

<script>
import AbmReservaAula from "./AbmReservaAula.vue";
import AbmReservaMateria from "./AbmReservaMateria.vue";
import ConfirmDialog from "./ConfirmDialog.vue";

export default {
  components: {
    AbmReservaAula,
    AbmReservaMateria,
    ConfirmDialog,
  },
  data() {
    return {
      cantTotalListaAulasReservadas: 0,
      loading: true,
      options: {},
      headers: [
        { text: "ID", value: "id" },
        { text: "ID Aula", value: "id_aula" },
        { text: "Fecha Hora Desde", value: "fh_desde" },
        { text: "Fecha Hora Hasta", value: "fh_hasta" },
        { text: "Observación", value: "observacion" },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      footerProps: {
        "items-per-page-options": [10, 25, 50],
      },
      listaReservaAulas: [],
      listaReservaMaterias: [],
      mostrarAbmReservaMateria: false,
      mostrarAbmReservaAula: false,
      reservaSeleccionada: {},
      materiaSeleccionada: {},
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
          `/apiv1/reservaaula?per-page=${itemsPerPage}&page=${page}&sort=${sortMode}${sortBy}`
        )
        .then(function (response) {
          if (response.status === 200) {
            that.listaReservaAulas = response.data;
            that.cantTotalListaAulasReservadas = parseInt(
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
    mostrarFormularioAbmReservaAula() {
      this.mostrarAbmReservaAula = true;
    },

    agregarReserva() {
      this.editar = false;
      this.reservaSeleccionada = {};
      this.mostrarFormularioAbmReservaAula();
    },
    abrirReservaMateria() {
      this.mostrarAbmReservaMateria = true;
    },
    editarReserva(reservaaula) {
      console.log(reservaaula.id_aula);
      console.log(reservaaula.fh_desde);
      console.log(reservaaula.fh_hasta);
      console.log(reservaaula.observacion);
      this.reservaSeleccionada = {
        id: reservaaula.id,
        id_aula: reservaaula.id_aula,
        fh_desde: reservaaula.fh_desde,
        fh_hasta: reservaaula.fh_hasta,
        observacion: reservaaula.observacion,
      };
      this.editar = true;
      this.mostrarAbmReservaAula = true;
    },

    guardarAbmReservaAula() {
      console.log("Reserva Guardada con exito!");
      this.mostrarAbmReservaAula = false;
      this.obtenerListadoDeApi();
    },
    guardarAbmReservaMateria() {
      this.mostrarAbmReservaMateria = false;
      this.obtenerListadoDeApi();
    },
    eliminarReserva(reservaaula) {
      var that = this;
      this.axios
        .delete(`/apiv1/reservaaula/${reservaaula.id}`)
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

    cancelarAbmReservaAula() {
      this.mostrarAbmReservaAula = false;
      this.reservaSeleccionada.id = "";
      this.reservaSeleccionada.id_aula = "";
      this.reservaSeleccionada.fh_desde = "";
      this.reservaSeleccionada.fh_hasta = "";
      this.reservaSeleccionada.observacion = "";
    },
    cancelarAbmReservaMateria() {
      this.mostrarAbmReservaMateria = false;
    },
    mostrarConfirmacionEliminar(reservaaula) {
      this.reservaSeleccionada = reservaaula;
      this.mostrarConfirmDialog = true;
    },
  },
};
</script>
