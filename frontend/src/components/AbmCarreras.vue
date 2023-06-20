<template>
  <v-form ref="form" v-model="valid" @submit.prevent>
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
        <v-btn color="primary" @click="submit" :disabled="!valid"
          >Guardar</v-btn
        >
        <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
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
  data() {
    return {
      valid: false,
      nuevoNombre: "",
      nombreRules: [(v) => !!v || "El nombre es requerido"],
    };
  },
  watch: {
    carrera: {
      handler(nuevoValor) {
        this.nuevoNombre = nuevoValor.nombre;
        if (!this.editar){
          this.resetValidationForAbm();
        }
      },
    },
  },
  methods: {
    submit() {
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
          that.resetValidationForAbm();
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
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.resetValidationForAbm();
          that.$emit("guardar");
        });
    },
    cancelar() {
      this.resetValidationForAbm();
      this.$emit("cancelar");
    },
    resetValidationForAbm() {
      this.$refs.form.resetValidation();
    },
  },
  created() {
    if (this.carrera) {
      this.nuevoNombre = this.carrera.nombre;
    }
  },
};
</script>
