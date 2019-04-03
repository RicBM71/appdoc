<template>
    <div v-if="registros">
        <v-layout row wrap>
			<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-flex xs10>
                <h2>{{titulo}}</h2>
            </v-flex>
			<v-flex xs2>
				<v-btn v-on:click="create" small >
					<v-icon small>add</v-icon> Crear F. Pago
				</v-btn>
			</v-flex>
        </v-layout>
        <v-layout row wrap>
			<v-flex xs12>
				<v-data-table
				:headers="headers"
				:items="this.fpagos"
                rows-per-page-text="Registros por página"
				>
					<template slot="items" slot-scope="props">
						<td>{{ props.item.id }}</td>
						<td>{{ props.item.nombre }}</td>
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
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        fpagos:[],
        status: false,
		registros: false,
        dialog: false,
        fpago_id: 0,
        titulo:"Formas de Pago"
      }
    },
    mounted()
    {

        axios.get('/admin/fpagos')
            .then(res => {
                console.log(res);
                this.fpagos = res.data;
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
            this.$router.push({ name: 'fpago.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'fpago.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.iva_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/fpagos/'+this.iva_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Forma de pago eliminada!');
                    this.fpagos = response.data;
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
