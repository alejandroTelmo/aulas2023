<template>
  <v-form v-model="valid" ref="form">
    <v-card>
      <v-container>
        <v-card-title>Agregar Horario de Materia</v-card-title>
        <v-autocomplete
          v-model="selectedItem"
          :search-input.sync="searchInput"
          clearable
          item-text="nombre"
          item-value="id"
          :items="filteredItems"
          :loading="isLoading"
          label="Materia"
          placeholder="Ingrese al menos 3 letras para filtrar"
          :rules="materiaRules"
          required
        ></v-autocomplete>
        <v-menu
          v-model="menuFechaDesde"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="fechaDesde"
              label="Fecha inicio de cuatrimestre:"
              prepend-inner-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
              :rules="fechaDesdeRules"
              required
            ></v-text-field>
          </template>
          <v-date-picker
            locale="es-ar"
            scrollable
            v-model="fechaDesde"
            @input="menuFechaDesde = false"
          ></v-date-picker>
        </v-menu>
        <v-menu
          v-model="menuFechaHasta"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="fechaHasta"
              label="Fecha fin de cuatrimestre:"
              prepend-inner-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
              :rules="fechaDesdeRules"
              required
            ></v-text-field>
          </template>
          <v-date-picker
            locale="es-ar"
            scrollable
            v-model="fechaHasta"
            @input="menuFechaHasta = false"
          ></v-date-picker>
        </v-menu>
        <v-row>
          <v-col cols="3">
            <v-select v-model="dia" :items="diasSemana" label="Día"></v-select>
          </v-col>
          <v-col cols="3">
            <v-menu
              ref="menuHoraDesde"
              v-model="menuHoraDesde"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="horaDesde"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  style="width: 140px"
                  v-model="horaDesde"
                  label="Hora Desde:"
                  persistent-placeholder
                  prepend-inner-icon="mdi-clock"
                  clearable
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker
                v-if="menuHoraDesde"
                format="24hr"
                v-model="horaDesde"
                full-width
                @click:minute="$refs.menuHoraDesde.save(horaDesde)"
              ></v-time-picker>
            </v-menu>
          </v-col>
          <v-col cols="3">
            <v-menu
              ref="menuHoraHasta"
              v-model="menuHoraHasta"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="horaHasta"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  style="width: 120px"
                  v-model="horaHasta"
                  label="Hora hasta:"
                  prepend-inner-icon="mdi-clock"
                  persistent-placeholder
                  clearable
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-time-picker
                v-if="menuHoraHasta"
                format="24hr"
                v-model="horaHasta"
                full-width
                @click:minute="$refs.menuHoraHasta.save(horaHasta)"
              ></v-time-picker>
            </v-menu>
          </v-col>
          <v-col cols="2">
            <v-checkbox v-model="esVirtual" label="Es Virtual"></v-checkbox>
          </v-col>
          <v-col cols="1" style="display: flex; align-items: center">
            <v-icon @click="agregarDia" color="primary"
              >mdi-check-circle</v-icon
            >
          </v-col>
        </v-row>
        <div v-if="showDataTable">
          <v-divider></v-divider>
          Dias Seleccionados
          <v-divider></v-divider>
          <v-row>
            <v-col cols="3">Día</v-col>
            <v-col cols="3">Hora Desde</v-col>
            <v-col cols="3">Hora Hasta</v-col>
            <v-col cols="3">Es Virtual?</v-col>
          </v-row>
        </div>
        <v-row v-for="(dia, index) in horarioDiasSemana" :key="index">
          <v-col cols="3" v-if="dia.checked">
            {{ index }}
          </v-col>
          <v-col cols="3" v-if="dia.checked">
            {{ dia.horaDesde }}
          </v-col>
          <v-col cols="3" v-if="dia.checked">
            {{ dia.horaHasta }}
          </v-col>
          <v-col cols="3" v-if="dia.checked">
            {{ virtual(dia) }}
          </v-col>
        </v-row>
      </v-container>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn color="primary" @click="submit" :disabled="!validForm"
          >Guardar</v-btn
        >
        <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
export default {
  data() {
    return {
      valid: false,
      materiaRules: [(v) => !!v || "Debe seleccionar una materia"],
      fechaDesdeRules: [(v) => !!v || "Debe seleccionar una fecha de inicio"],
      fechaHastaRules: [(v) => !!v || "Debe seleccionar una fecha de fin"],
      searchInput: "",
      selectedItem: null,
      isLoading: false,
      filteredItems: [],
      menuFechaDesde: null,
      menuFechaHasta: null,
      fechaDesde: null,
      fechaHasta: null,
      dia: null,
      horaDesde: null,
      horaHasta: null,
      esVirtual: false,
      menuHoraDesde: null,
      menuHoraHasta: null,
      showDataTable: false,
      diasSemana: [
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
        "Domingo",
      ],
      horarioDiasSemana: {
        Lunes: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 1,
        },
        Martes: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 2,
        },
        Miércoles: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 3,
        },
        Jueves: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 4,
        },
        Viernes: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 5,
        },
        Sábado: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 6,
        },
        Domingo: {
          horaDesde: null,
          horaHasta: null,
          esVirtual: null,
          checked: false,
          utcDay: 0,
        },
      },
    };
  },
  watch: {
    searchInput() {
      if (this.searchInput && this.searchInput.length >= 3) {
        this.fetchItemsFromAPI();
      } else {
        this.filteredItems = [];
      }
    },
  },
  computed: {
    validForm(){
      if (this.valid && this.showDataTable){
        console.log ('true');
        return true;
      } else {
        console.log ('false');
        return false;
      }
    }
  },
  methods: {
    agregarDia() {
      if (this.dia) {
        this.horarioDiasSemana[this.dia].horaDesde = this.horaDesde;
        this.horarioDiasSemana[this.dia].horaHasta = this.horaHasta;
        this.horarioDiasSemana[this.dia].esVirtual = this.esVirtual;
        this.horarioDiasSemana[this.dia].checked = true;

        this.dia = null;
        this.horaDesde = null;
        this.horaHasta = null;
        this.esVirtual = false;
        if (!this.showDataTable) {
          this.showDataTable = true;
        }
      }
    },
    virtual(dia) {
      if (dia.esVirtual === null) {
        return "";
      }
      if (dia.esVirtual) {
        return "SI";
      } else {
        return "NO";
      }
    },
    fetchItemsFromAPI() {
      this.isLoading = true;
      var that = this;
      this.axios
        .get(`/apiv1/materia/buscar?nombre=${this.searchInput}`)
        .then(function (response) {
          console.log(response.data);
          that.filteredItems = response.data;
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.isLoading = false;
        });
    },
    submit() {
      this.guardarCarrera();
    },
    guardarCarrera() {
      let data = [];
      Object.values(this.horarioDiasSemana).forEach((dia) => {
        if (dia.checked === true) {
          let fechas = this.obtenerFechas(dia.utcDay);
          fechas.forEach((fecha) => {
            data.push({
              id_materia: this.selectedItem,
              fh_desde: `${fecha} ${dia.horaDesde}`,
              fh_hasta: `${fecha} ${dia.horaHasta}`,
              clase_virtual: dia.esVirtual,
            });
            
          });
        }
      });
      var that = this;
      this.axios
      .post("/apiv1/horariomateria", data)
      .then(function (response) {
        console.log(response);
        that.reiniciarValores();
      })
      .catch(function (error) {
        console.log(error);
      })
      .then(function () {
        that.resetValidationForAbm();
        that.$emit("guardar");
      });
    },
    obtenerFechas(diaUtc) {
      const fechas = [];
      const fechaInicio = new Date(this.fechaDesde);
      const fechaFin = new Date(this.fechaHasta);

      while (fechaInicio.getUTCDay() !== diaUtc) {
        fechaInicio.setDate(fechaInicio.getDate() + 1);
      }

      const fechaActual = new Date(fechaInicio);
      while (fechaActual <= fechaFin) {
        const fechaFormateada = fechaActual.toISOString().slice(0, 10);
        fechas.push(fechaFormateada);
        fechaActual.setDate(fechaActual.getDate() + 7);
      }
      return fechas;
    },
    cancelar() {
      this.resetValidationForAbm();
      this.reiniciarValores();
      this.$emit("cancelar");
    },
    reiniciarValores() {
      Object.values(this.horarioDiasSemana).forEach((dia) => {
        dia.checked = false;
        dia.esVirtual = null;
        dia.horaDesde = null;
        dia.horaHasta = null;
      });
      this.showDataTable = false;
      this.fechaDesde = null;
      this.fechaHasta = null;
      this.selectedItem = null;
    },
    resetValidationForAbm() {
      this.$refs.form.resetValidation();
    },
  },
};
</script>
