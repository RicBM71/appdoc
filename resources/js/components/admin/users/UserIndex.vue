<template>
    <div v-if="registros">
        <v-layout row wrap>
			<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-flex xs10>
                <h2>Usuarios</h2>
            </v-flex>
			<v-flex xs2>
				<v-btn v-on:click="create" small >
					<v-icon small>add</v-icon> Crear Usuario
				</v-btn>
			</v-flex>
        </v-layout>
        <v-layout row wrap>
			<v-flex xs12>
				<v-data-table
				:headers="headers"
				:items="usuarios"
                rows-per-page-text="Registros por página"
				>
					<template slot="items" slot-scope="props">
						<td>{{ props.item.id }}</td>
						<td  v-if="props.item.blocked == false"  class="text-xs-left">{{ props.item.name }}</td>
                        <td v-else class="text-xs-left"><span class="red--text">BLOQUEADO -></span></td>
						<td class="text-xs-left">{{ props.item.username }}</td>
						<td class="text-xs-left">{{ props.item.email }}</td>
						<td class="text-xs-left">{{ extrae(props.item.roles) }}</td>
						<td class="justify-center layout px-0">
							<v-icon
								small
								class="mr-2"
								@click="editItem(props.item.id)"
							>
								edit
							</v-icon>


							<v-icon
							small
							@click="openDialog(props.item.id)"
							>
							delete
							</v-icon>
						</td>
					</template>
					<template slot="pageText" slot-scope="props">
						Registros {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
					</template>
				</v-data-table>
			</v-flex>
		</v-layout>
    </div>
</template>
<script>
import MyDialog from '@/components/shared/MyDialog'
  export default {
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
        headers: [
          {
            text: 'ID',
            align: 'center',
            value: 'id'
          },
          {
            text: 'Nombre',
            align: 'left',
            value: 'name'
          },
          { text: 'Username', align: 'left', value: 'name' },
          { text: 'Email', align: 'left', value: 'email' },
          { text: 'Roles', align: 'left', value: 'roles', sortable: false, },
          { text: 'Acciones', align: 'left', value: 'acciones', sortable: false, }
        ],
        usuarios:[],
        status: false,
		registros: false,
        dialog: false,
        user_id: 0
      }
    },
    mounted()
    {

        axios.get('/admin/users')
            .then(res => {

                this.usuarios = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        create(){
            this.$router.push({ name: 'users_create', params: { id: '0' } })
        },
        extrae: function(role){ // extrae los permisos de cada role.

            var i=0;
            var roles = "";
            role.forEach(function(element) {
            roles += (element.name);
            ++i;
            if (i < role.length)
                roles+=", ";
            });

            return roles;
        },
        editItem (id) {
            this.$router.push({ name: 'users_edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.user_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/users/'+this.user_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Usuario borrado correctamente');
                    this.usuarios = response.data;
                }
                })
            .catch(err => {
                this.status = true;
               // console.log(err.response.data.message);
                var msg = err.response.data.message;
                this.$toast.error(msg);

            });

        },

    }
  }
</script>
