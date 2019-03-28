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
                    <v-flex sm3>
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
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.razon"
                            v-validate="'required'"
                            :error-messages="errors.collect('razon')"
                            label="Razon"
                            data-vv-name="razon"
                            required
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="cliente.cif"
                            :error-messages="errors.collect('cif')"
                            label="CIF"
                            data-vv-name="cif"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="cliente.telefono1"
                            :error-messages="errors.collect('telefono1')"
                            label="Telefono"
                            data-vv-name="telefono1"
                            data-vv-as="Teléfono"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="cliente.telefono2"
                            :error-messages="errors.collect('telefono2')"
                            label="Telefono"
                            data-vv-name="telefono2"
                            data-vv-as="Teléfono"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.direccion"
                            :error-messages="errors.collect('direccion')"
                            label="Dirección"
                            data-vv-name="direccion"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm1>
                        <v-text-field
                            v-model="cliente.cpostal"
                            :error-messages="errors.collect('cpostal')"
                            label="CP"
                            data-vv-name="CP"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.poblacion"
                            :error-messages="errors.collect('poblacion')"
                            label="Población"
                            data-vv-name="poblacion"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.provincia"
                            :error-messages="errors.collect('provincia')"
                            label="Provincia"
                            data-vv-name="provincia"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="cliente.sigla"
                            :error-messages="errors.collect('sigla')"
                            label="Sigla"
                            data-vv-name="sigla"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.titulo"
                            v-validate="'required'"
                            :error-messages="errors.collect('titulo')"
                            label="Tíitulo"
                            data-vv-name="titulo"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.contacto"
                            :error-messages="errors.collect('contacto')"
                            label="Contacto"
                            data-vv-name="contacto"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.email"
                            v-validate="'email'"
                            :error-messages="errors.collect('email')"
                            label="email"
                            data-vv-name="email"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.web"
                            :error-messages="errors.collect('web')"
                            label="Web"
                            data-vv-name="web"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.carpeta"
                            :error-messages="errors.collect('carpeta')"
                            label="Carpeta"
                            data-vv-name="carpeta"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.certificado"
                            :error-messages="errors.collect('certificado')"
                            label="Certificado"
                            data-vv-name="certificado"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.passwd_cer"
                            :error-messages="errors.collect('passwd_cer')"
                            label="Password"
                            data-vv-name="password"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                    <v-flex sm3>
                        <v-text-field
                            v-model="cliente.logo"
                            :error-messages="errors.collect('logo')"
                            label="Logo"
                            data-vv-name="logo"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>

                </v-layout>
                <v-layout row wrap>
                    <v-flex sm12>
                        <v-text-field
                            v-model="cliente.txtpie1"
                            :error-messages="errors.collect('txtpie1')"
                            label="Pie"
                            data-vv-name="txtpie1"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm12>
                        <v-text-field
                            v-model="cliente.txtpie2"
                            :error-messages="errors.collect('txtpie2')"
                            label="Pie"
                            data-vv-name="txtpie2"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>

                </v-layout>
                <v-layout row wrap>
                    <v-flex sm4 d-flex>
                        <v-select
                        v-model="cliente.carpeta_id"
                        item-text="name"
                        item-value="id"
                        :items="clientes"
                        label="Link cliente"
                        ></v-select>
                    </v-flex>
                    <v-flex sm2>
                        <v-text-field
                            v-model="cliente.username"
                            :error-messages="errors.collect('username')"
                            label="User"
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
                titulo:"Clientes",
                cliente: {
                    id:"",
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
                    tipodoc:"",
                    dni:"",
                    fechaalta:"",
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
                    factusn:"",
                    iban:"",
                    ref19:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },
                clientes:[],

                cliente_id: "",

        		status: false,
                enviando: false,

                show: false,

                showMenuCli: false,
                x: 0,
                y: 0,
                items: [
                    { title: 'clientes', name: 'cliente.index', icon: 'list' },
                    { title: 'Nueva cliente', name: 'cliente.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]


      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/clientes/'+id+'/edit')
                    .then(res => {
                        this.cliente = res.data.cliente;
                        this.show=true;
                        console.log(this.cliente);

                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'clientes.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.cliente.updated_at ? moment(this.cliente.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.cliente.updated_at ? moment(this.cliente.created_at).format('D/MM/YYYY H:mm:ss') : '';
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

                console.log(this.cliente);
                this.enviando = true;

                var url = "/admin/clientes/"+this.cliente.id;

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.put(url, this.cliente)
                            .then(response => {
                                this.$toast.success(response.data.message);
                                this.cliente = response.data.cliente;
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

    }
  }
</script>
