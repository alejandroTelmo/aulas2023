<template>
  <v-container
    ><v-app-bar app elevation="4">
      <v-app-bar-nav-icon @click="$emit('toogle-menu')"></v-app-bar-nav-icon>
      <v-app-bar-title>Aulas Curza</v-app-bar-title>
      <v-spacer></v-spacer>

      <div class="text-center">
        <v-menu offset-y transition="slide-x-transition" bottom right>
          <template v-slot:activator="{ on, attrs }">
            <v-btn small text fab v-bind="attrs" v-on="on">
              <v-icon>mdi-cog-outline </v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item
              v-for="(item, index) in items"
              :key="index"
              link
              @click="AbrirChangePassword()"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
      <v-btn small @click="$store.dispatch('logoutAttempt')">
        <v-icon>mdi-exit-to-app</v-icon>
      </v-btn>
    </v-app-bar>

    <!--CHANGE PASSWORD-->
    <v-dialog v-model="mostrarChangePassword" max-width="500px" persistent>
      <ChangePassword
        @guardar="guardarChangePassword"
        @cancelar="cancelarChangePassword"
      />
    </v-dialog>
  </v-container>
</template>

<script>
import ChangePassword from "./ChangePassword.vue";

export default {
  name: "MainBar",
  components: { ChangePassword },

  data: () => ({
    items: [{ title: "Cambiar Contraseña" }],
    mostrarChangePassword: false,
  }),
  methods: {
    AbrirChangePassword() {
      this.mostrarChangePassword = true;
    },
    guardarChangePassword() {
      this.mostrarChangePassword = false;
    },
    cancelarChangePassword() {
      this.mostrarChangePassword = false;
    },
  },
};
</script>
