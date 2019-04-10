<template>
	<div v-show="show">
        <mod-menu :showMenuCli="showMenuCli" :x="x" :y="y" :items="items"></mod-menu>
        <v-card>
            <v-card-title>
                <h2>{{titulo}}</h2>
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
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.nombre"
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
                                v-model="empresa.razon"
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
                                v-model="empresa.cif"
                                :error-messages="errors.collect('cif')"
                                label="CIF"
                                data-vv-name="cif"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="empresa.telefono1"
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
                                v-model="empresa.telefono2"
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
                                v-model="empresa.direccion"
                                :error-messages="errors.collect('direccion')"
                                label="Dirección"
                                data-vv-name="direccion"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm1>
                            <v-text-field
                                v-model="empresa.cpostal"
                                :error-messages="errors.collect('cpostal')"
                                label="CP"
                                data-vv-name="CP"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.poblacion"
                                :error-messages="errors.collect('poblacion')"
                                label="Población"
                                data-vv-name="poblacion"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.provincia"
                                :error-messages="errors.collect('provincia')"
                                label="Provincia"
                                data-vv-name="provincia"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="empresa.sigla"
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
                                v-model="empresa.titulo"
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
                                v-model="empresa.contacto"
                                :error-messages="errors.collect('contacto')"
                                label="Contacto"
                                data-vv-name="contacto"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.email"
                                :error-messages="errors.collect('email')"
                                label="email"
                                data-vv-name="email"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.web"
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
                                v-model="empresa.carpeta"
                                :error-messages="errors.collect('carpeta')"
                                label="Carpeta"
                                data-vv-name="carpeta"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.certificado"
                                :error-messages="errors.collect('certificado')"
                                label="Certificado"
                                data-vv-name="certificado"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.passwd_cer"
                                :error-messages="errors.collect('passwd_cer')"
                                label="Password"
                                data-vv-name="password"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="empresa.logo"
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
                                v-model="empresa.txtpie1"
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
                                v-model="empresa.txtpie2"
                                :error-messages="errors.collect('txtpie2')"
                                label="Pie"
                                data-vv-name="txtpie2"
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>

                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm3 d-flex>
                            <v-select
                            :items="items"
                            label="Standard"
                            ></v-select>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="empresa.username"
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
        </v-card>
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
                titulo:"Empresas",
                empresa: {
                    id: 0,
                    nombre: "",
                    razon: "",
                    cif: "",
                    poblacion: "",
                    direccion: "",
                    cpostal: "",
                    provincia: "",
                    telefono1: "",
                    telefono2: "",
                    contacto: "",
                    email: "",
                    web: "",
                    txtpie1: "",
                    txtpie2: "",
                    flags: "",
                    sigla: "",
                    carpeta: "",
                    titulo: "",
                    logo:"",
                    certificado:"",
                    passwd_cer:"",
                    carpeta_id:"",
                    username: "",
                    updated_at:"",
                    created_at:"",
                },
                empresa_id: "",

        		status: false,
                enviando: false,

                show: false,
                showMenuCli: false,

                x: 0,
                y: 0,
               items: [
                    { title: 'Empresas', name: 'empresa.index', icon: 'list' },
                    { title: 'Nueva Empresa', name: 'empresa.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]
      		}
        },
        mounted(){
            axios.get('/admin/empresas/create')
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
                return this.empresa.updated_at ? moment(this.empresa.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.empresa.created_at ? moment(this.empresa.created_at).format('D/MM/YYYY H:mm:ss') : '';
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

                //console.log("Edit user (submit):"+this.empresa.id);
                this.enviando = true;

                var url = "/admin/empresas";

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url, this.empresa)
                            .then(response => {
                                //console.log(response.data);
                                this.$toast.success(response.data.message);

                                this.enviando = false;
                                this.$router.push({ name: 'empresa.edit', params: { id: response.data.empresa.id } })
                            })
                            .catch(err => {
                                console.log(err.request);
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
