<template>
  <v-form v-model="valid" @submit.prevent>
    <v-card>
      <v-card-title>Agregar Reserva</v-card-title>
      <v-card-text>
        <v-select
          label="Aula"
          v-model="selectedAulaId"
          :items="aulas"
          item-text="descripcion"
          item-value="id"
        ></v-select>

        <!--INICIO CALENDARIO FECHA DESDE:-->
        <template>
          <div>
            <v-menu
              @keyup.enter="submit"
              :close-on-content-click="true"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  @keyup.enter="submit"
                  v-model="fechaDesde"
                  label="Fecha desde:"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker
                @keyup.enter="submit"
                v-model="fechaDesde"
                min="1950-01-01"
                locale="es-ar"
              ></v-date-picker>
            </v-menu>
          </div>
        </template>
        <!--FIN CALENDARIO DE FECHA DESDE-->

        <!--INICIO RELOJ HORA DESDE-->
        <template>
          <div>
            <v-menu
              @keyup.enter="submit"
              :close-on-content-click="false"
              ref="menu"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  @keyup.enter="submit"
                  v-model="horaDesde"
                  label="Hora desde:"
                  prepend-inner-icon="mdi-clock"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker
                @keyup.enter="submit"
                v-model="horaDesde"
                format="24hr"
              >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="$refs.menu.save(horaDesde)">
                  OK
                </v-btn>
              </v-time-picker>
            </v-menu>
          </div>
        </template>
        <!--FIN RELOJ HORA DESDE-->

        <!--INICIO CALENDARIO FECHA HASTA:-->
        <template>
          <div>
            <v-menu
              justify="center"
              @keyup.enter="submit"
              ref="menuHasta"
              :close-on-content-click="true"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  @keyup.enter="submit"
                  v-model="fechaHasta"
                  label="Fecha hasta:"
                  prepend-inner-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="fechaHasta"
                min="1950-01-01"
                locale="es-ar"
              ></v-date-picker>
            </v-menu>
          </div>
        </template>
        <!--FIN CALENDARIO DE FECHA HASTA-->

        <!--INICIO RELOJ HORA HASTA-->
        <template>
          <div>
            <v-menu
              @keyup.enter="submit"
              :close-on-content-click="false"
              ref="hasta"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  @keyup.enter="submit"
                  v-model="horaHasta"
                  label="Hora hasta:"
                  prepend-inner-icon="mdi-clock"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker v-model="horaHasta" format="24hr">
                <v-spacer></v-spacer>
                <v-btn
                  text
                  color="primary"
                  @click="$refs.hasta.save(horaHasta)"
                >
                  OK
                </v-btn>
              </v-time-picker>
            </v-menu>
          </div>
        </template>

        <!--FIN RELOJ HORA HASTA-->
        <v-text-field
          @keyup.enter="submit"
          v-model="observacionNueva"
          label="Observación:"
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
    reservaaula: {
      type: Object,
      default: () => ({
        id: "",
        id_aula: "",
        fh_desde: "",
        fh_hasta: "",
        observacion: "",
      }),
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
      id_aula: "",
      selectedAulaId: null,
      aulas: [],
      fechaDesde: "",
      horaDesde: "",
      horaHasta: "",
      fechaHasta: "",
      observacionNueva: "",
      reservaLocal: null,
      // nombreRules: [
      //   v => !!v || 'El campo es requerido',
      // ],
    };
  },

  watch: {
    reservaaula: {
      handler(nuevoValor) {
        if (nuevoValor) {
          this.id_aula = nuevoValor.id_aula;

          // Obtener fecha y hora desde nuevoValor.fh_desde
          if (nuevoValor.fh_desde) {
            const [fechaDesde, horaDesde] = nuevoValor.fh_desde.split(" ");
            this.fechaDesde = fechaDesde;
            this.horaDesde = horaDesde;
          }

          // Obtener fecha y hora desde nuevoValor.fh_hasta
          if (nuevoValor.fh_hasta) {
            const [fechaHasta, horaHasta] = nuevoValor.fh_hasta.split(" ");
            this.fechaHasta = fechaHasta;
            this.horaHasta = horaHasta;
          }

          this.observacionNueva = nuevoValor.observacion;
        }
      },
    },
  },

  methods: {
    submit() {
      console.log(this.editar);
      if (this.editar) {
        this.editarReserva();
      } else {
        this.guardarReserva();
      }
    },
    guardarReserva() {
      const data = {
        id_aula: this.selectedAulaId,
        fh_desde: `${this.fechaDesde} ${this.horaDesde}`,
        fh_hasta: `${this.fechaHasta} ${this.horaHasta}`,
        observacion: this.observacionNueva,
      };
      console.log(data);
      var that = this;
      this.axios
        .post("/apiv1/reservaaula", data)
        .then(function (response) {
          console.log(response);
          that.selectedAulaId = null;
          that.fechaDesde = "";
          that.horaDesde = "";
          that.horaHasta = "";
          that.fechaHasta = "";
          that.observacionNueva = "";
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.$emit("guardar");
        });
    },
    editarReserva() {
      const data = {
        id_aula: this.selectedAulaId,
        fh_desde: `${this.fechaDesde} ${this.horaDesde}`,
        fh_hasta: `${this.fechaHasta} ${this.horaHasta}`,
        observacion: this.observacionNueva,
      };
      var that = this;
      this.axios
        .patch(`/apiv1/reservaaula/${this.reservaaula.id}`, data)
        .then(function (response) {
          console.log(response);
          /* alert("Registro Guardado!!"); */
          that.id_aula = "";
          that.fechaDesde = "";
          that.horaDesde = "";
          that.horaHasta = "";
          that.fechaHasta = "";
          that.observacionNueva = "";
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.$emit("guardar");
        });
    },
    getAulas() {
      var that = this;
      this.axios
        .get("/apiv1/aula")
        .then((response) => {
          console.log(response.data);
          that.aulas = response.data.map((aula) => {
            return { id: aula.id, descripcion: aula.descripcion };
          });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    cancelar() {
      this.$emit("cancelar");
    },
  },
  created() {
    this.reservaLocal = this.reservaaula;

    this.selectedAulaId = this.reservaLocal.id_aula;

    if (this.reservaLocal.fh_desde) {
      const [fechaDesde, horaDesde] = this.reservaLocal.fh_desde.split(" ");
      this.fechaDesde = fechaDesde;
      this.horaDesde = horaDesde;
    }

    if (this.reservaLocal.fh_hasta) {
      const [fechaHasta, horaHasta] = this.reservaLocal.fh_hasta.split(" ");
      this.fechaHasta = fechaHasta;
      this.horaHasta = horaHasta;
    }

    this.observacionNueva = this.reservaLocal.observacion;
    this.getAulas();
  },

  /*   beforeDestroy() {
    this.nuevoNombre = "";
  }, */
};
</script>
