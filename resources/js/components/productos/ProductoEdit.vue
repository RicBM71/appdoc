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
                    <v-flex sm4>
                        <v-text-field
                            v-model="producto.nombre"
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
                            v-model="producto.importe"
                            v-validate="'required|decimal:2'"
                            :error-messages="errors.collect('importe')"
                            label="Importe"
                            data-vv-name="importe"
                            data-vv-as="Valor"
                            required
                            class="inputPrice"
                            type="number"
                            v-on:keyup.enter="submit"
                        >
                        </v-text-field>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm4 d-flex>
                        <v-select
                        v-model="producto.retencion_id"
                        :error-messages="errors.collect('retencion_id')"
                        v-validate="'required'"
                        data-vv-name="retencion_id"
                        item-text="name"
                        item-value="id"
                        :items="retenciones"
                        label="IRPF"
                        ></v-select>
                    </v-flex>
                    <v-flex sm4 d-flex>
                        <v-select
                        v-model="producto.iva_id"
                        :error-messages="errors.collect('iva_id')"
                        v-validate="'required'"
                        data-vv-name="iva_id"
                        item-text="name"
                        item-value="id"
                        :items="ivas"
                        label="IVA"
                        ></v-select>
                    </v-flex>
                </v-layout>
                <v-layout row wrap>
                    <v-flex sm2>
                        <v-text-field
                            v-model="producto.username"
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
                    <v-flex sm3></v-flex>
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
                titulo:"Productos",
                producto: {
                    id:"",
                    empresa_id:"",
                    nombre:"",
                    iva_id:"",
                    retencion_id:"",
                    importe: 0,
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                ivas:[],
                retenciones:[],
                productos:[],

                producto_id: "",

        		status: false,
                enviando: false,
                show: true,

                showMenuCli: false,
                x: 0,
                y: 0,
                items: [
                    { title: 'Productos', name: 'producto.index', icon: 'list' },
                    { title: 'Nuevo producto', name: 'producto.create', icon: 'add' },
                    { title: 'Home', name: 'dash', icon: 'home' },

                ]


      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/productos/'+id+'/edit')
                    .then(res => {
                        this.producto = res.data.producto;
                        res.data.ivas.map((e) =>
                            {
                                this.ivas.push({id: e.id, name: e.nombre});
                            })
                        res.data.retenciones.map((e) =>
                            {
                                this.retenciones.push({id: e.id, name: e.nombre});
                            })
                        this.show=true;
                    })
                    .catch(err => {
                        if (err.response.status == 404)
                            this.$toast.error("producto No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'producto.index'})
                    })
        },
        computed: {
            computedFModFormat() {
                moment.locale('es');
                return this.producto.updated_at ? moment(this.producto.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.producto.created_at ? moment(this.producto.created_at).format('D/MM/YYYY H:mm:ss') : '';
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

                var url = "/mto/productos/"+this.producto.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.producto)
                            .then(response => {
                                this.$toast.success(response.data.message);
                                this.producto = response.data.producto;
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
