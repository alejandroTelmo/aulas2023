<template>
   <v-form ref= "form" v-model="valid" @submit.prevent>
      <v-card>
        <v-card-title>Ingreso</v-card-title>
        <v-card-text>
            <v-text-field
                v-model="usuario"
                label="Usuario"
                required
                :rules="usuarioRules"
                ></v-text-field>
                <v-text-field
                v-model="contrasenia"
                label="Contraseña"
                type="password"
                required
                :rules="contraseniaRules"
                ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="$store.dispatch('loginAttempt')" :disabled="!valid">Ingresar</v-btn>
        </v-card-actions>
      </v-card>
    </v-form>
</template>
  
  <script>
  export default {
    
  
    data: () => ({
        valid: false,
        usuario:"",
        contrasenia:"",
        usuarioRules:[v => !!v || "El usuario es obligatorio"],
        contraseniaRules:[v => !!v || "La contraseña es obligatoria"],
    }),
    methods: {
      ingresar(){

      }
      },
    }
  
  </script>