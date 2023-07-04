<template>
  <v-form v-model="valid" @submit.prevent ref="form">
    <v-card>
      <v-card-title>Agregar Reserva</v-card-title>
      <v-card-text>
        <v-select
          label="Materia"
          v-model="selectedMateriaId"
          :items="materias"
          item-text="nombre"
          item-value="id"
          :rules="materiaRules"
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
                  :rules="fechaDesdeRules"
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
                  :rules="fechaHastaRules"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="fechaHasta"
                min="1950-01-01"
                locale="es-ar"
              ></v-date-picker>
            </v-menu>
            <v-btn color="primary" @click="getHorarioMaterias" :disabled="!isBuscarEnabled"
          >Buscar</v-btn>
          </div>
        </template>
        <!--FIN CALENDARIO DE FECHA HASTA-->
        <template>
          <v-simple-table height="150px" dense>
          <template v-slot:default>
          <thead>
            <tr>
              <th>
                N°
              </th>
              <th>
                Fecha Hora Desde
              </th>
              <th>
                Fecha Hora Hasta
              </th>
            </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in horarioMateria"
            :key="item.id"
          >
            <td>{{ index + 1 }}</td>
            <td style="text-align: left">{{ item.fh_desde }}</td>
            <td style="text-align: left">{{ item.fh_hasta }}</td>
          </tr>
        </tbody>
        </template>
        </v-simple-table>
        </template>
        <v-select
          label="Aula"
          v-model="selectedAulaId"
          :items="aulas"
          item-text="descripcion"
          item-value="id"
          :rules="aulaRules"
        ></v-select>
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
  data() {
    return {
      valid: false,
      id: "",
      selectedMateriaId: null,
      selectedAulaId: null,
      aulas: [],
      materias: [],
      horarioMateria: [],
      fechaDesde: "",
      fechaHasta: "",
      reservaLocal: null,
      materiaRules: [ v => !!v || 'El campo es requerido'],
      fechaDesdeRules: [ v => !!v || 'El campo es requerido'],
      fechaHastaRules: [ v => !!v || 'El campo es requerido'],
      aulaRules: [ v => !!v || 'El campo es requerido'],
    };
  },
  computed: {
    isBuscarEnabled() {
      return (
        this.selectedMateriaId !== null &&
        this.fechaDesde !== '' &&
        this.fechaHasta !== ''
      );
    },
  },
  methods: {
    submit() {
        this.guardarReserva();
    },
    guardarReserva() {
      let data = [];
      this.horarioMateria.forEach(item => {
        data.push({
              id_horarioMateria: item.id,
              fh_desde: item.fh_desde,
              fh_hasta: item.fh_hasta,
              id_aula: this.selectedAulaId,
            });
      });
      var that = this;
      this.axios
        .post("/apiv1/reservaaulaparahorariomateria", data)
        .then(function () {
          that.reiniciarValores();
          that.resetValidationForAbm();
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.$emit("guardar");
        });
    },
    getMaterias() {
      var that = this;
      this.axios
        .get("/apiv1/materia")
        .then((response) => {
          console.log(response.data);
          that.materias = response.data.map((materia) => {
            return { id: materia.id, nombre: materia.nombre };
          });
        })
        .catch((error) => {
          console.error(error);
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
    getHorarioMaterias() {
      const id_materia = this.selectedMateriaId;
      const fh_desde = `${this.fechaDesde} 00:00:00`;
      const fh_hasta = `${this.fechaHasta} 23:59:59`;
      var that = this;
      this.axios
        .get(`/apiv1/horariomateria/obtenerporfecha?id_materia=${id_materia}&fh_desde=${fh_desde}&fh_hasta=${fh_hasta}`)
        .then((response) => {
          that.horarioMateria = response.data
        })
        .catch((error) => {
          console.error(error);
        });
    },
    reiniciarValores() {
      this.selectedMateriaId = null;
      this.selectedAulaId = null;
      this.horarioMateria = [];
      this.fechaDesde = '';
      this.fechaHasta = '';
    },
    resetValidationForAbm() {
      this.$refs.form.resetValidation();
    },
    cancelar() {
      this.reiniciarValores();
      this.resetValidationForAbm();
      this.$emit("cancelar");
    },
  },
  created() {
    this.getMaterias();
    this.getAulas();
  },
};
</script>
