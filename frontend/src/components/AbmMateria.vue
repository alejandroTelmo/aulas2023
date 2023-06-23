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
          v-model="selectedProfesorId"
          :items="profesores"
          item-text="apellido"
          item-value="id"
          ></v-select>
        <v-select
          label="Seleccionar Carrera"
          v-model="selectedCarreraId"
          :items="carreras"
          item-text="nombre"
          item-value="id"
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

  data() {
    return {

      valid: false,
      id:0,
      alumnos: "",
      cant_alumnos:"",
      nombreMateria: "",
      profesores:[],
      selectedProfesor:null,
      selectedProfesorId: null,
      carreras:[],
      selectedCarrera:null,
      selectedCarreraId:null,
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
        this.id = nuevoValor.id;
        this.nombreMateria = nuevoValor.nombre;
        this.alumnos = nuevoValor.alumnos;
        this.cant_alumnos = nuevoValor.cant_alumnos;
        this.id_carrera = nuevoValor.id_carrera;
        this.id_profesor = nuevoValor.id_profesor;
      },
      inmediate:true,
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
        id_carrera:this.selectedCarreraId,
        id_profesor:this.selectedProfesorId,
      };
      console.log(data);
     var that=this;
      this.axios
        .post("/apiv1/materia", data)
        .then(function (response) {
          console.log(response);
          that.nombreMateria = "";
          that.alumnos="";
          that.cant_alumnos="";
          that.selectedCarrera=null;
          that.selectedProfesor=null
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
        id:this.materia.id,
        nombre: this.nombreMateria,
        alumnos:this.alumnos,
        cant_alumnos:this.cant_alumnos,
        id_carrera:this.selectedCarreraId,
        id_profesor:this.selectedProfesorId,

      };
      var that = this;
      this.axios
        .patch(`/apiv1/materia/${this.materia.id}`, data)
        .then(function (response) {
          console.log(response);
          alert("Registro Editado con Éxito!!"); 
          that.id = "";
          that.nombreMateria = "";
          that.alumnos = "";
          that.cant_alumnos = "";
          that.id_carrera = "";
          that.id_profesor = "";
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
          that.profesores = response.data.map(profesor => {
            return { id: profesor.id, apellido: profesor.apellido };
          });
          
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
          that.carreras = response.data.map(carrera =>{
            return {id: carrera.id,nombre: carrera.nombre};
          });
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
  this.nombreMateria = this.materia.nombre;
  this.alumnos = this.materia.alumnos;
  this.cant_alumnos = this.materia.cant_alumnos;
  this.selectedCarreraId = this.materia.id_carrera;
  this.selectedProfesorId = this.materia.id_profesor;
  this.getProfesores();
  this.getCarreras();
  },
  
};
</script>
