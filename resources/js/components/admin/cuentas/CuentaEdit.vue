
<template>
	<div v-show="show">
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="cuenta.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm4>
                            <v-text-field
                                v-model="cuenta.nombre"
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
                             <v-switch
                                v-model="cuenta.activa"
                                color="primary"
                                label="Activa"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm4>
                            <v-text-field
                                v-model="cuenta.iban"
                                :error-messages="errors.collect('iban')"
                                label="IBAN"
                                mask="AA## #### #### #### #### ####"
                                counter=24
                                data-vv-name="iban"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="cuenta.bic"
                                :error-messages="errors.collect('bic')"
                                label="BIC"
                                counter=11
                                data-vv-name="bic"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <br/>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="cuenta.sufijo_adeudo"
                                :error-messages="errors.collect('sufijo_adeudo')"
                                label="Sufijo adeudos SEPA"
                                data-vv-name="sufijo_adeudo"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="cuenta.sufijo_transf"
                                :error-messages="errors.collect('sufijo_transf')"
                                label="Sufijo transferencias SEPA"
                                data-vv-name="sufijo_transf"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>

                    </v-layout>
                    <br/>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <v-text-field
                                v-model="cuenta.username"
                                label="Usuario"
                                data-vv-name="username"
                                readonly
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
            'menu-ope': MenuOpe,
		},
    	data () {
      		return {
                titulo:"Cuenta-IBAN",
                cuenta: {
                    id:       0,
                    empresa_id:0,
                    nombre:  "",
                    iban:"",
                    bic:"",
                    sufijo_adeudo:"",
                    sufijo_transf:"",
                    activa: false,
                    username:"",
                    updated_at:"",
                    created_at:"",
                },

        		status: false,
                enviando: false,

                show: false,

      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/admin/cuentas/'+id+'/edit')
                    .then(res => {

                        this.cuenta = res.data.cuenta;
                        this.show = true;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'cuenta.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.cuenta.updated_at ? moment(this.cuenta.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.cuenta.created_at ? moment(this.cuenta.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            submit() {

                //console.log("Edit user (submit):"+this.cuenta.id);
                this.enviando = true;

                var url = "/admin/cuentas/"+this.cuenta.id;
                var metodo = "put";
                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.put(url, this.cuenta)
                            .then(response => {
                                this.$toast.success(response.data.message);
                                this.cuenta = response.data.cuenta;
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
