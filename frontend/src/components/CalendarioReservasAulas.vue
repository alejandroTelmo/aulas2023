<template>
  <div>
    <v-sheet tile height="54" class="d-flex">
      <v-row>
        <v-col cols="1">
          <v-btn icon class="ma-2" @click="$refs.calendar.prev()">
            <v-icon>mdi-chevron-left</v-icon>
          </v-btn>
        </v-col>
        <v-col cols="3">
          <v-select 
            v-model="tipo" 
            :items="tipos" 
            dense 
            outlined 
            hide-details 
            class="ma-2" 
            label="tipo"
            >
          </v-select>
        </v-col>
        <v-col cols="3">
          <v-toolbar-title 
            class="ma-2" 
            v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
        </v-col>
        <v-col cols="4">
          <v-select 
            v-model="aula" 
            :items="aulas" 
            item-text="descripcion" 
            item-value="id" 
            dense outlined 
            hide-details
            clearable 
            class="ma-2" 
            label="aulas" 
            @change="getEvents"
            >
          </v-select>
        </v-col>
        <v-col cols="1">
          <v-btn icon class="ma-2" @click="$refs.calendar.next()">
            <v-icon>mdi-chevron-right</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-sheet>
    <v-sheet height="600">
      <v-calendar 
        ref="calendar" 
        locale="es" 
        v-model="value" 
        weekdays="1, 2, 3, 4, 5, 6, 0" 
        event-overlap-mode="column"
        :type="tipo" 
        :events="eventos" 
        :event-overlap-threshold="30" 
        :event-color="getEventColor" 
        @change="getEvents"
        @click:event="showEvent"
        >
      </v-calendar>
      <v-menu 
        v-model="selectedOpen" 
        :close-on-content-click="false" 
        :activator="selectedElement" 
        offset-x
        >
        <v-card color="grey lighten-4" min-width="350px" flat>
          <v-toolbar :color="selectedEvent.color" dark>
            <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
            <v-spacer></v-spacer>
          </v-toolbar>
          <v-card-text>
            <div v-html="selectedEvent.start"></div>
            <div v-html="selectedEvent.end"></div>
          </v-card-text>
          <v-card-actions>
            <v-btn color="secondary" @click="selectedOpen = false">
              Cancel
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-menu>
    </v-sheet>
  </div>
</template>
<script>
export default {
  data() {
    return {
      value: '',
      tipo: 'month',
      tipos: [
        { text: 'Mes', value: 'month' },
        { text: 'Semana', value: 'week' },
        { text: 'Dia', value: 'day' },
        { text: '4 Dias', value: '4day' },
      ],
      eventos: [],
      colors: ['blue', 'indigo', 'deep-purple', 'cyan', 'green', 'orange', 'grey darken-1'],
      names: ['Meeting', 'Holiday', 'PTO', 'Travel', 'Event', 'Birthday', 'Conference', 'Party'],
      aulas: [],
      aula: null,
      selectedEvent: {},
      selectedElement: null,
      selectedOpen: false,
    }
  },
  methods: {
    getEvents() {
      const start = this.$refs.calendar.lastStart;
      const end = this.$refs.calendar.lastEnd;
      const fh_desde = `${start.date} 00:00:00`;
      const fh_hasta = `${end.date} 23:59:59`;
      console.log(`start: ${fh_desde}, end: ${fh_hasta}`);
      var that = this;
      this.axios
        .get(
          `/apiv1/reservaaula/calendario?fh_desde=${fh_desde}&fh_hasta=${fh_hasta}&id_aula=${that.aula}`
        )
        .then(function (response) {
          if (response.data.length > 0) {
            that.eventos = response.data.map((item) => {
              return {
                ...item,
                name: item.nombre ? item.nombre : item.observacion,
                color: that.colors[that.rnd(0, that.colors.length - 1)],
                timed: false,
              };
            });
          } else {
            that.eventos = [];
          }
        })
        .catch(function (error) {
          console.log(error);
        })
        .then(function () {
          that.loading = false;
        });
    },
    getEventColor(event) {
      return event.color
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    },
    showEvent({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event
        this.selectedElement = nativeEvent.target
        requestAnimationFrame(() => requestAnimationFrame(() => this.selectedOpen = true))
      }

      if (this.selectedOpen) {
        this.selectedOpen = false
        requestAnimationFrame(() => requestAnimationFrame(() => open()))
      } else {
        open()
      }
      nativeEvent.stopPropagation()
    },
  },
  mounted() {
    if (this.$refs.calendar) {
      this.$refs.calendar.checkChange()
    }
    var that = this;
    this.axios
      .get(`/apiv1/aula/todos`)
      .then(function (response) {
        that.aulas = response.data
      })
      .catch(function (error) {
        console.log(error);
      })
      .then(function () {
      });
  },
};
</script>