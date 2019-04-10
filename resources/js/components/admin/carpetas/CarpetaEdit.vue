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
                        <v-flex sm1></v-flex>
                        <v-flex sm3>
                            <v-text-field
                                v-model="carpeta.nombre"
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
                                v-model="carpeta.color"
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
                titulo:"Carpetas",
                carpeta: {
                    id:       0,
                    empresa: 0,
                    nombre:  "",
                    color: "",
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
                    { title: 'Carpetas', name: 'carpeta.index', icon: 'list' },
                    { title: 'Nueva Carpeta', name: 'carpeta.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]

      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/admin/carpetas/'+id+'/edit')
                    .then(res => {

                        this.carpeta = res.data.carpeta;
                        this.show = true;
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'carpeta.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.carpeta.updated_at ? moment(this.carpeta.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.carpeta.created_at ? moment(this.carpeta.created_at).format('D/MM/YYYY H:mm:ss') : '';
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

                //console.log("Edit user (submit):"+this.carpeta.id);
                this.enviando = true;

                var url = "/admin/carpetas/"+this.carpeta.id;
                var metodo = "put";
                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios({
                            method: metodo,
                            url: url,
                            data:
                                {
                                    nombre: this.carpeta.nombre,
                                    empresa: this.carpeta.empresa,
                                    color: this.carpeta.color,

                                }
                            })
                            .then(response => {
                                this.$toast.success(response.data.message);
                                this.carpeta = response.data.carpeta;
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
