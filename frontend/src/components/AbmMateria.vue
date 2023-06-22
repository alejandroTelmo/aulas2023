<template>
  <v-form v-model="valid" @submit.prevent>
    <v-card>
      <v-card-title>Agregar Materia</v-card-title>
      <v-card-text>
        <v-text-field
          @keyup.enter="submit"
          v-model="nombreMateria"
          label="Nombre"
          :rules="nombreRules"
            required
        ></v-text-field>
        <v-text-field
          @keyup.enter="submit"
          v-model="alumnos"
          label="Alumnos"
          :rules="alumnosRules"
          required
        ></v-text-field>
        <v-text-field
          @keyup.enter="submit"
          v-model="cant_alumnos"
          label="Cantidad de Alumnos"
         
            required
        ></v-text-field>
        <v-select
          label="Seleccionar Profesor"
          v-model="selectedProfesor"
          :items="profesores"
        ></v-select>
        <v-select
          label="Seleccionar Carrera"
          v-model="selectedCarrera"
          :items="carreras"
        ></v-select>
        <template>
  
</template>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid">Guardar</v-btn>
        <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
/* import { required } from "vuelidate/lib/validators"; */
export default {
  props: {
    materia: {
      type: Object,
      default: () => ({ id: "", nombre: "",alumnos:"",cantidad_alumnos:"",id_carrera:"",id_profesor:"" }),
    },
    editar: {
      type: Boolean,
      default: false,
    },
  },
  /*   validations: {
    nuevoNombre: { required },
  }, */
  data() {
    return {

      valid: false,
      id:0,
      alumnos: "",
      cant_alumnos:"",
      nombreMateria: "",
      profesores:[],
      selectedProfesor:null,
      carreras:[],
      selectedCarrera:null,
      id_carrera:"",
      id_profesor:"",
      nombreRules: [
        v => !!v || 'El nombre es requerido',
      ],
      alumnosRules: [
        v => !!v || 'El alumno es requerido',
      ],
    };
  },
  watch: {
    materia: {
      handler(nuevoValor) {
        this.nombre = nuevoValor.nombre;
      },
    },
  },
  methods: {
    submit() {
      console.log(this.editar);
      if (this.editar) {
        this.editarMateria();
      } else {
        this.guardarMateria();
      }
    },
    guardarMateria() {
      const data = {
        nombre: this.nombreMateria,
        alumnos:this.alumnos,
        cant_alumnos:this.cant_alumnos,
        id_carrera:this.id_carrera,
        id_profesor:this.id_profesor,
      };
      console.log(data);
     var that=this;
      this.axios
        .post("/apiv1/materia", data)
        .then(function (response) {
          console.log(response);
          that.nuevoNombre = "";
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.$emit("guardar");
        });
    },
    editarMateria() {
      const data = {
        id:this.id,
        nombre: this.nombreMateria,
        alumnos:this.alumnos,
        cant_alumnos:this.cant_alumnos,
        id_carrera:this.id_carrera,
        id_profesor:this.id_profesor,

      };
      var that = this;
      this.axios
        .patch(`/apiv1/materia/${this.materia.id}`, data)
        .then(function (response) {
          console.log(response);
          /* alert("Registro Guardado!!"); */
          that.nuevoNombre = "";
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.$emit("guardar");
        });
    },
    getProfesores(){
      var that=this;
      this.axios.get('/apiv1/profesor')
        .then(response => {
          console.log(response.data);
          that.profesores = response.data.map(profesor => profesor.apellido);
        })
        .catch(error => {
          console.error(error);
        });
    },
    getCarreras(){
      var that=this;
      this.axios.get('/apiv1/carrera')
        .then(response => {
          console.log(response.data);
          that.carreras = response.data.map(carrera => carrera.nombre);
        })
        .catch(error => {
          console.error(error);
        });
    },
    cancelar() {
      this.$emit("cancelar");
    },
  },
  created() {
    this.materiaLocal = this.materia
    this.getProfesores();
    this.getCarreras();
  },
  /*   beforeDestroy() {
    this.nuevoNombre = "";
  }, */
};
</script>
