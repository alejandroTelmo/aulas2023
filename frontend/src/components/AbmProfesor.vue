<template>
    <v-form v-model="valid" @submit.prevent>
      <v-card>
        <v-card-title>Agregar Profesor</v-card-title>
        <v-card-text>
          <v-text-field
            @keyup.enter="submit"
            v-model="Nombre"
            label="Nombre"
            :rules="nombreRules"
          ></v-text-field>
          <v-text-field  
            v-model="apellido"           
            label="Apellido"
            :rules="apellidoRules"
            required
          ></v-text-field>
        </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid"
          >Guardar</v-btn
        >
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
        default: () => ({ nombre: "",apellido:"",mostrarnombre : "" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
    
    data() {
      return {
        valid: false,
        Nombre: "",
        apellido:"",
        profesorLocal: null,
        nombreRules: [
          v => !!v || 'El nombre es requerido',
        ],

        apeliidoRules: [
          v => !!v || 'El apellido es requerido',
        ],
      };
    },
    watch: {
      profesor(){
        this.setearValores();
      },
    },
    methods: {
      setearValores(){
            if(this.editar){
                this.nombre = this.profesor.nombre
                this.apellido = this.profesor.apellido
                
            }
        },

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
          nombre: this.nombre,
          apellido: this.apellido,
        };
        console.log(data);
        var that = this;
        this.axios
          .post("/apiv1/profesor", data)
          .then(function (response) {
            console.log(response);
            that.nombre = "";
            that.apellido="";
          })
          .catch(function (error) {
            console.log(error);
          })
          .then(function () {
            that.limpiarCampos();
            that.$emit("guardar");
          });
      },
      editarProfesor() {
        const data = {
          nombre: this.Nombre,
        };
        var that = this;
        this.axios
          .patch(`/apiv1/profesor/${this.profesor.id}`, data)
          .then(function (response) {
            console.log(response);
           
            that.Nombre = "";
          })
          .catch(function (error) {
            console.log(error);
          })
          .then(function () {
            that.limpiarCampos();
            that.$emit("guardar");
          });
      },
      cancelar() {
        this.limpiarCampos();
        this.$refs.form.resetValidation();
        this.$emit("cancelar");
      },

      limpiarCampos(){
            this.nombre= "";
            this.apellido = "";
             }
    },
    
    mounted() {
      if(this.profesor){
        this.setearValores()
      }
    },     
  };
  </script>
  