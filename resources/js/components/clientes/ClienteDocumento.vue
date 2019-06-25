<template>
    <div>

        <v-card>
            <v-layout row wrap>
                <v-flex xs6></v-flex>
                <v-flex xs6>
                    <v-spacer></v-spacer>
                    <v-text-field
                        v-model="search"
                        append-icon="search"
                        label="Buscar"
                        single-line
                        hide-details
                    ></v-text-field>
                </v-flex>
            </v-layout>
            <br/>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-data-table
                    :pagination.sync="pagination"
                    :headers="headers"
                    :items="documentos"
                    :search="search"
                    rows-per-page-text="Registros por página"
                    >
                        <template slot="items" slot-scope="props">
                            <td>{{ formatDate(props.item.fecha) }}</td>
                            <td>{{ props.item.concepto }}</td>
                            <td class="justify-center layout px-0">
                                <v-icon
                                    small
                                    class="mr-2"
                                    @click="editItem(props.item.id)"
                                >
                                    edit
                                </v-icon>
                            </td>
                        </template>
                        <template slot="pageText" slot-scope="props">
                            Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                        </template>
                    </v-data-table>
                </v-flex>
            </v-layout>
        </v-card>
    </div>
</template>
<script>
import {mapGetters} from 'vuex';
import moment from 'moment';
  export default {
    props:{
        documentos: Array
    },
    data () {
      return {
        titulo:"Documentos",
        search:"",
        headers: [
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha'
          },
          {
            text: 'Concepto',
            align: 'left',
            value: 'concepto'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        pagination:{
            descending: true,
            sortBy: fecha,
        },
        status: false,
		registros: false,

      }
    },
    computed:{
        ...mapGetters([
            'hasDocumenta'
         ]),
    },
    methods:{
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        editItem (id) {
            if (this.hasDocumenta)
                this.$router.push({ name: 'documento.edit', params: { id: id } })
            else
                this.$router.push({ name: 'documento.show', params: { id: id } })
        },
    }
  }
</script>
