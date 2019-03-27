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
                    <v-flex sm3>
                        <v-text-field
                            v-model="iva.nombre"
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
                    <v-flex sm2>
                        <v-text-field
                            v-model="iva.importe"
                            v-validate="'required|decimal:2'"
                            :error-messages="errors.collect('importe')"
                            label="Valor"
                            data-vv-name="importe"
                            data-vv-as="Valor"
                            required
                            class="inputPrice"
                            type="number"
                            v-on:keyup.enter="submit"

                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="computedFModFormat"
                            label="Modificado"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
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
                titulo:"Tipos de IVA",
                iva: {
                    id:       0,
                    nombre:  "",
                    importe: "",
                    updated_at:"",
                    created_at:"",
                },
                iva_id: "",

        		status: false,
                enviando: false,

                show: false,
                showMenuCli: false,

                x: 0,
                y: 0,
                items: [
                    { title: 'Tipos Iva', name: 'iva.index', icon: 'list' },
                    { title: 'Nuevo tipo', name: 'ret.create', icon: 'add' },
                    { title: 'Retenciones', name: 'ret.index', icon: 'functions' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]


      		}
        },
        mounted(){
            axios.get('/admin/ivas/create')
                .then(res => {
                    this.show = true;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'iva.index'})
                })
        },

        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.iva.updated_at ? moment(this.iva.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.iva.updated_at ? moment(this.iva.created_at).format('D/MM/YYYY H:mm:ss') : '';
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

                //console.log("Edit user (submit):"+this.iva.id);
                this.enviando = true;

                var url = "/admin/ivas";
                var metodo = "post";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.iva.nombre,
                                    importe: this.iva.importe,

                                }
                            })
                            .then(response => {
                                //console.log(response.data);
                                this.$toast.success(response.data.message);

                                this.enviando = false;
                                this.$router.push({ name: 'iva.edit', params: { id: response.data.iva.id } })
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
<style>
.inputPrice >>> input {
  text-align: center;
  -moz-appearance:textfield;
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0;
}
</style>
