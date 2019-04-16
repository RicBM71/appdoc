<template>
	<div v-show="show">
        <loading :show_loading="show_loading"></loading>
        <mod-menu :showMenuCli="showMenuCli" :x="x" :y="y" :items="items"></mod-menu>

        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
                <v-spacer></v-spacer>
                <v-btn
                    color="white"
                    icon
                    @click="printPDF"
                >
                    <v-icon>print</v-icon>
                </v-btn>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
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
                        <v-flex sm1>
                            <v-text-field
                                v-model="albaran.serie"
                                :error-messages="errors.collect('serie')"
                                label="Serie"
                                data-vv-name="serie"
                                data-vv-as="serie"
                                required
                                readonly
                                :disabled="computedFactura"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.albaran"
                                :error-messages="errors.collect('albaran')"
                                label="Albarán"
                                data-vv-name="albaran"
                                data-vv-as="albarán"
                                required
                                readonly
                                :disabled="computedFactura"
                            >
                            </v-text-field>
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
                                    :disabled="computedFactura"
                                >

                                    <v-text-field
                                        slot="activator"
                                        :value="computedFechaAlb"
                                        label="Fecha Albarán"
                                        append-icon="event"
                                        readonly
                                        data-vv-as="Fecha Albarán"
                                        :disabled="computedFactura"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="albaran.fecha_alb"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                        :disabled="computedFactura"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>

                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.factura"
                                :error-messages="errors.collect('factura')"
                                label="Factura"
                                data-vv-name="factura"
                                data-vv-as="factura"
                                :readonly="computedFactura"
                                :disabled="computedFactura"
                            >
                                <v-tooltip slot="append" bottom color="black">
                                    <v-icon v-if="albaran.ejefac==0" slot="activator" @click="facturar(albaran.id)">lock</v-icon>
                                    <v-icon v-else if="isAdmin" slot="activator" @click="desFacturar(albaran.id)">lock_open</v-icon>
                                    <span v-if="albaran.ejefac==0">Generar factura automática</span>
                                    <span v-else>Desfacturar</span>
                                </v-tooltip>
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-menu
                                    v-model="menu3"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    lazy
                                    transition="scale-transition"
                                    offset-y
                                    full-width
                                    min-width="290px"
                                    :disabled="computedFactura"
                                >

                                    <v-text-field
                                        slot="activator"
                                        :value="computedFechaFac"
                                        label="Fecha Factura"
                                        append-icon="event"
                                        readonly
                                        data-vv-as="Fecha Factura"
                                        :disabled="computedFactura"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="albaran.fecha_fac"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu3 = false"
                                        :disabled="computedFactura"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                        <v-flex sm2>
                            <v-switch
                                v-model="albaran.notificado"
                                data-vv-name="notificado"
                                data-vv-as="Notificado"
                                :label="albaran.notificado == '0' ? 'Notificar' : 'Notificado'"
                                color="primary"
                                :disabled="computedFactura"
                            ></v-switch>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm4>
                            <v-autocomplete
                                v-model="albaran.cliente_id"
                                v-validate="'required'"
                                data-vv-name="cliente_id"
                                data-vv-as="Cliente"
                                item-text="name"
                                item-value="id"
                                :error-messages="errors.collect('cliente_id')"
                                :loading="loading"
                                :items="clientes"
                                :search-input.sync="search"
                                flat
                                label="Cliente"
                                required
                                :disabled="computedFactura"
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm3 d-flex>
                            <v-autocomplete
                                v-model="albaran.fpago_id"
                                v-validate="'required'"
                                data-vv-name="fpago_id"
                                data-vv-as="F. Pago"
                                :error-messages="errors.collect('fpago_id')"
                                :items="fpagos"
                                flat
                                label="F. Pago"
                                required
                                :disabled="computedFactura"
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm3 d-flex>
                            <v-autocomplete
                                v-model="albaran.vencimiento_id"
                                v-validate="'required'"
                                data-vv-name="vencimiento_id"
                                data-vv-as="Vencimiento"
                                :error-messages="errors.collect('vencimiento_id')"
                                :items="vencimientos"
                                flat
                                label="Vencimiento"
                                required
                                :disabled="computedFactura"
                            >
                            </v-autocomplete>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="albaran.username"
                                label="Usuario"
                                readonly
                                v-on:keyup.enter="submit"
                                :disabled="computedFactura"
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm6 d-flex>
                            <v-text-field
                                    v-model="albaran.notas"
                                    label="Observaciones"
                                    :disabled="computedFactura"
                                >
                            </v-text-field>
                        </v-flex>

                        <v-flex sm2>
                            <v-text-field
                                v-model="computedFModFormat"
                                label="Modificado"
                                readonly
                                :disabled="computedFactura"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="computedFCreFormat"
                                label="Creado"
                                readonly
                                :disabled="computedFactura"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <div class="text-xs-center">
                                <v-btn @click="submit"
                                    :loading="show_loading"
                                    block
                                    color="primary"
                                    :disabled="computedFactura"
                                >
                                    Guardar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
        </v-card>
        <br/>
        <albalin v-if="albaran.id > 0" :albaran_id="albaran.id" :ejefac="albaran.ejefac"></albalin>
	</div>
</template>
<script>
import moment from 'moment'
import ModMenu from '@/components/shared/ModMenu'
import Loading from '@/components/shared/Loading'
import Albalin from './Albalin'
import {mapGetters} from 'vuex';

	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'mod-menu': ModMenu,
            'loading': Loading,
            'albalin': Albalin
		},
    	data () {
      		return {
                titulo:"Albarán",
                albaran: {
                    id:"",
                    empresa_id:"",
                    ejercicio:"",
                    albaran:"",
                    serie:"",
                    fecha_alb:"",
                    cliente_id:"",
                    ejefac:"",
                    factura:"",
                    fecha_fac:"",
                    fpago_id: "",
                    vencimiento_id:0,
                    notificado:"",
                    notas:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                ivas:[],
                retenciones:[],
                productos:[],
                fpagos:[],
                vencimientos:[],

                clientes:[],
                loading: false,
                search: null,

                search_fp: null,

                albaran_id: "",

        		status: false,

                show: true,

                menu2:false,
                facturado:true,
                menu3:false,

                show_loading: false,

                showMenuCli: false,
                x: 0,
                y: 0,
                items: [
                    { title: 'Albaranes', name: 'albaran.index', icon: 'list' },
                    { title: 'Nuevo albarán', name: 'albaran.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]


      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/ventas/albacabs/'+id+'/edit')
                    .then(res => {

                        this.clientes = res.data.clientes;
                        this.fpagos = res.data.fpagos;
                        this.vencimientos = res.data.vencimientos;

                        this.albaran = res.data.albaran;

                        this.albaran.ejefac ==  0 ? this.titulo ="Albarán" : this.titulo = "Factura";

                        this.show=true;
                    })
                    .catch(err => {
                        if (err.response.status == 404)
                            this.$toast.error("Albarán No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'albaran.index'})
                    })
        },
        computed: {
            ...mapGetters([
                'isRoot',
                'isAdmin'
		    ]),
            computedFechaAlb() {
                moment.locale('es');
                return this.albaran.fecha_alb ? moment(this.albaran.fecha_alb).format('L') : '';
            },
            computedFechaFac() {
                moment.locale('es');
                return this.albaran.fecha_fac ? moment(this.albaran.fecha_fac).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.albaran.updated_at ? moment(this.albaran.updated_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.albaran.created_at ? moment(this.albaran.created_at).format('DD/MM/YYYY H:mm:ss') : '';
            },
            computedFactura(){
                /*
                * true: bloqueado, false: desbloqueado
                */
                if(this.albaran.ejefac != 0)
                    return true;

                return false;
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

                this.show_loading = true;

                var url = "/ventas/albacabs/"+this.albaran.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.albaran)
                            .then(response => {

                                this.$toast.success(response.data.message);
                                this.albaran = response.data.albaran;
                                this.show_loading = false;
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
                                    console.log(err.response.data);
                                    this.$toast.error(err.response.data.message);
                                }
                                this.show_loading = false;
                            });
                        }
                    else{
                        this.show_loading = false;
                    }
                });

            },
            facturar(id){

                this.show_loading = true;

                axios.put('/ventas/albacabs/'+id+'/facturar', this.albaran)
                .then(response => {
                    this.$toast.success(response.data.message);

                    this.albaran = response.data.albaran;
                    this.albaran.ejefac ==  0 ? this.titulo ="Albarán" : this.titulo = "Factura";

                    this.show_loading = false;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.show_loading = false;
                });
            },
            desFacturar(id){

                this.show_loading = true;

                axios.put('/ventas/albacabs/'+id+'/desfacturar', this.albaran)
                .then(response => {

                    this.$toast.success(response.data.message);

                    this.albaran = response.data.albaran;
                    this.albaran.ejefac ==  0 ? this.titulo ="Albarán" : this.titulo = "Factura";

                    this.show_loading = false;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.show_loading = false;
                });

            },
            printPDF(){
                var id = 1;
                var url = '/ventas/albacabs/'+id+'/print';

                window.open(url, '_blank');
                //window.location = url;
                return;

                axios.get('/ventas/albacabs/'+id+'/print')
                    .then(res => {
                        console.log(res);
                    })
                    .catch(err => {
                        console.log(err.response);
                    })
                console.log("print");
            }

    }
  }
</script>

<style scope>
.v-text-field {
    padding-top: 2px;
    margin-top: 4px;
}
.theme--light.v-input--is-disabled,  .theme--light.v-input--is-disabled input, .theme--light.v-input--is-disabled textarea {
    color: #263238;
}

.v-form>.container>.layout>.flex{
    padding: 0px 8px;
}

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
