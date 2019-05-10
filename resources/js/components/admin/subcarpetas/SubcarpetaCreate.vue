<template>
	<div v-show="show">

        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="subcarpeta.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3 d-flex>
                            <v-autocomplete
                                v-model="subcarpeta.carpeta_id"
                                v-validate="'required'"
                                data-vv-name="carpeta_id"
                                data-vv-as="carpeta"
                                :error-messages="errors.collect('carpeta_id')"
                                :items="carpetas"
                                flat
                                label="Carpeta"
                                required
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="subcarpeta.nombre"
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
                                v-model="subcarpeta.color"
                                v-validate="'required'"
                                :error-messages="errors.collect('color')"
                                label="Color"
                                data-vv-name="color"
                                data-vv-as="Color"
                                required
                                v-on:keyup.enter="submit"

                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
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
                                        <v-btn @click="submit"  round  :loading="enviando" block  color="primary">
                                Guardar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
	</div>
</template>
<script>
import moment from 'moment'
import MenuOpe from './MenuOpe'

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'menu-ope': MenuOpe
		},
    	data () {
      		return {
                titulo:"Subcarpetas",
                subcarpeta: {
                    id:       0,
                    empresa_id: 0,
                    carpeta_id:"",
                    nombre:  "",
                    color: "",
                    updated_at:"",
                    created_at:"",
                },
                subcarpeta_id: "",
                carpetas: [],

        		status: false,
                enviando: false,

                show: false,
      		}
        },
        mounted(){
            axios.get('/admin/subcarpetas/create')
                .then(res => {
                    this.show = true;
                    this.carpetas = res.data.carpetas;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'subcarpeta.index'})
                })
        },

        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.subcarpeta.updated_at ? moment(this.subcarpeta.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.subcarpeta.created_at ? moment(this.subcarpeta.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;

                var url = "/admin/subcarpetas";
                var metodo = "post";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.subcarpeta.nombre,
                                    carpeta_id: this.subcarpeta.carpeta_id,
                                    color: this.subcarpeta.color,

                                }
                            })
                            .then(response => {
                                //console.log(response.data);
                                this.$toast.success(response.data.message);

                                this.enviando = false;
                                this.$router.push({ name: 'subcarpeta.edit', params: { id: response.data.subcarpeta.id } })
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
