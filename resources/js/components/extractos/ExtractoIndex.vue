<template>
    <div>
        <loading :show_loading="show_loading"></loading>

        <div v-if="registros">

            <v-card>
                <v-card-title>
                    <h2>{{titulo}}</h2>
                    <v-spacer></v-spacer>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on }">
                            <v-btn
                                v-on="on"
                                color="white"
                                icon
                                @click="filtro = !filtro"
                            >
                                <v-icon color="primary">filter_list</v-icon>
                            </v-btn>
                        </template>
                        <span>Filtros</span>
                    </v-tooltip>
                </v-card-title>
            </v-card>
            <v-card v-show="filtro">
                <v-form>
                    <v-container>
                        <v-layout row wrap>
                            <v-flex xs1></v-flex>
                            <v-flex sm2>
                                 <v-menu
                                    ref="menu_d"
                                    v-model="menu_d"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="fecha_d"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaD"
                                            label="Desde"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    v-model="fecha_d"
                                    type="month"
                                    locale="es"
                                    no-title
                                    scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="menu_d = false">Cancelar</v-btn>
                                    <v-btn flat color="primary" @click="$refs.menu_d.save(fecha_d)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex sm2>
                                <v-menu
                                    ref="menu_h"
                                    v-model="menu_h"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="fecha_h"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="computedFechaH"
                                            label="Hasta"
                                            prepend-icon="event"
                                            readonly
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                    v-model="fecha_h"
                                    type="month"
                                    locale="es"
                                    no-title
                                    scrollable
                                    >
                                    <v-spacer></v-spacer>
                                    <v-btn flat color="primary" @click="menu_h = false">Cancelar</v-btn>
                                    <v-btn flat color="primary" @click="$refs.menu_h.save(fecha_h)">OK</v-btn>
                                    </v-date-picker>
                                </v-menu>
                            </v-flex>
                            <v-flex xs2 d-flex>
                                <v-select
                                    v-model="dh"
                                    :items="tipos"
                                    label="D/H"
                                ></v-select>
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
                            :expand="expand"
                            :headers="headers"
                            :items="apuntes"
                            :search="search"
                            rows-per-page-text="Registros por página"

                            :pagination.sync="pagination"
                            >
                                <template slot="items" slot-scope="props">
                                    <tr>
                                    <td>{{ formatDate(props.item.fecha) }}</td>
                                    <td>{{ props.item.dh }}</td>
                                    <td>{{ props.item.concepto }}</td>
                                    <td :class=colorLin(props.item.dh)>{{ props.item.importe | currency('€', 2, { thousandsSeparator:'.', thousandsSeparator:'.', decimalSeparator: ',', symbolOnLeft: false })}}</td>
                                    <td class="justify-center layout px-0">
                                        <v-icon v-show="props.item.documentos.length > 0"
                                            small
                                            class="mr-2"
                                            @click="props.expanded = !props.expanded"
                                            color="blue lighten-1"
                                        >
                                            description
                                        </v-icon>
                                        <v-icon v-show="!props.item.documentos.length > 0 && hasDocumenta"
                                            small
                                            class="mr-2"
                                            @click="createDocu(props.item)"
                                        >
                                            sd_storage
                                        </v-icon>
                                    </td>
                                     </tr>
                                </template>
                                <template v-slot:expand="props">
                                    <v-card flat>
                                        <v-card-text :key="child.id" v-for="child in props.item.documentos">
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn
                                                        v-on="on"
                                                        color="white"
                                                        icon
                                                        @click="editDoc(child.id)"
                                                    >
                                                        <v-icon :color="estaCerrado(child.cerrado)">pageview</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Ver documento</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn
                                                        v-show="hasDocumenta"
                                                        v-on="on"
                                                        color="white"
                                                        icon
                                                        @click="detachDoc(child)"
                                                    >
                                                        <v-icon>delete_outline</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Eliminar relación</span>
                                            </v-tooltip>
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on }">
                                                    <v-btn
                                                        v-show="hasDocumenta"
                                                        v-on="on"
                                                        color="white"
                                                        icon
                                                        @click="agruparADoc(child.id)"
                                                    >
                                                        <v-icon>call_split</v-icon>
                                                    </v-btn>
                                                </template>
                                                <span>Unir extractos a documento</span>
                                            </v-tooltip>
                                            <span :class="child.archivo.color+'--text'">{{child.archivo.nombre+": "+formatDate(child.fecha)+" "+child.concepto}}</span>

                                        </v-card-text>
                                    </v-card>

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
import moment from 'moment';
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex';
  export default {
    $_veeValidate: {
      		validator: 'new'
    },
    components: {
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo:"Extracto",
        pagination:{
             rowsPerPage: 10
        },
        search:"",
        headers: [
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha'
          },
          {
            text: 'M',
            align: 'left',
            value: 'dh'
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
        apuntes:[],
        status: false,
		registros: false,
        dialog: false,
        extracto_id: 0,
        documento_id: 0,
        expand: true,

        tipos: [
                {text:'Debe','value':'D'},
                {text:'Haber','value':'H'},
                {text:'Todos','value':'T'}
            ],

        show_loading: false,
        filtro: false,

        menu_d: false,
        menu_h: false,
        fecha_d: new Date().toISOString().substr(0, 5)+"01",
        fecha_h: new Date().toISOString().substr(0, 7),
        dh:"T"
      }
    },
    mounted()
    {

        this.show_loading = true;

        axios.get('/mto/extractos')
            .then(res => {

                this.apuntes = res.data;
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
        ...mapGetters([
                'hasDocumenta',
                'isAdmin'
		]),
        computedFechaD() {
            moment.locale('es');
            return this.fecha_d ? moment(this.fecha_d).format('MM-YYYY') : '';
        },
        computedFechaH() {
            moment.locale('es');
            return this.fecha_h ? moment(this.fecha_h).format('MM-YYYY') : '';
        },
    },
    methods:{
        colorLin(dh){
            if (dh == 'D')
                return "text-xs-right red--text";
            else
                return "text-xs-right indigo--text";
        },
        formatDate(f){
            if (f == null) return null;
                moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        estaCerrado(cerrado){
            if (cerrado) return ''; else return 'warning';
        },
        filtrar(){


            this.$validator.validateAll().then((result) => {
                    if (result){
                        this.show_loading = true;
                        axios.post('mto/extractos/filtrar',
                                {
                                    fecha_d: this.fecha_d+"-01",
                                    fecha_h: this.fecha_h+"-01",
                                    dh: this.dh,
                                }
                            )
                            .then(res => {

                                this.filtro = false;

                                this.apuntes = res.data;
                                this.show_loading = false;

                            })
                            .catch(err => {

                                this.show_loading = false;
                                if (err.response.status != 419)
                                    this.$toast.error(err.response.data.message);
                                else
                                    this.$toast.error("Sesión expirada!");

                            });
                    }
            });

        },
        editDoc (id) {
            this.$router.push({ name: 'documento.edit', params: { id: id } })
        },
        createDocu(extracto){
            if (this.documento_id == 0)
                this.$router.push({ name: 'documento.create', params: { extracto: extracto } })
            else{
                this.attachDoc(extracto.id);
            }
        },
        agruparADoc(documento_id){
            this.documento_id = documento_id;
            this.titulo = "Enlazando a documento, seleccionar otro apunte";
            this.$toast.warning("Enlazando a documento, seleccionar otro apunte");
           // console.log(documento_id);
        },
        attachDoc(id){
            //console.log(id);

            if (id == 0 || this.documento_id == 0){
                this.$toast.error("No se ha podido realizar el enlace");
                return;
            }

            this.show_loading = true;
            axios.post('mto/documentos/'+this.documento_id+'/attach',
                    {
                        extracto_id: id
                    }
                )
                .then(res => {
                    this.filtro = false;

                    this.titulo = "Extracto";
                    this.$toast.success("Apunte enlazado correctamente!");

                    this.filtrar();
                    this.show_loading = false;

                })
                .catch(err => {

                    this.show_loading = false;
                    this.$toast.error(err.response.data.message);


                });

        },
        detachDoc(child){

            this.show_loading = true;
            axios.post('mto/documentos/'+child.pivot.documento_id+'/detach',
                    {
                        extracto_id: child.pivot.extracto_id
                    }
                )
                .then(res => {
                    this.filtro = false;

                    this.filtrar();
                    this.show_loading = false;

                })
                .catch(err => {

                    this.show_loading = false;
                    this.$toast.error(err.response.data.message);


                });

        },

    }
  }
</script>
