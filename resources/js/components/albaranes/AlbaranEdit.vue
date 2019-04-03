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
                    <v-flex sm1>
                        <v-text-field
                            v-model="albaran.serie"
                            v-validate="'required'"
                            :error-messages="errors.collect('serie')"
                            label="Serie"
                            data-vv-name="serie"
                            data-vv-as="serie"
                            required
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="albaran.albaran"
                            v-validate="'required|numeric'"
                            :error-messages="errors.collect('albaran')"
                            label="Albarán"
                            data-vv-name="albaran"
                            data-vv-as="albarán"
                            required
                            type="number"
                            v-on:keyup.enter="submit"
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
                            >

                                <v-text-field
                                    slot="activator"
                                    :value="computedFechaAlb"
                                    label="Fecha Albarán"
                                    append-icon="event"
                                    readonly
                                    data-vv-as="Fecha Albarán"
                                    ></v-text-field>
                                <v-date-picker
                                    v-model="albaran.fecha_alb"
                                    no-title
                                    locale="es"
                                    first-day-of-week=1
                                    @input="menu2 = false"

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
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="computedFechaFac"
                            :error-messages="errors.collect('fecha_fac')"
                            label="F. Factura"
                            data-vv-name="fecha_fac"
                            data-vv-as="F. factura"
                            readonly
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-switch
                            v-model="albaran.notficado"
                            data-vv-name="notificado"
                            data-vv-as="Notificado"
                            label="Notificado"
                            color="primary"
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
                            ></v-autocomplete>
                    </v-flex>
                    <v-flex sm3 d-flex>
                        <v-select
                            v-model="albaran.fpago_id"
                            :error-messages="errors.collect('fpago_id')"
                            v-validate="'required'"
                            data-vv-name="fpago_id"
                            data-vv-as="Forma de pago"
                            item-text="name"
                            item-value="id"
                            :items="fpagos"
                            label="Forma de Pago"
                        ></v-select>
                    </v-flex>
                    <v-flex sm3 d-flex>
                        <v-select
                            v-model="albaran.vencimiento_id"
                            :error-messages="errors.collect('vencimiento_id')"
                            v-validate="'required'"
                            data-vv-name="vencimiento_id"
                            data-vv-as="Vencimiento"
                            item-text="name"
                            item-value="id"
                            :items="fpagos"
                            label="Vencimiento"
                        ></v-select>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="albaran.username"
                            label="Usuario"
                            readonly
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm6 d-flex>
                        <v-text-field
                                v-model="albaran.notas"
                                label="Observaciones"
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
                titulo:"Albaranes",
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

                kk:1,
                ivas:[],
                retenciones:[],
                productos:[],
                fpagos:[],

                clientes:[],
                loading: false,
                search: null,


                albaran_id: "",

        		status: false,
                enviando: false,
                show: true,

                menu2:false,

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
                        console.log(res);

                        res.data.ivas.map((e) =>
                            {
                                this.ivas.push({id: e.id, name: e.nombre});
                            })
                        res.data.retenciones.map((e) =>
                            {
                                this.retenciones.push({id: e.id, name: e.nombre});
                            })
                        this.clientes = res.data.clientes;

                        this.fpagos = res.data.fpagos;

                        this.albaran = res.data.albaran;

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

                this.enviando = true;

                var url = "/ventas/albacabs/"+this.albaran.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.albaran)
                            .then(response => {
                                 console.log(response);
                                this.$toast.success(response.data.message);
                                this.albaran = response.data.albaran;
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
.v-text-field {
    padding-top: 2px;
    margin-top: 4px;
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
