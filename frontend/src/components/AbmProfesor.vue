<template>
    <v-form v-model="valid" @submit.prevent>
      <v-card>
        <v-card-title>Agregar Profesor</v-card-title>
        <v-card-text>
          <v-text-field
            @keyup.enter="submit"
            v-model="nuevoNombre"
            label="Nombre"
            :rules="nombreRules"
            required
          ></v-text-field>
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
      profesor: {
        type: Object,
        default: () => ({ id: "", nombre: "",apellido:"",mostrarnombre : "" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
    
    data() {
      return {
        valid: false,
        nuevoNombre: "",
        profesorLocal: null,
        nombreRules: [
          v => !!v || 'El nombre es requerido',
        ],
      };
    },
    watch: {
      profesor: {
        handler(nuevoValor) {
          this.nuevoNombre = nuevoValor.nombre;
        },
      },
    },
    methods: {
      submit() {
        console.log(this.editar);
        if (this.editar) {
          this.editarProfesor();
        } else {
          this.guardarProfesor();
        }
      },
      guardarProfesor() {
        const data = {
          nombre: this.nuevoNombre,
        };
        console.log(data);
        var that = this;
        this.axios
          .post("/apiv1/profesor", data)
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
      editarProfesor() {
        const data = {
          nombre: this.nuevoNombre,
        };
        var that = this;
        this.axios
          .patch(`/apiv1/profesor/${this.profesor.id}`, data)
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
      cancelar() {
        this.$emit("cancelar");
      },
    },
    created() {
      this.profesorLocal = this.profesor;
      console.log(this.profesorLocal.id, this.profesorLocal.nombre);
      this.nuevoNombre = this.profesorLocal.nombre;
    },
         
  };
  </script>
  