<template>
	<div v-show="!show_loading">
        <loading :show_loading="show_loading"></loading>
        <my-dialog :dialog.sync="dialog" registro="avatar" @destroyReg="destroyReg"></my-dialog>
        <v-card>
            <v-card-title color="indigo">
                <h2 color="indigo">{{titulo}}</h2>
                <v-spacer></v-spacer>
                <menu-ope :id="user.id"></menu-ope>
            </v-card-title>
        </v-card>
        <v-card>
            <v-tabs fixed-tabs>
                <v-tab>
                        Datos generales
                </v-tab>
                <v-tab>
                        Roles y Permisos
                </v-tab>
                <v-tab-item>

                    <v-form>
                        <v-container>
                            <v-layout row wrap>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="user.name"
                                        v-validate="'required'"
                                        :error-messages="errors.collect('name')"
                                        label="Nombre"
                                        data-vv-name="name"
                                        data-vv-as="nombre"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="user.username"
                                        v-validate="'required|min:6'"
                                        :counter="20"
                                        :error-messages="errors.collect('username')"
                                        label="Usuario"
                                        data-vv-name="username"
                                        data-vv-as="usuario"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="user.email"
                                        v-validate="'required|email'"
                                        :error-messages="errors.collect('email')"
                                        label="email"
                                        data-vv-name="email"
                                        v-on:keyup.enter="submit"
                                        required
                                    >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="user.password"
                                        ref="password"
                                        :append-icon="show ? 'visibility_off' : 'visibility'"
                                        :type="show ? 'text' : 'password'"
                                        :error-messages="errors.collect('password')"
                                        v-validate="'min:6'"
                                        label="password"
                                        hint="Indicar password solo si va a modificarla"
                                        data-vv-name="password"
                                        v-on:keyup.enter="submit"
                                        @click:append="show = !show"
                                        >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="user.password_confirmation"
                                        v-validate="'min:6|confirmed:password'"
                                        :append-icon="show ? 'visibility_off' : 'visibility'"
                                        :type="show ? 'text' : 'password'"
                                        :error-messages="errors.collect('password_confirmation')"
                                        label="confirmar password"
                                        hint="Confirmar password solo si va a modificarla"
                                        data-vv-name="password_confirmation"
                                        data-vv-as="password"
                                        v-on:keyup.enter="submit"
                                        @click:append="show = !show"
                                        >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm4>
                                    <v-text-field
                                        v-model="user.lastname"
                                        :error-messages="errors.collect('lastname')"
                                        label="Apellidos"
                                        data-vv-name="lastname"
                                        data-vv-as="apellidos"
                                        v-on:keyup.enter="submit"
                                    >
                                    </v-text-field>
                                    <v-switch
                                        v-model="user.blocked"
                                        :disabled="computedId"
                                        color="primary"
                                        label="Bloqueado"
                                    ></v-switch>
                                    <v-menu v-if="user.blocked"
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
                                            :value="computedDateFormat"
                                            clearable
                                            label="Fecha Bloqueo"
                                            prepend-icon="event"
                                            readonly
                                            data-vv-as="F. bloqueo"
                                            @click:clear="clearDate"
                                            ></v-text-field>
                                        <v-date-picker
                                            v-model="user.blocked_at"
                                            no-title
                                            locale="es"
                                            first-day-of-week=1
                                            @input="menu2 = false"

                                        ></v-date-picker>
                                    </v-menu>
                                    <v-text-field v-if="!user.blocked"
                                        v-model="computedLoginAt"
                                        label="Ult. Login"
                                        readonly
                                    >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="computedFModFormat"
                                        label="Modificado"
                                        readonly
                                    >
                                    </v-text-field>
                                    <v-text-field
                                        v-model="computedFCreFormat"
                                        label="Creado"
                                        readonly
                                    >
                                    </v-text-field>
                                </v-flex>
                                <v-flex sm1></v-flex>
                                <v-flex sm2 v-if="showPer">
                                    <div v-if="user.avatar == '#'">
                                        <vue-dropzone
                                            ref="myVueDropzone"
                                            id="dropzone"
                                            :options="dropzoneOptions"
                                            v-on:vdropzone-success="upload"
                                        ></vue-dropzone>
                                    </div>
                                    <div v-else>
                                        <v-avatar>
                                            <img class="img-fluid" :src="user.avatar" alt="avatar">
                                        </v-avatar>
                                        <v-btn @click="openDialog" flat icon color="red darken-4">
                                            <v-icon>delete</v-icon>
                                        </v-btn>
                                    </div>
                                </v-flex>

                            </v-layout>
                            <v-layout>
                                <v-flex sm4></v-flex>
                                <v-flex sm4>
                                    <div class="text-xs-center">
                                        <v-btn @click="submit"  round  :loading="enviando" block  color="primary">
                                            Guardar Usuario
                                        </v-btn>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-form>

                </v-tab-item>
                <v-tab-item>
                    <v-container>
                        <v-layout row wrap text-xs-center>
                            <v-flex sm12><h2>{{user.username}}</h2></v-flex>
                        </v-layout>
                        <v-layout row wrap>
                            <v-flex sm12>
                                <div v-if="showPer">
                                    <user-emp :user_id="this.user.id" :emp_user="this.emp_user"></user-emp>
                                    <user-role :user_id="this.user.id" :role_user="this.role_user"></user-role>
                                    <user-permiso :user_id="this.user.id" :permisos="this.permisos" :permisos_selected="permisos_selected"></user-permiso>
                                </div>
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-tab-item>
            </v-tabs>
            </v-card>
	</div>
</template>
<script>
    import moment from 'moment'
    import MenuOpe from './MenuOpe'
    import UserEmp from './UserEmp'
    import {mapGetters} from 'vuex';
    import UserRole from './UserRole'
    import UserPermiso from './UserPermiso'
    import vue2Dropzone from 'vue2-dropzone'
    import MyDialog from '@/components/shared/MyDialog'
    import Loading from '@/components/shared/Loading'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

	export default {
		$_veeValidate: {
      		validator: 'new'
    	},
        components: {
            'user-emp': UserEmp,
            'user-role': UserRole,
            'user-permiso': UserPermiso,
            'my-dialog': MyDialog,
            'vueDropzone': vue2Dropzone,
            'menu-ope': MenuOpe,
            'loading': Loading

		},
    	data () {
      		return {
                user: {
                    id:       0,
                    name:     "",
                    lastname: "",
                    username: "",
                    email:    "",
                    avatar:   "",
                    blocked_at:"",
                    blocked:  "",
                    login_at: "",
                    empresa_id:"",
                    password: "",
                    password_confirmation:"",

                    // updated_at:"",
                    // created_at:"",
                },
                titulo:   "Usuarios",

                emp_user:[],
                role_user: [],
                permisos:[],
                permisos_selected:[],

        		status: false,
        		msg : "",
                color: "error",
                icon: "warning",
                enviando: false,

                show_loading: true,
                show: false,
                menu2: false,

                showPer: false,
                dialog: false,

                showDrop: false,
                dropzoneOptions: {
                    url: '/admin/users/'+this.$route.params.id+'/avatar',
                    paramName: 'avatar',
                    acceptedFiles: 'image/*',
                    thumbnailWidth: 150,
                    maxFiles: 1,
		    	    resizeWidth: 80,
		    	    resizeHeight: 80,
                    maxFilesize: 0.5,
                    headers: {
		    		    'X-CSRF-TOKEN':  window.axios.defaults.headers.common['X-CSRF-TOKEN']
                    },
                    dictDefaultMessage: 'Arrastra la foto aquí para subir avatar'
                }
      		}
        },
        mounted(){

            if (!this.isRoot)
                this.items.splice(2,1)

            var id = this.$route.params.id;

            if (id > 0){
                var id = this.$route.params.id;
                axios.get('/admin/users/'+id+'/edit')
                    .then(res => {
                       // console.log(res.data);


                        this.user = res.data.user;
                        this.role_user = res.data.role_user;
                        this.permisos = res.data.permisos;
                        this.permisos_selected = res.data.permisos_user;

                        this.emp_user = res.data.emp_user;

                        this.showPer=true;
                        this.show_loading = false;

                    })
                    .catch(err => {

                        if (err.response.status != 401){
                           this.$toast.error(err.response.data.message);
                          //  this.$router.push({ name: 'users'});
                        }

                    })
            }
        },
        computed: {
            ...mapGetters([
	    		'isRoot'
    		]),
            computedDateFormat() {
                moment.locale('es');
                return this.user.blocked_at ? moment(this.user.blocked_at).format('L') : '';
            },
            computedFModFormat() {
                moment.locale('es');
                return this.user.updated_at ? moment(this.user.updated_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedFCreFormat() {
                moment.locale('es');
                return this.user.created_at ? moment(this.user.created_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedLoginAt() {
                moment.locale('es');
                return this.user.login_at ? moment(this.user.login_at).format('D/MM/YYYY H:mm:ss') : '';
            },
            computedId(){
                if (this.user.id == 1) return true; else return false;
            }

        },
    	methods:{
            submit() {

                //console.log("Edit user (submit):"+this.user.id);
                this.enviando = true;

                var url = '/admin/users/'+this.user.id;

                this.$validator.validateAll().then((result) => {
                    if (result){

                        axios.put(url, this.user)
                            .then(response => {

                                //console.log(response.data);
                                this.$toast.success(response.data.msg);
                                if (response.data.status == "C"){
                                     this.$router.push({ name: 'users_edit', params: { id: response.data.user.id } })
                                }
                                else{
                                    this.user.blocked_at = response.data.user.blocked_at;
                                    this.user.updated_at = response.data.user.updated_at;
                                    this.password = this.password_confirmation = "";
                                }
                                this.enviando = false;
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
                                this.enviando = false;
                            });
                        }
                    else{
                        this.enviando = false;
                    }
                });

            },
            clearDate(){
                this.user.blocked_at = null;
            },
            openDialog(){

                //this.$emit('update:dialog', true)
                this.dialog = true;
            },
            destroyReg(){
                 axios({
                    method: 'delete',
                    url: '/admin/avatars/'+this.user.id+'/delete',
                    })
                    .then(response => {
                        this.user.avatar = "#";
                        this.$toast.success(response.data.msg);
                    })
                    .catch(err => {
                        this.$toast.error(err.response.data);
                    });
            },
            upload(file, response){
                this.user.avatar = response.url;
            }

    }
  }
</script>
