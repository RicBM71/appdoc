<template>
    <div v-show="show">
        <h3>Empresas</h3>

        <v-layout row wrap>
            <v-flex sm6
                v-for="item in empresas"
                :key="item.id"
            >
                <v-switch
                    v-on:change="setUserEmp"
                    v-model="emp_selected"

                    :label="item.nombre"
                    :value="item.id"
                    color="primary">
                ></v-switch>
            </v-flex>
        </v-layout>
    </div>
</template>
<script>
export default {
    props: ['user_id','emp_user'],
    data () {
        return {
            empresas: [],
            emp_selected: [],
            show: false
        }
    },
    mounted(){
        //console.log(this.emp_user);
            //cargamos todos las empresas disponibles
        axios.get('/admin/empresas')
            .then(res => {

                var emps = [];
                //console.log(res);
                res.data.map((e) => {
                    this.empresas.push({id: e.id, nombre: e.titulo});
                })

                this.show = (this.empresas.length > 0 );
            })
            .catch(err => {
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'users'})
            })

        this.emp_selected = this.emp_user;

    },
    methods:{
        setUserEmp(){

            axios({
                url: '/admin/users/'+this.user_id+'/empresas',
                method: 'put',
                data:
                    {
                        empresas: this.emp_selected
                    }
                })
                .then(res => {
                    axios({
                        method: 'put',
                        url: '/admin/users/'+this.user_id+'/empresa',
                        data:{ empresa: this.emp_selected[0] }
                    })
                    this.$toast.success(res.data);
                    })
                .catch(err => {
                    //console.log(err);
                    const msg_valid = err.response.data.errors;
                    for (const prop in msg_valid) {
                        this.$toast.error(`${msg_valid[prop]}`);
                    }
                });
        }
    }
}
</script>
