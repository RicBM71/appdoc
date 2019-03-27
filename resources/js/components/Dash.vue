<template>
    <v-app>
        <div v-if="isLoggedIn">
        <v-navigation-drawer
            v-if="isLoggedIn"
            v-model="drawer"
            :clipped="$vuetify.breakpoint.lgAndUp"
            fixed
            app
            >
            <v-list dense>
                <template v-for="item in items">
                <v-layout
                    v-if="item.heading"
                    :key="item.heading"
                    row
                    align-center
                >
                    <v-flex xs6>
                    <v-subheader v-if="item.heading">
                        {{ item.heading }}
                    </v-subheader>
                    </v-flex>
                    <v-flex xs6 class="text-xs-center">
                    <a href="#!" class="body-2 black--text">EDIT</a>
                    </v-flex>
                </v-layout>
                <v-list-group
                    v-else-if="item.children"
                    :key="item.text"
                    v-model="item.model"
                    :prepend-icon="item.model ? item.icon : item['icon-alt']"
                    append-icon=""
                >
                    <v-list-tile slot="activator">
                    <v-list-tile-content>
                        <v-list-tile-title>
                        {{ item.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                    </v-list-tile>
                    <v-list-tile
                    v-for="child in item.children"
                    :key="child.name"
                    :to="{ name: child.name}"
                    >
                    <v-list-tile-action v-if="child.icon">
                        <v-icon>{{ child.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>
                        {{ child.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                    </v-list-tile>
                </v-list-group>
                <v-list-tile v-else :key="item.text" @click="abrir(item.name)">
                    <v-list-tile-action>
                    <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                    <v-list-tile-title>
                        {{ item.text }}
                    </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                </template>
            </v-list>
        </v-navigation-drawer>
        <v-toolbar
            v-if="menu"
            :clipped-left="$vuetify.breakpoint.lgAndUp"
            color="blue darken-3"
            dark
            app
            fixed
            >
            <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <span class="hidden-sm-and-down">Sanaval</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon v-on:click="home">
                <v-icon>home</v-icon>
            </v-btn>
            <v-btn icon v-on:click="passwd">
                <v-avatar v-if="user.avatar !='#'" size="32px">
                    <img class="img-fluid" :src="user.avatar">
                </v-avatar>
                <v-icon v-else>settings</v-icon>
            </v-btn>
            <strong v-html="user.name"></strong>
            <v-btn icon large  v-on:click="Logout">
                <v-avatar size="32px" tile>
                    <v-icon>exit_to_app</v-icon>
                </v-avatar>
            </v-btn>
            </v-toolbar>
        <v-content>
            <v-container fluid>

                <router-view></router-view>

            </v-container>
        </v-content>
        </div>
</v-app>
</template>
<script>
import {mapActions} from "vuex";
import {mapState} from 'vuex'
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapState({
            user: state => state.auth
        }),
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin'
		]),
    },
    data: () => ({
        menu: true,
        dialog: false,
        drawer: true,
        show: true,

        // user: {
        //     name : ""
        // },

        root: {
            icon: 'keyboard_arrow_up',
            'icon-alt': 'keyboard_arrow_down',
            text: 'Administrador',
            model: false,
            children: [
                { text: 'Usuarios', name: 'users' },
                { text: 'Roles', name: 'roles' },
                { text: 'Empresas', name: 'empresa.index' },
                { text: 'Retenciones (IRPF)', name: 'ret.index' },
                { text: 'Tipos de IVA', name: 'iva.index' },
                { text: 'Carpetas', name: 'carpeta.index' },
            ]
        },

        admin: {
            icon: 'keyboard_arrow_up',
            'icon-alt': 'keyboard_arrow_down',
            text: 'Administrador',
            model: false,
            children: [
                { text: 'Usuarios', name: 'users' },
                { text: 'Empresas', name: 'empresa.index' },
                { text: 'Retenciones (IRPF)', name: 'ret.index' },
                { text: 'Tipos de IVA', name: 'iva.index' },
                { text: 'Carpetas', name: 'carpeta.index' },
            ]
        },

        items: [
            { icon: 'settings', text: 'Settings' },
        ]
    }),
    mounted(){

        axios.get('/dash')
            .then(res => {
                this.setAuthUser(res.data.user);
                //this.show = true;
                if (this.isRoot)
                    this.items.push(this.root);
                else if(this.isAdmin)
                    this.items.push(this.admin);
            })
            .catch(err => {
                //console.log(err);
                this.show = false;
                if (err.request.status == 401){ // fallo de validated.
                    //this.$router.push("/login");
                    window.location = '/login';
                }
            })

    },
    methods:{
        ...mapActions([
				'setAuthUser'
			]),
        abrir(name){
            this.$router.push({path: name});
        },
        home(){
            this.$router.push({name: 'dash'});
        },
        passwd(){
            this.$router.push({name: 'edit.password'});
        },
        Logout() {
            this.$toast.success('Logout, espere...');
            axios.post('/logout').then(res => {
                //console.log(res);
                this.$store.dispatch('unsetAuthUser')
                this.$router.push({name: 'index'});
			})
        },
    }
  }
</script>
