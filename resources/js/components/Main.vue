<template>
    <div>
        <v-layout row wrap>
            <v-flex xs3>
            </v-flex>
            <v-flex xs2>
                 <v-btn block large color="blue-grey" class="white--text" @click="goAlbaranes()">Albaranes</v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex xs2>
                <v-btn block large color="blue-grey" class="white--text" @click="goClientes()">Clientes</v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex xs2>
                <v-btn block large color="blue-grey" class="white--text" @click="goProductos()">Productos</v-btn>
            </v-flex>

        </v-layout>
    </div>
</template>
<script>
import {mapActions} from "vuex";
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin'
		]),
    },
    data: () => ({

    }),
    mounted(){
        axios.get('/dash')
            .then(res => {
                this.setAuthUser(res.data.user);
            })
            .catch(err => {
                console.log(err);
        })

    },
    methods:{
        ...mapActions([
				'setAuthUser'
            ]),
        goAlbaranes(){
            this.$router.push({ name: 'albaran.index' })
        },
        goClientes(){
            this.$router.push({ name: 'cliente.index' })
        },
        goProductos(){
            this.$router.push({ name: 'producto.index' })
        }
    }
  }
</script>
