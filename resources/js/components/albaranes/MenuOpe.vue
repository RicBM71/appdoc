<template>
    <div>
        <my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goCreate"
                >
                    <v-icon color="indigo darken-4">add</v-icon>
                </v-btn>
            </template>
                <span>Nuevo</span>
        </v-tooltip>
         <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="openDialog"
                >
                    <v-icon color="indigo darken-4">delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Registro</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goIndex"
                >
                    <v-icon color="indigo darken-4">list</v-icon>
                </v-btn>
            </template>
            <span>Lista</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goCliente"
                >
                    <v-icon color="indigo darken-4">group</v-icon>
                </v-btn>
            </template>
            <span>Ir a cliente</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="printPDF"
                >
                    <v-icon color="indigo darken-4">print</v-icon>
                </v-btn>
            </template>
            <span>Generar PDF</span>
        </v-tooltip>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
export default {
    props:{
        id: Number,
        cliente_id: Number
    },
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
          dialog: false,
      }
    },
    methods:{
        goCreate(){
            this.$router.push({ name: 'albaran.create' })
        },
        goIndex(){
            this.$router.push({ name: 'albaran.index' })
        },
        goCliente(){
                this.$router.push({ name: 'cliente.edit', params: { id: this.cliente_id } })
        },
        printPDF(){

            var url = '/ventas/albacabs/'+this.id+'/print';

            window.open(url, '_blank');
        },
        openDialog (){
            this.dialog = true;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/ventas/albacabs/'+this.id,{_method: 'delete'})
                .then(response => {
                this.albaranes = response.data;
                this.$router.push({ name: 'albaran.index' })
                this.$toast.success('Albarán eliminado!');

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
