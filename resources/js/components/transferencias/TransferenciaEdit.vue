<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope
                    :id="transferencia.id"
                    :enviada="computedEnviada"
                    @set-bloqueo="setBloqueo">
                </menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap v-if="transferencia.iban_cargo > ''">
                        <v-flex sm4>
                                <v-text-field
                                    :disabled="computedEnviada"
                                    v-model="transferencia.iban_cargo"
                                    label="Cuenta Ordenante"
                                    mask="AA## #### #### #### #### ####"
                                    readonly
                                >
                                </v-text-field>
                            </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm4 d-flex>
                            <v-select
                                v-model="transferencia.cliente_id"
                                :items="clientes"
                                v-validate="'required'"
                                data-vv-name="cliente_id"
                                data-vv-as="Cliente"
                                :error-messages="errors.collect('cliente_id')"
                                label="Beneficiario"
                            ></v-select>
                        </v-flex>
                         <v-flex sm2>
                            <v-menu
                                    v-model="menu2"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                >

                                    <v-text-field
                                        :disabled="computedEnviada"
                                        slot="activator"
                                        :value="computedFecha"
                                        label="Fecha"
                                        append-icon="event"
                                        readonly
                                        class="center"
                                        data-vv-as="Fecha"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="transferencia.fecha"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                        <v-flex sm4>
                            <v-text-field
                                :disabled="computedEnviada"
                                v-model="transferencia.iban_abono"
                                :error-messages="errors.collect('iban')"
                                label="IBAN"
                                mask="AA## #### #### #### #### ####"
                                data-vv-name="iban"
                                readonly
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                :disabled="computedEnviada"
                                v-model="transferencia.enviada"
                                color="primary"
                                label="Procesada"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm8>
                            <v-text-field
                                :disabled="computedEnviada"
                                v-model="transferencia.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto"
                                data-vv-name="concepto"
                                data-vv-as="Concepto"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                             <v-currency-field
                                :disabled="computedEnviada"
                                v-model="transferencia.importe"
                                v-validate="'required|decimal:2'"
                                :error-messages="errors.collect('importe')"
                                label="Importe"
                                data-vv-name="importe"
                                data-vv-as="Importe"
                                v-bind="currency_config"
                                v-on:keyup.enter="submit"
                                class="inputPrice"
                            >
                             </v-currency-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm2>
                            <v-text-field
                                v-model="transferencia.username"
                                :error-messages="errors.collect('username')"
                                label="Usuario"
                                data-vv-name="username"
                                readonly
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
                        <v-flex sm3></v-flex>
                        <v-flex sm2>
                            <div class="text-xs-center">
                                <v-btn @click="submit"  round :disabled="computedEnviada"  :loading="enviando" block  color="primary">
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
import MyDialog from '@/components/shared/MyDialog'
import Loading from '@/components/shared/Loading'
import {mapGetters} from 'vuex';

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'loading': Loading,
            'my-dialog': MyDialog,
            'menu-ope': MenuOpe,
		},
    	data () {
      		return {
                titulo:"Transferencias",
                transferencia: {
                    id:0,
                    empresa_id:"",
                    cliente_id:"",
                    concepto:"",
                    fecha: "",
                    enviada: 0,
                    iban_cargo: "",
                    bic_cargo: "",
                    iban_abono: "",
                    bic_abono: "",
                    importe: "",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                clientes:[],

                transferencia_id: "",

                menu2: false,
        		status: false,
                enviando: false,
                show: true,
                show_upload: false,
                show_loading: true,

                currency_config: {
                    decimal: ',',
                    thousands: '.',
                    suffix: ' €',
                    precision: 2,

                    allowBlank: false,
                    }
      		}
        },
        mounted(){

            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/transferencias/'+id+'/edit')
                    .then(res => {

                        this.transferencia = res.data.transferencia;
                       // console.log(this.transferencia);

                        this.clientes = res.data.clientes;

                        this.show=true;
                        this.show_loading = false;
                    })
                    .catch(err => {

                        if (err.response.status == 404)
                            this.$toast.error("Documento No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'transferencia.index'})
                    })
        },
        computed: {
            ...mapGetters([
                'isAdmin'
            ]),
            computedEnviada(){
                return this.transferencia.enviada ? true : false;
            },
            computedFecha() {
                moment.locale('es');
                return this.transferencia.fecha ? moment(this.transferencia.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.transferencia.updated_at ? moment(this.transferencia.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.transferencia.created_at ? moment(this.transferencia.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }
        },
    	methods:{
            setBloqueo(e){
                this.transferencia.enviada = e;
                this.submit();
            },
            formatDate(f){
                if (f == null) return null;
                    moment.locale('es');
                return moment(f).format('DD/MM/YYYY');
            },
            submit() {

                this.enviando = true;

                var url = "/mto/transferencias/"+this.transferencia.id;


                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.transferencia)
                            .then(response => {
                                // console.log(response);
                                this.$toast.success(response.data.message);
                                this.transferencia = response.data.transferencia;
                                this.enviando = false;
                            })
                            .catch(err => {
                                //console.log(err.response.data.errors);
                                if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    for (const prop in msg_valid) {
                                        // this.$toast.error(`${msg_valid[prop]}`);
                                        //console.log(prop);
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

            }
    }
  }
</script>

<style scoped>


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
