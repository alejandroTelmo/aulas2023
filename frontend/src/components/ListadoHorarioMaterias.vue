<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="listadoHorarioMaterias"
      :options.sync="options"
      :server-items-length="cantTotalListado"
      :loading="loading"
      :footer-props="footerProps"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Listado Horario de Materias</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="agregarHorarioMateria"
            >Agregar Horario de Materia</v-btn
          >
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editarHorarioMateria(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="eliminarHorarioMateria(item)">
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>

    <v-dialog v-model="mostrarAbm" max-width="550px" persistent>
      <AbmHorarioMateria
        @guardar="guardarAbm"
        @cancelar="cancelarAbm"
      />
    </v-dialog>
    <v-dialog v-model="mostrarAbmEdicion" max-width="500px">
      <AbmEdicionHorarioMateria
        :horarioMateria="horarioMateriaSeleccionada"
        :materia="materiaSeleccionada"
        :editar="editar"
        @guardar="guardarAbmEdicion"
        @cancelar="cancelarAbmEdicion"
      />
    </v-dialog>
  </div>
</template>

<script>
import AbmHorarioMateria from "./AbmHorarioMateria.vue";
import AbmEdicionHorarioMateria from "./AbmEdicionHorarioMateria.vue";
export default {
  components: {
    AbmHorarioMateria,
    AbmEdicionHorarioMateria,
  },
  data() {
    return {
      cantTotalListado: 0,
      loading: true,
      options: {},
      headers: [
        { text: "ID", value: "id" },
        { text: "Materia", value: "materia.nombre", sortable: false },
        { text: "ID Reserva", value: "id_reserva", sortable: false },
        { text: "Fecha Hora Desde", value: "fh_desde" },
        { text: "Fecha Hora Hasta", value: "fh_hasta" },
        { text: "Es Virtual", value: "clase_virtual" },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      footerProps: {
        "items-per-page-options": [10, 25, 50],
      },
      listadoHorarioMaterias: [],
      mostrarAbm: false,
      mostrarAbmEdicion: false,
      horarioMateriaSeleccionada: {},
      materiaSeleccionada: {},
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

      let sortMode = sortDesc[0] ? 'Desc' : 'Asc';
      let sort = sortBy[0] ? sortBy[0] : 'id';

      var that = this;
      this.axios
        .get(
          `/apiv1/horariomateria?per-page=${itemsPerPage}&page=${page-1}&sort=${sort} ${sortMode}`
        )
        .then(function (response) {
          if (response.status === 200) {
            that.listadoHorarioMaterias = response.data.map((item) => {
              return {
                ...item,
                clase_virtual: item.clase_virtual ? "SI" : "NO",
              };
            });
            that.cantTotalListado = parseInt(
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
    mostrarFormularioAbm() {
      this.mostrarAbm = true;
    },
    agregarHorarioMateria() {
      this.editar = false;
      this.horarioMateriaSeleccionada = {};
      this.materiaSeleccionada = {};
      this.mostrarFormularioAbm();
    },
    editarHorarioMateria(horarioMateria) {
      this.horarioMateriaSeleccionada = {
        id: horarioMateria.id,
        id_materia: horarioMateria.materia.id,
        fh_desde: horarioMateria.fh_desde,
        fh_hasta: horarioMateria.fh_hasta,
        clase_virtual: horarioMateria.clase_virtual,
      };
      this.materiaSeleccionada = {
        id: horarioMateria.materia.id,
        nombre: horarioMateria.materia.nombre,
      };
      this.editar = true;
      this.mostrarAbmEdicion = true;
    },
    guardarAbm() {
      console.log("Horario Materia guardado con exito!");
      this.mostrarAbm = false;
      this.obtenerListadoDeApi();
    },
    eliminarHorarioMateria(carrera) {
      var that = this;
      this.axios
        .delete(`/apiv1/horariomateria/${carrera.id}`)
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
    cancelarAbm() {
      this.mostrarAbm = false;
    },
    cancelarAbmEdicion() {
      this.mostrarAbmEdicion = false;
    },
    guardarAbmEdicion() {
      console.log("Horario Materia actualizado con exito!");
      this.mostrarAbmEdicion = false;
      this.obtenerListadoDeApi();
    },
  },
};
</script>
