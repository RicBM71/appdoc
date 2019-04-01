<template>
	<div v-show="show">
        <mod-menu :showMenuCli="showMenuCli" :x="x" :y="y" :items="items"></mod-menu>
        <h2>{{titulo}}</h2>
        <v-form>
            <v-container @contextmenu="showMenu">
                <v-btn
                    @click="showMenu"
                    fixed
                    dark
                    fab
                    bottom
                    right
                    color="teal accent-4"
                    >
                    <v-icon>add</v-icon>
                </v-btn>
                <v-layout row wrap>
                    <v-flex sm1></v-flex>
                    <v-flex sm4>
                        <v-text-field
                            v-model="fpago.nombre"
                            v-validate="'required'"
                            :error-messages="errors.collect('nombre')"
                            label="Nombre"
                            data-vv-name="nombre"
                            data-vv-as="nombre"
                            required
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="computedFModFormat"
                            label="Modificado"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="computedFCreFormat"
                            label="Creado"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm5>
                    </v-flex>
                    <v-flex sm2>
                        <div class="text-xs-center">
                                    <v-btn @click="submit"  :loading="enviando" block  color="primary">
                            Guardar
                            </v-btn>
                        </div>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-form>

	</div>
</template>
<script>
import moment from 'moment'
import ModMenu from '@/components/shared/ModMenu'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'mod-menu': ModMenu
		},
    	data () {
      		return {
                titulo:"Formas de Pago",
                fpago: {
                    id:       0,
                    nombre:  "",
                    updated_at:"",
                    created_at:"",
                },

        		status: false,
                enviando: false,

                show: false,

                showMenuCli: false,
                x: 0,
                y: 0,
                items: [
                    { title: 'F. Pago', name: 'fpago.index', icon: 'list' },
                    { title: 'Nueva F. Pago', name: 'fpago.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]

      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/admin/fpagos/'+id+'/edit')
                    .then(res => {

                        this.fpago = res.data.fpago;
                        this.show = true;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'fpago.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.fpago.updated_at ? moment(this.fpago.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.fpago.created_at ? moment(this.fpago.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            showMenu (e) {

                e.preventDefault()

                this.showMenuCli = false
                this.x = e.clientX
                this.y = e.clientY

                this.$nextTick(() => {
                    this.showMenuCli = true
                })
            },
            submit() {

                //console.log("Edit user (submit):"+this.fpago.id);
                this.enviando = true;

                var url = "/admin/fpagos/"+this.fpago.id;
                var metodo = "put";
                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.fpago.nombre,

                                }
                            })
                            .then(response => {
                                this.$toast.success(response.data.message);
                                this.fpago = response.data.fpago;
                                this.enviando = false;
                            })
                            .catch(err => {

                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`
                                        })
                                    }
                                }else{
                                    this.$toast.error(err.response.data.message);
                                }
                                this.enviando = false;
                            });
                        }
                    else{
                        this.enviando = false;
                    }
                });

            },

    }
  }
</script>
