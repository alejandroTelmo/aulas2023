<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="listadoMaterias"
      :options.sync="options"
      :server-items-length="cantTotalListadoMaterias"
      :loading="loading"
      :footer-props="footerProps"
      sort-by="id"
      class="elevation-1"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Listado Materias</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="agregarMateria">Agregar Materia</v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="editarMateria(item)">
          mdi-pencil
        </v-icon>
        <v-icon small @click="eliminarMateria(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>

    <v-dialog v-model="mostrarAbmMaterias" max-width="500px">
      <AbmMateria
        :materia="materiaSeleccionada"
        :editar="editar"
        @guardar="guardarAbmMateria"
        @cancelar="cancelarAbmMateria"
      />
    </v-dialog>
  </div>
</template>

<script>
import AbmMateria from "./AbmMateria.vue";
//import axios from "axios"; // Importa axios si no lo has hecho

export default {
  components: {
    AbmMateria,
  },
  data() {
    return {
      cantTotalListadoMaterias: 0,
      loading: true,
      options: {},
      headers: [
        { text: "ID", value: "id" },
        { text: "Nombre", value: "nombre" },
        { text: "Alumno", value: "alumnos" },
        { text: "Cantidad de alumnos", value: "cant_alumnos" },
        { text: "Carrera", value: "carrera" },
        { text: "Profesor", value: "profesor" },
        { text: "Acciones", value: "actions", sortable: false },
      ],
      footerProps: {
        "items-per-page-options": [10, 25, 50],
      },
      listadoMaterias: [],
      mostrarAbmMaterias: false,
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
      let sortMode = "";
      if (sortDesc[0]) {
        sortMode = "-";
      }
      const that = this;

      this.axios
        .get(
          `/apiv1/materia?per-page=${itemsPerPage}&page=${page}&sort=${sortMode}${sortBy}`
        )
        .then(function (response) {
          if (response.status === 200) {
            const materias = response.data;
           
            const promises = materias.map((materia) => {
             
              const carreraPromise = that.axios.get(
                `/apiv1/carrera/${materia.id_carrera}`
              );
            
              const profesorPromise = that.axios.get(
                `/apiv1/profesor/${materia.id_profesor}`
              );
             
              return Promise.all([carreraPromise, profesorPromise]);
            });

            Promise.all(promises)
              .then((results) => {
                const updatedMaterias = results.map(
                  ([carreraResponse, profesorResponse], index) => {
                    const materia = materias[index];
                    materia.carrera = carreraResponse.data.nombre;
                    materia.profesor = `${profesorResponse.data.nombre} ${profesorResponse.data.apellido}`;
                    return materia;
                  }
                );

                that.listadoMaterias = updatedMaterias;
                that.cantTotalListadoMaterias = parseInt(
                  response.headers["x-pagination-total-count"]
                );
              })
              .catch((error) => {
                console.log(error);
              })
              .finally(() => {
                that.loading = false;
              });
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    mostrarFormularioAbmMaterias() {
      this.mostrarAbmMaterias = true;
    },
    agregarMateria() {
      this.editar = false;
      this.materiaSeleccionada = {};
      this.mostrarFormularioAbmMaterias();
    },
    editarMateria(materia) {
      this.materiaSeleccionada = {
        id: materia.id,
        nombre: materia.nombre,
        alumnos:materia.alumnos,
        cant_alumnos:materia.cant_alumnos,
        id_carrera:materia.id_carrera,
        id_profesor:materia.id_profesor,

      };
      this.editar = true;
      this.mostrarAbmMaterias = true;
    },
    guardarAbmMateria() {
      console.log("Materia guardada con éxito!");
      this.mostrarAbmMaterias = false;
      this.obtenerListadoDeApi();
    },
    eliminarMateria(materia) {
      const that = this;
      this.axios
        .delete(`/apiv1/materia/${materia.id}`)
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
    cancelarAbmMateria() {
      this.mostrarAbmMaterias = false;
      this.materiaSeleccionada.nombre = "";
      this.materiaSeleccionada.id = "";
    },
  },
};
</script>
