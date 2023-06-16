<template>
  <v-form v-model="valid" @submit.prevent>
    <v-card>
      <v-card-title>Agregar Carrera</v-card-title>
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
    carrera: {
      type: Object,
      default: () => ({ id: "", nombre: "" }),
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
      nuevoNombre: "",
      carreraLocal: null,
      nombreRules: [
        v => !!v || 'El nombre es requerido',
      ],
    };
  },
  watch: {
    carrera: {
      handler(nuevoValor) {
        this.nuevoNombre = nuevoValor.nombre;
      },
    },
  },
  methods: {
    submit() {
      console.log(this.editar);
      if (this.editar) {
        this.editarCarrera();
      } else {
        this.guardarCarrera();
      }
    },
    guardarCarrera() {
      const data = {
        nombre: this.nuevoNombre,
      };
      console.log(data);
      var that = this;
      this.axios
        .post("/apiv1/carrera", data)
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
    editarCarrera() {
      const data = {
        nombre: this.nuevoNombre,
      };
      var that = this;
      this.axios
        .patch(`/apiv1/carrera/${this.carrera.id}`, data)
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
    this.carreraLocal = this.carrera;
    console.log(this.carreraLocal.id, this.carreraLocal.nombre);
    this.nuevoNombre = this.carreraLocal.nombre;
  },
  /*   beforeDestroy() {
    this.nuevoNombre = "";
  }, */
};
</script>
