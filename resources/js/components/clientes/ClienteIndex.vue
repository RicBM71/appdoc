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
                    </v-card-title>
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
                                :items="clientes"
                                @update:pagination="updateEventPagina"
                                :pagination.sync="pagination"
                                :search="search"
                                rows-per-page-text="Registros por página"
                                >
                                    <template slot="items" slot-scope="props">
                                        <td>{{ props.item.id }}</td>
                                        <td>{{ props.item.nombre }}</td>
                                        <td>{{ props.item.cif }}</td>
                                        <td>{{ props.item.email }}</td>
                                        <td>{{ props.item.telefono1 }}</td>
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
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import MenuOpe from './MenuOpe'
import {mapGetters} from 'vuex'
import {mapActions} from "vuex";
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
        'loading': Loading
    },
    data () {
      return {
        titulo:"Clientes",
        search:"",
        paginaActual:{},
        pagination:{
            model: "clientes",
            descending: false,
            page: 1,
            rowsPerPage: 10,
            sortBy: "nombre",
            search: ""
        },
        headers: [
          {
            text: 'ID',
            align: 'center',
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'nombre'
          },
          {
            text: 'CIF',
            align: 'left',
            value: 'cif'
          },
          {
            text: 'email',
            align: 'left',
            value: 'email'
          },
          {
            text: 'Teléfono',
            align: 'Left',
            value: 'telefono1'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        clientes:[],
        status: false,
		registros: false,
        dialog: false,
        cliente_id: 0,
        show_loading: true,

      }
    },
    mounted()
    {
        if (this.getPagination.model == this.pagination.model)
            this.updatePosPagina(this.getPagination);
        else
            this.unsetPagination();

        axios.get('/mto/clientes')
            .then(res => {

                this.clientes = res.data;
                this.registros = true;
                this.show_loading = false;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    computed: {
        ...mapGetters([
            'isAdmin',
            'getPagination'
        ]),
    },
    methods:{
        ...mapActions([
            'setPagination',
            'unsetPagination'
		]),
        updateEventPagina(obj){

            this.paginaActual = obj;

        },
        updatePosPagina(pag){

            this.pagination.page = pag.page;
            this.pagination.descending = pag.descending;
            this.pagination.rowsPerPage= pag.rowsPerPage;
            this.pagination.sortBy = pag.sortBy;

        },
        create(){
            this.$router.push({ name: 'cliente.create'})
        },
        editItem (id) {
            this.setPagination(this.paginaActual);
            this.$router.push({ name: 'cliente.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.cliente_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/clientes/'+this.cliente_id,{_method: 'delete'})
                .then(response => {

                this.clientes = response.data;
                this.$toast.success('Cliente eliminado!');


            })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
  }
</script>
