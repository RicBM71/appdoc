<template>
    <div>
        <v-layout row wrap>
            <v-flex xs3>
                 <v-btn round block large color="grey" class="blue-grey lighten-3" @click="goAlbaranes()">
                     Albaranes
                     <v-icon right dark>keyboard</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex xs3>
                <v-btn
                    round block large color="grey" class="blue-grey lighten-3"
                    @click="goClientes()">
                        Clientes
                    <v-icon right dark>group</v-icon>
                </v-btn>
            </v-flex>
            <v-flex xs1></v-flex>
            <v-flex xs3>
                <v-btn
                    round block large color="grey" class="blue-grey lighten-3"
                    @click="goProductos()">
                    Productos
                    <v-icon right dark>local_offer</v-icon>
                 </v-btn>
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
