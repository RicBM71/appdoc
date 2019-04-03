<template>
    <div v-if="registros">
        <v-layout row wrap>
			<my-dialog :dialog.sync="dialog" registro="registro" @destroyReg="destroyReg"></my-dialog>
            <v-flex xs10>
                <h2>{{titulo}}</h2>
            </v-flex>
			<v-flex xs2>
				<v-btn v-on:click="create" small >
					<v-icon small>add</v-icon> Crear Contador
				</v-btn>
			</v-flex>
        </v-layout>
        <v-layout row wrap>
			<v-flex xs12>
				<v-data-table
				:headers="headers"
				:items="this.contadores"
                rows-per-page-text="Registros por página"
				>
					<template slot="items" slot-scope="props">
						<td>{{ props.item.id }}</td>
						<td>{{ props.item.ejercicio }}</td>
                        <td class="text-xs-center">{{ props.item.seriealb+"-"+props.item.albaran}}</td>
                        <td class="text-xs-center">{{ props.item.seriefac+"-"+props.item.factura}}</td>
                        <td class="text-xs-center">{{ props.item.serieabo+"-"+props.item.abono}}</td>
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
            text: 'Ejercicio',
            align: 'left',
            value: 'ejercicio'
          },
          {
            text: 'Serie Albarán',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Serie Facturas',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Serie Abonos',
            align: 'center',
            value: 'serie'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        contadores:[],
        status: false,
		registros: false,
        dialog: false,
        contador_id: 0,
        titulo:"Contadores"
      }
    },
    mounted()
    {

        axios.get('/admin/contadors')
            .then(res => {

                this.contadores = res.data;
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
            this.$router.push({ name: 'contador.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'contador.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.iva_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/admin/contadores/'+this.iva_id,{_method: 'delete'})
                .then(response => {

                if (response.status == 200){
                    this.$toast.success('Contador eliminado!');
                    this.contadores = response.data;
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
