<template>
	<div>
        <loading :show_loading="show_loading"></loading>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <!-- <menu-ope :id="user.id"></menu-ope> -->
            </v-card-title>
        </v-card>
        <v-card>
            <v-form>
                <v-container>
                    <v-layout row wrap>
                        <v-flex sm3>
                            <vue-dropzone v-show="mostrar"
                                ref="myVueDropzone"
                                id="dropzone"
                                :options="dropzoneOptions"
                                v-on:vdropzone-success="upload"
                            ></vue-dropzone>
                        </v-flex>
                        <v-flex sm3 v-show="!mostrar">
                            <v-text-field
                                v-model="destino"
                                label="Destino"
                                required
                            >
                            </v-text-field>
                        </v-flex>
                    </v-layout>
                    <v-layout>
                        <v-flex sm4></v-flex>
                        <v-flex sm4>
                            <div class="text-xs-center">
                                <v-btn @click="submit"  round :disabled="computedDisabled" :loading="show_loading" block  color="primary">
                                    Importar fichero
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
    import {mapGetters} from 'vuex';
    import Loading from '@/components/shared/Loading'
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	export default {
		$_veeValidate: {
      		validator: 'new'
    	},
        components: {
            'loading': Loading,
            'vueDropzone': vue2Dropzone,
		},
    	data () {
      		return {
                mostrar: true,
                titulo:   "Importar Norma 43",
                destino: "",
                disabled: true,
                show_loading: false,
                dropzoneOptions: {
                    url: '/mto/extractos/importar',
                    paramName: 'extracto',
                    acceptedFiles: '.n43',
                    maxFiles: 1,
                    maxFilesize: 0.5,
                    createImageThumbnails: false,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra aquí el documento para subirlo'
                }
              }

        },
        mounted(){

            if (!this.isAdmin){
                this.$toast.error('No tiene permiso de administrador');
            }
        },
        computed: {
            ...mapGetters([
	    		'isAdmin'
            ]),
            computedDisabled(){
                if (this.destino != '')
                    return false;
                return true;
            }
        },
    	methods:{
            upload(file, response){
                this.mostrar = false;
               // console.log(file);
                console.log(response);
            },
            submit() {

                //console.log("Edit user (submit):"+this.user.id);
                this.show_loading = true;

                var url = '/ventas/albacabs/remesar';

                this.$validator.validateAll().then((result) => {
                    if (result){
                        axios.post(url,
                            {
                                fecha: this.fecha,
                                cuenta_id: this.cuenta_id
                            })
                            .then(response => {

                                this.$toast.success("Se ha generado correctamente la remesa");
                                this.show_loading = false;
                            })
                            .catch(err => {
                                //console.log(err);
                                //if (err.request.status == 422){ // fallo de validated.
                                    const msg_valid = err.response.data.errors;
                                    //console.log(`obj.${prop} = ${msg_valid[prop]}`);
                                    for (const prop in msg_valid) {
                                        this.errors.add({
                                            field: prop,
                                            msg: `${msg_valid[prop]}`
                                        })
                                    }
                                //}
                                this.show_loading = false;
                            });
                        }
                    else{
                        this.show_loading = false;
                    }
                });

            }
    }
  }
</script>
