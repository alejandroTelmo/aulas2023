<template>
  <v-form v-model="valid" @submit.prevent>
    <v-card>
      <v-card-title>Editar Horario de Materia</v-card-title>
      <v-card-text>
        <v-autocomplete
          v-model="selectedItem"
          :search-input.sync="searchInput"
          clearable
          item-text="nombre"
          item-value="id"
          :items="filteredItems"
          :loading="isLoading"
          label="Materia"
          placeholder="Comience a escribir para filtrar"
        ></v-autocomplete>
        <v-menu
          v-model="menu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="fechaDesde"
              label="Fecha desde:"
              readonly
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker
            locale="es-ar"
            scrollable
            v-model="fechaDesde"
            @input="menu = false"
          ></v-date-picker>
        </v-menu>
        <v-menu
          ref="menu2"
          v-model="menu2"
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
              v-model="horaDesde"
              label="Hora desde:"
              clearable
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker
            format="24hr"
            v-if="menu2"
            v-model="horaDesde"
            scrollable
            full-width
            @click:minute="$refs.menu2.save(horaDesde)"
          ></v-time-picker>
        </v-menu>
        <v-menu
          v-model="menu3"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="fechaHasta"
              label="Fecha hasta:"
              readonly
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker
            locale="es-ar"
            scrollable
            v-model="fechaHasta"
            @input="menu3 = false"
          ></v-date-picker>
        </v-menu>
        <v-menu
          ref="menu4"
          v-model="menu4"
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
              v-model="horaHasta"
              label="Hora hasta:"
              clearable
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-time-picker
            format="24hr"
            v-if="menu4"
            v-model="horaHasta"
            full-width
            @click:minute="$refs.menu4.save(horaHasta)"
          ></v-time-picker>
        </v-menu>
        <v-checkbox v-model="esVirtual" label="Es virtual"></v-checkbox>
      </v-card-text>
      <v-card-actions>
        <v-btn color="primary" @click="submit">Guardar</v-btn>
        <v-btn color="secondary" @click="cancelar">Cancelar</v-btn>
      </v-card-actions>
    </v-card>
  </v-form>
</template>

<script>
export default {
  props: {
    editar: {
      type: Boolean,
      default: false,
    },
    horarioMateria: {
      type: Object,
      default: () => ({
        id: "",
        id_materia: "",
        id_reserva: "",
        fh_desde: "",
        fh_hasta: "",
        clase_virtual: "",
      }),
    },
    materia: {
      type: Object,
      default: () => ({
        id: "",
        nombre: "",
      }),
    },
  },
  data() {
    return {
      esVirtual: null,
      fechaHora: null,
      menu4: false,
      fechaDesde: null,
      fechaHasta: null,
      menu: false,
      horaDesde: null,
      menu2: false,
      horaHasta: null,
      menu3: false,
      searchInput: "",
      selectedItem: null,
      isLoading: false,
      filteredItems: [], // Elementos filtrados según la búsqueda
      valid: false,
      materiaRules: [(v) => !!v || "Seleccione una materia"],
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
    horarioMateria() {
      /* handler() { */
        this.setearValores();
      /* }, */
    },
  },
  methods: {
    fetchItemsFromAPI() {
      this.isLoading = true;
      var that = this;
      this.axios
        .get(`/apiv1/materia/buscar?nombre=${this.searchInput}`)
        .then(function (response) {
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
      if (this.editar) {
        this.editarCarrera();
      }
    },
    editarCarrera() {
      const data = {
        id_materia: this.selectedItem,
        fh_desde: `${this.fechaDesde} ${this.horaDesde}`,
        fh_hasta: `${this.fechaHasta} ${this.horaHasta}`,
        clase_virtual: this.esVirtual,
      };
      var that = this;
      this.axios
        .patch(`/apiv1/horariomateria/${this.horarioMateria.id}`, data)
        .then(function (response) {
          console.log(response);
          that.nuevoNombre = "";
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          /* that.resetFormValues(); */
          that.$emit("guardar");
        });
    },
    cancelar() {
      /* this.resetFormValues(); */
      this.$emit("cancelar");
    },
    resetFormValues() {
      this.searchInput = "";
      this.selectedItem = null;
      this.fechaDesde = null;
      this.horaDesde = null;
      this.fechaHasta = null;
      this.horaHasta = null;
      this.esVirtual = null;
      this.valid = false;
    },
    setearValores() {
      if (this.editar) {
        const clase_virtual = this.horarioMateria.clase_virtual === 'SI' ? true : false; 
        const [fechaDesde, horaDesde] = this.horarioMateria.fh_desde.split(" ");
        const [fechaHasta, horaHasta] = this.horarioMateria.fh_hasta.split(" ");
        this.searchInput = this.materia.nombre;
        this.selectedItem = this.materia.id;
        this.fechaDesde = fechaDesde;
        this.horaDesde = horaDesde.slice(0, 5);
        this.fechaHasta = fechaHasta;
        this.horaHasta = horaHasta.slice(0, 5);
        this.esVirtual = clase_virtual;
      }
    },
  },
  created() {
    if (this.horarioMateria) {
      this.setearValores();
    }
  },
};
</script>
