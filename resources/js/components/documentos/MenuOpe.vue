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
                    <v-icon color="primary">add</v-icon>
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
                    <v-icon color="primary">delete</v-icon>
                </v-btn>
            </template>
                <span>Borrar Registro</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0 && !cerrado">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-show="id > 0"
                    v-on="on"
                    color="white"
                    icon
                    @click="goCloud()"
                >
                    <v-icon color="primary">cloud_queue</v-icon>
                </v-btn>
            </template>
            <span>Upload documentos</span>
        </v-tooltip>
        <v-tooltip bottom v-if="id > 0 && !cerrado">
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBloqueo(1)"
                >
                    <v-icon color="primary">lock_open</v-icon>
                </v-btn>
            </template>
            <span>Bloquear adjuntos</span>
        </v-tooltip>
        <v-tooltip bottom v-else>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goBloqueo(0)"
                >
                    <v-icon color="primary">lock</v-icon>
                </v-btn>
            </template>
            <span>Desbloquear adjuntos</span>
        </v-tooltip>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goIndex"
                >
                    <v-icon color="primary">list</v-icon>
                </v-btn>
            </template>
            <span>Lista</span>
        </v-tooltip>

    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
export default {
    props:{
        id: Number,
        cerrado: Number
    },
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
          dialog: false
      }
    },
    methods:{
        goCreate(){
            this.$router.push({ name: 'documento.create' })
        },
        goIndex(){
            this.$router.push({ name: 'documento.index' })
        },
        openDialog (){
            this.dialog = true;
        },
        goCloud(){
            this.$emit('show-upload',  true);
        },
        goBloqueo(v){
            this.$emit('set-bloqueo',  v);
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/mto/documentos/'+this.id,{_method: 'delete'})
                .then(response => {
                this.$router.push({ name: 'documento.index' })
                this.$toast.success('Documento eliminado!');

            })
            .catch(err => {
                this.status = true;
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
}
</script>
