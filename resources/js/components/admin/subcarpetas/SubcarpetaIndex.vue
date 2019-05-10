
<template>
    <v-container v-if="registros">
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
                    <v-flex xs12>
                        <v-data-table
                        :headers="headers"
                        :items="this.subcarpetas"
                        rows-per-page-text="Registros por página"
                        >
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.id }}</td>
                                <td>{{ props.item.nombre }}</td>
                                <td>{{ props.item.carpeta.nombre }}</td>
                                <td :class="props.item.color"><span class="white--text">{{ props.item.color }}</span></td>
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
    </v-container>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
import MenuOpe from './MenuOpe'
  export default {
    components: {
        'my-dialog': MyDialog,
        'menu-ope': MenuOpe,
    },
    data () {
      return {
        titulo: "Subcarpeta",
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
            text: 'Carpeta',
            align: 'left',
            value: 'nombre'
          },
          {
            text: 'Color',
            align: 'Left',
            value: 'color'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        subcarpetas:[],
        status: false,
		registros: false,
        dialog: false,
        carpeta_id: 0,

      }
    },
    mounted()
    {

        axios.get('/admin/subcarpetas')
            .then(res => {

                this.subcarpetas = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        create(){
            this.$router.push({ name: 'subcarpeta.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'subcarpeta.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.carpeta_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/subcarpetas/'+this.carpeta_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Subcarpeta eliminada!');
                    this.subcarpetas = response.data;
                }
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
