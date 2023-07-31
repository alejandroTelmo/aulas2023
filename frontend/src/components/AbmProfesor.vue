<template>
    <v-form ref= "form" v-model="valid" @submit.prevent>
      <v-card>
        <v-card-title>Agregar Profesor</v-card-title>
        <v-card-text>
            <v-text-field  
            v-model="nombre"
            label="Nombre"
            :rules="nombreRules"
            ></v-text-field>
            <v-text-field  
            v-model="apellido"           
            label="Apellido"
            :rules="apellidoRules"
            required
          ></v-text-field>
          <v-text-field  
            v-model="mostrar"
            label="Texto a mostrar"
            :rules="mostrarRules"
             ></v-text-field>
        </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!valid"
          >Guardar</v-btn>
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
        default: () => ({ id:"",nombre: "",apellido:"",mostrar:"" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
    
    data() {
      return {
        valid: false,
        id: "",
        nombre: "",
        apellido:"",
        mostrar:"",
        profesorLocal: null,
        mostrarRules: [
          v => !!v || 'Texto a mostrar es requerido',
        ],
        nombreRules: [
          v => !!v || 'El nombre es requerido',
        ],
        apellidoRules: [
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
                this.id = this.profesor.id
                this.nombre = this.profesor.nombre
                this.apellido = this.profesor.apellido
                this.mostrar =this.profesor.mostrar
                
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
          id: this.id,
          nombre: this.nombre,
          apellido: this.apellido,
          mostrar: this.mostrar

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
          apellido: this.apellido,
          mostrar: this.mostrar,

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
            this.mostrar = "";
             }
    },
    
    mounted() {
      if(this.profesor){
        this.setearValores()
      }
    },     
  };
  </script>
  