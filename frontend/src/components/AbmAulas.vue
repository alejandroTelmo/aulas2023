<template>
    <v-form ref= "form" v-model="valid" @submit.prevent>
      <v-card>
        <v-card-title>Agregar Carrera</v-card-title>
        <v-card-text>
            <v-text-field
                v-model="descripcion"
                label="Descripción"
                required
                :rules="descripcionRules"
                ></v-text-field>
                <v-text-field
                v-model="ubicacion"
                label="Ubicación"
                required
                :rules="ubicacionRules"
                ></v-text-field>
                <v-text-field
                v-model="cant_pcs"
                label="Cant_pcs"
                required
                ></v-text-field>
                <v-text-field
                v-model="cant_proyectores"
                label="Cant_Proyector"
                required
                ></v-text-field>
                <v-checkbox
                v-model="es_climatizada"
                label="¿Es Climatizada"
                ></v-checkbox>
                <v-text-field
                v-model="aforo"
                label="Aforo"
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
      aula: {
        type: Object,
        default: () => ({ id: "", descripcion: "" ,ubicacion: "", cant_pcs: "", cant_proyectores: "", es_climatizada: "", aforo: "" }),
      },
      editar: {
        type: Boolean,
        default: false,
      },
    },
   
    data() {
      return {
        valid: false,
        descripcion: "",
        ubicacion: "",
        cant_pcs: "",
        cant_proyectores: "",
        es_climatizada: "",
        aforo: "",
        aulaLocal: null,
        descripcionRules: [
          v => !!v || 'La descripcion es obligatoria',
        ],
        ubicacionRules: [
          v => !!v || 'La ubicacion es obligatoria',
        ],
      };
    },
    watch: {
      aula(){
        this.setearValores();
      }
    },
    methods: {
        setearValores(){
            if(this.editar){
                this.descripcion= this.aula.descripcion
                this.ubicacion= this.aula.ubicacion
                this.cant_pcs= this.aula.cant_pcs
                this.cant_proyector= this.aula.cant_proyectores
                this.es_climatizada= this.aula.es_climatizada
                this.aforo= this.aula.aforo
            }
        },

      submit() {
        console.log(this.editar);
        if (this.editar) {
          this.editarAula();
        } else {
          this.guardarAula();
        }
      },
      guardarAula() {
        const data = {
          descripcion: this.descripcion,
          ubicacion: this.ubicacion,
          cant_pcs: this.cant_pcs,
          cant_proyectores: this.cant_proyectores,
          es_climatizada: this.es_climatizada,
          aforo: this.aforo,
        };
        console.log(data);
        var that = this;
        this.axios
          .post("/apiv1/aula", data)
          .then(function (response) {
            console.log(response);
            
          })
          .catch(function (error) {
            console.log(error);
          })
          .then(function () {
            that.limpiarCampos();
            that.$emit("guardar");
          });
      },
      editarAula() {
        const data = {
            descripcion: this.descripcion,
            ubicacion: this.ubicacion,
            cant_pcs: this.cant_pcs,
            cant_proyectores: this.cant_proyectores,
            es_climatizada: this.es_climatizada,
            aforo: this.aforo,
        };
        var that = this;
        this.axios
          .patch(`/apiv1/aula/${this.aula.id}`, data)
          .then(function (response) {
            console.log(response);
            /* alert("Registro Guardado!!"); */
            
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
            this.descripcion = "";
            this.ubicacion = "";
            this.cant_pcs = "";
            this.cant_proyectores = "";
            this.es_climatizada = "";
            this.aforo = "";
      }
    },
    mounted() {
      if(this.aula){
        this.setearValores()
      }
    },
  };
  </script>