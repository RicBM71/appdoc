<template>
    <div v-show="show">
        <h3>Permisos Específicos</h3>
        <v-layout row wrap>
            <v-flex sm3
                v-for="item in this.permisos"
                :key="'p'+item.id"
            >
                <v-switch
                    v-on:change="setPermisosUsr"
                    v-model="permiso_usr"
                    :label="item.name"
                    :value="item.name"
                    color="primary">
                ></v-switch>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>

export default {
    props:{
        user_id: Number,
        permisos: Array,
        permisos_selected: Array
    },
    data(){
        return{
            permiso_usr: "",
            show: false
        }
    },
    mounted(){
        this.permiso_usr = this.permisos_selected;

        this.show = (this.permisos.length > 0);
    },
    methods:{
        setPermisosUsr(){
                axios({
                    method: 'put',
                    url: '/admin/users/'+this.user_id+'/permissions',
                    data:
                        {
                            permissions: this.permiso_usr
                        }
                    })
                    .then(response => {
                        this.$toast.success(response.data);
                    })
                    .catch(err => {

                        const msg_valid = err.response.data.errors;
                        for (const prop in msg_valid) {
                            this.$toast.error(`${msg_valid[prop]}`);
                        }
                    });
            },
    }
}
</script>

