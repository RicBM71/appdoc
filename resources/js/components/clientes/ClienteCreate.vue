<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="cliente.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm6>
                            <v-text-field
                                v-model="cliente.nombre"
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
                        <v-flex sm1></v-flex>
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
                titulo:"Nuevo Cliente/Proveedor",
                cliente: {
                    id:0,
                    empresa_id:"",
                    razon:"",
                    nombre:"",
                    direccion:"",
                    cpostal:"",
                    poblacion:"",
                    provincia:"",
                    telefono1:"",
                    telefono2:"",
                    tfmovil:"",
                    email:"",
                    contacto:"",
                    cif:"",
                    fechabaja:"",
                    web:"",
                    carpeta_id:"",
                    patron:"",
                    notas1:"",
                    efact:"",
                    eusr:"",
                    epass:"",
                    egrupo:"",
                    fpago_id:"",
                    factura:false,
                    iban:"",
                    bic:"",
                    ref19:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                sino:[
                    {id: 0, name:"Si"}, {id: 1, name:"No"},
                ],
                clientes:[],
                carpetas:[],
                fpagos:[],

                cliente_id: "",

        		status: false,
                enviando: false,

                calfbaja:false,
                show: false,

      		}
        },
        mounted(){
            axios.get('/mto/clientes/create')
                .then(res => {
                    this.carpetas = res.data.carpetas;
                    this.fpagos = res.data.fpagos;
                    // res.data.carpetas.map((e) =>
                    //     {
                    //         this.carpetas.push({id: e.id, name: e.nombre});
                    //     })
                    // res.data.fpagos.map((e) =>
                    //     {
                    //         this.fpagos.push({id: e.id, name: e.nombre});
                    //     })
                    // this.show=true;
                })
                .catch(err => {
                    this.$toast.error(err.response.data.message);
                    this.$router.push({ name: 'cliente.index'})
                })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.cliente.updated_at ? moment(this.cliente.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.cliente.created_at ? moment(this.cliente.created_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFechaBaja() {
                moment.locale('es');
                return this.cliente.fechabaja ? moment(this.cliente.fechabaja).format('D/MM/YYYY') : '';
            }

        },
    	methods:{
            submit() {

                this.enviando = true;
                this.cliente.razon = this.cliente.nombre;
                ///this.cliente.empresa_id = 1;

                var url = "/mto/clientes";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url, this.cliente)
                            .then(response => {
                                //console.log(response);
                                this.$router.push({ name: 'cliente.edit', params: { id: response.data.cliente.id } })
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

            },
            clearDate(){
                this.user.fechabaja = null;
            },

    }
  }
</script>
