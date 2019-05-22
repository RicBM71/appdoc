<template>
	<div>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="documento.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm4 d-flex>
                            <v-select
                                v-model="documento.carpeta_id"
                                :items="carpetas"
                                label="Carpeta"
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
                                        slot="activator"
                                        :value="computedFecha"
                                        label="Fecha"
                                        append-icon="event"
                                        readonly
                                        data-vv-as="Fecha"
                                        ></v-text-field>
                                    <v-date-picker
                                        v-model="documento.fecha"
                                        no-title
                                        locale="es"
                                        first-day-of-week=1
                                        @input="menu2 = false"
                                    ></v-date-picker>
                                </v-menu>
                        </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                        <v-flex sm10>
                            <v-text-field
                                v-model="documento.concepto"
                                v-validate="'required'"
                                :error-messages="errors.collect('concepto')"
                                label="Concepto"
                                data-vv-name="concepto"
                                data-vv-as="concepto"
                                required
                                v-on:keyup.enter="submit"
                            >
                            </v-text-field>
                        </v-flex>
                        <v-flex sm2>
                            <v-text-field
                                v-model="documento.importe"
                                v-validate="'decimal:2'"
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
                        <v-flex sm2>
                            <v-text-field
                                v-model="documento.username"
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
                                        <v-btn @click="submit"  round  :loading="enviando" block  color="primary">
                                Guardar
                                </v-btn>
                            </div>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-form>
            <v-container v-show="!files.lenght > 0">
                <v-layout row wrap>
                    <v-flex sm2
                        v-for="f in files"
                        :key="f.url">
                        <v-icon @click="download(f.id)">cloud_download</v-icon> Doc.{{extensionFile(f.url)}}
                    </v-flex>
                </v-layout>
            </v-container>
            <v-container>
                <v-layout row wrap>
                        <v-flex sm12>
                            <vue-dropzone
                                ref="myVueDropzone"
                                id="dropzone"
                                :options="dropzoneOptions"
                                @vdropzone-success="upload"
                                @vdropzone-error="verror"
                            ></vue-dropzone>
                        </v-flex>
                </v-layout>
            </v-container>
        </v-card>
	</div>
</template>
<script>
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import moment from 'moment'
import MenuOpe from './MenuOpe'
import vue2Dropzone from 'vue2-dropzone'
import MyDialog from '@/components/shared/MyDialog'


	export default {
		$_veeValidate: {
      		validator: 'new'
        },
        components: {
            'my-dialog': MyDialog,
            'vueDropzone': vue2Dropzone,
            'menu-ope': MenuOpe,
		},
    	data () {
      		return {
                titulo:"Documentos",
                documento: {
                    id:0,
                    empresa_id:"",
                    carpeta_id:"",
                    concepto:"",
                    fecha:"",
                    importe: 0,
                    username: "",
                    updated_at:"",
                    created_at:"",
                },

                carpetas:[],
                files: [],

                documento_id: "",

                menu2: false,
        		status: false,
                enviando: false,
                show: true,

                dropzoneOptions: {
                    url: '/mto/filedocs/'+this.$route.params.id,
                    paramName: 'files',
                    thumbnailWidth: 150,
                    maxFiles: 10,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra aquí tus documentos para subir al servidor'
                }
      		}
        },
        mounted(){
            var id = this.$route.params.id;
            //console.log(this.$route.params);
            if (id > 0)
                axios.get('/mto/documentos/'+id+'/edit')
                    .then(res => {
                        this.documento = res.data.documento;
                        this.carpetas = res.data.carpetas;
                        this.files = res.data.files;

                        this.show=true;
                    })
                    .catch(err => {
                        if (err.response.status == 404)
                            this.$toast.error("Documento No encontrado!");
                        else
                            this.$toast.error(err.response.data.message);
                        this.$router.push({ name: 'documento.index'})
                    })
        },
        computed: {
            computedFecha() {
                moment.locale('es');
                return this.documento.fecha ? moment(this.documento.fecha).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.documento.updated_at ? moment(this.documento.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.documento.created_at ? moment(this.documento.created_at).format('D/MM/YYYY H:mm:ss') : '';
            }

        },
    	methods:{
            extensionFile(url){

                const a = url.split('.');

                return a[a.length - 1];
            },
            download(file_id){
                console.log(file_id);
            },
            submit() {

                this.enviando = true;

                var url = "/mto/documentos/"+this.documento.id;


                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.documento)
                            .then(response => {
                                // console.log(response);
                                this.$toast.success(response.data.message);
                                this.documento = response.data.documento;
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
            upload(file, response){

                this.files = response.files;
                console.log(this.files);

            },
            verror(file, err) {
                const m = err.errors.files[0];
                this.$toast.error(m);
            },
    }
  }
</script>
