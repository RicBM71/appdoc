<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div v-if="registros">
            <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <menu-ope></menu-ope>
                    <v-btn
                        color="white"
                        icon
                        @click="filtro = !filtro"
                    >
                        <v-icon color="primary">list_alt</v-icon>
                    </v-btn>
                </v-card-title>
            </v-card>
            <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs4 d-flex>
                                <v-select
                                    v-model="archivo_id"
                                    :items="archivos"
                                    label="Archivo"
                                ></v-select>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    v-model="menu_d"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >

                                    <v-text-field
                                        slot="activator"
                                        :value="computedFechaD"
                                        label="Desde"
                                        append-icon="event"
                                        readonly

                                        data-vv-as="Desde"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="fecha_d"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu_d = false"
                                        ></v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    v-model="menu_h"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >

                                    <v-text-field
                                        slot="activator"

                                        :value="computedFechaH"
                                        label="Hasta"
                                        append-icon="event"
                                        readonly
                                        data-vv-as="Hasta"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="fecha_h"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu_h = false"
                                        ></v-date-picker>
                                </v-menu>
                            </v-flex>

                            <v-spacer></v-spacer>
                            <v-flex sm2>
                                <v-btn @click="filtrar"  round  block  color="info">
                                    Filtrar
                                </v-btn>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-form>
            </v-card>
            <v-card>
                <v-container>
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
                            :headers="headers"
                            :items="documentos"
                            :search="search"
                            rows-per-page-text="Registros por página"
                            >
                                <template slot="items" slot-scope="props">
                                    <td>{{ formatDate(props.item.fecha) }}</td>
                                    <td>{{ props.item.archivo.nombre }}</td>
                                    <td>{{ props.item.carpeta.nombre }}</td>
                                    <td>{{ props.item.concepto }}</td>
                                    <td class="text-xs-right">{{ props.item.importe | currency('€', 2, { thousandsSeparator:'.', thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                    <td class="justify-center layout px-0">
                                        <v-icon
                                            small
                                            class="mr-2"
                                            @click="editItem(props.item.id)"
                                        >
                                            edit
                                        </v-icon>


                                        <v-icon
                                        small
                                        @click="openDialog(props.item.id)"
                                        >
                                        delete
                                        </v-icon>
                                    </td>
                                </template>
                                <template slot="pageText" slot-scope="props">
                                    Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                                </template>
                            </v-data-table>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card>
        </div>
    </div>
</template>
<script>
import Loading from '@/components/shared/Loading'
import MyDialog from '@/components/shared/MyDialog'
import moment from 'moment';
import MenuOpe from './MenuOpe'
  export default {
    $_veeValidate: {
        validator: 'new'
    },
    components: {
        'menu-ope': MenuOpe,
        'my-dialog': MyDialog,
        'loading': Loading
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
            text: 'Archivo',
            align: 'left',
            value: 'archivo.nombre'
          },
          {
            text: 'Carpeta',
            align: 'left',
            value: 'carpeta.nombre'
          },

          {
            text: 'Concepto',
            align: 'left',
            value: 'concepto'
          },
          {
            text: 'Importe',
            align: 'left',
            value: 'importe'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        documentos:[],
        archivos:[],
        archivo_id:"",
        status: false,
		registros: false,
        dialog: false,
        archivo_id: 0,


        show_loading: false,
        filtro: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 8)+"01",
        fecha_h: new Date().toISOString().substr(0, 10),

      }
    },
    mounted()
    {

        this.show_loading = true;
        axios.get('/mto/documentos')
            .then(res => {

                this.documentos = res.data.documentos;
                this.archivos = res.data.archivos;
                this.registros = true;

                this.show_loading = false;
            })
            .catch(err =>{

                this.show_loading = false;

                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })

            })
    },
    computed:{
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('L') : '';
        },
        computedFechaH() {
            moment.locale('es');
            return this.fecha_h ? moment(this.fecha_h).format('L') : '';
        },
    },
    methods:{
        formatDate(f){
            if (f == null) return null;
                moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        filtrar(){

            this.show_loading = true;
            axios.post('mto/documentos/filtrar',
                    {
                        fecha_d: this.fecha_d,
                        fecha_h: this.fecha_h,
                        archivo_id: this.archivo_id
                    }
                )
                .then(res => {
                    this.filtro = false;
                    //console.log(res.data);
                    this.documentos = res.data;
                    this.show_loading = false;

                })
                .catch(err => {

                    this.show_loading = false;
                    this.$toast.error(err.response.data.message);


                });
        },
        openDialog (id){
            this.dialog = true;
            this.documento_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/documentos/'+this.documento_id,{_method: 'delete'})
                .then(response => {

                this.documentos = response.data;
                this.$toast.success('Documento eliminado!');


            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },
        editItem (id) {

            this.$router.push({ name: 'documento.edit', params: { id: id } })
        },
    }
  }
</script>
