<template>
  <div class="login-container">
    <v-form ref="form" v-model="valid" @submit.prevent class="login-form">
      <v-card>
        <v-card-title>Ingreso</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="usuario"
            label="Usuario"
            required
            :rules="usuarioRules"
          >
          </v-text-field>
          <v-text-field
            v-model="contrasenia"
            label="Contraseña"
            required
            :rules="contraseniaRules"
            :append-icon="mostrarContrasenia ? 'mdi-eye' : 'mdi-eye-off'"
            :type="mostrarContrasenia ? 'text' : 'password'"
            @click:append="mostrarContrasenia = !mostrarContrasenia"
            @keyup.enter="ingresar"
          >
          </v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn
            align-self="center"
            color="primary"
            @click="$store.dispatch('loginAttempt')"
            :disabled="!valid"
            >Ingresar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
  </div>
</template>

<script>
export default {
  data: () => ({
    valid: false,
    usuario: "",
    contrasenia: "",
    mostrarContrasenia: false,
    usuarioRules: [(v) => !!v || "El usuario es obligatorio"],
    contraseniaRules: [(v) => !!v || "La contraseña es obligatoria"],
  }),
  methods: {
    ingresar() {
      this.$store.dispatch("loginAttempt");
    },
  },
};
</script>

<style lang="scss">
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}
.login-form {
  min-width: 400px;
}
</style>
