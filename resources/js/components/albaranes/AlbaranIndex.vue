<template>
    <div v-if="registros">
        <v-layout row wrap>
			    
            <v-flex xs10>
                <h2>{{titulo}}</h2>
            </v-flex>
			<v-flex xs2>
				<v-btn v-on:click="create" small >
					<v-icon small>add</v-icon> Crear Albarán
				</v-btn>
			</v-flex>
        </v-layout>
        <v-layout row wrap>
            <v-flex xs6></v-flex>
            <v-flex xs6>
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Buscar"
                    single-line
                    hide-details
                ></v-text-field>
            </v-flex>
        </v-layout>
        <br/>
        <v-layout row wrap>
			<v-flex xs12>
				<v-data-table
				:headers="headers"
				:items="albaranes"
                :search="search"
                rows-per-page-text="Registros por página"
				>
					<template slot="items" slot-scope="props">
						<td>{{ props.item.alb_ser }}</td>
                        <td>{{ formatDate(props.item.fecha_alb) }}</td>
                        <td>{{ props.item.cliente.razon }}</td>
                        <td class="text-xs-right">{{ totalImpLinea(props.item.albalins) | currency('€', 2, { decimalSeparator: ',', symbolOnLeft: false })}}</td>
						<td>{{ props.item.factura }}</td>
                        <td>{{ formatDate(props.item.fecha_fac) }}</td>
						<td class="justify-center layout px-0">
							<v-icon
								small
								class="mr-2"
								@click="editItem(props.item.id)"
							>
								edit
							</v-icon>


							<v-icon
                            v-if="props.item.factura==null"
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
import moment from 'moment';
import MyDialog from '@/components/shared/MyDialog'
  export default {
    components: {
        'my-dialog': MyDialog
    },
    data () {
      return {
        titulo:"Albaranes",
        search:"",
        headers: [
          {
            text: 'Albarán',
            align: 'center',
            value: 'albaran1'
          },
          {
            text: 'Fecha',
            align: 'left',
            value: 'fecha_alb'
          },
          {
            text: 'Cliente',
            align: 'left',
            value: 'cliente'
          },
          {
            text: 'Importe',
            align: 'right',
            value: 'importe',
            sortable: false
          },
          {
            text: 'Factura',
            align: 'Left',
            value: 'factura'
          },
          {
            text: 'F. Factura',
            align: 'Left',
            value: 'fecha_fac'
          },
          {
            text: 'Acciones',
            align: 'Center',
            value: ''
          }
        ],
        albaranes:[],
        status: false,
		registros: false,
        dialog: false,
        producto_id: 0,

      }
    },
    mounted()
    {

        axios.get('/ventas/albacabs')
            .then(res => {
                //console.log(res);
                this.albaranes = res.data;
                this.registros = true;
            })
            .catch(err =>{
                //console.log(err.response);
                this.$toast.error(err.response.data.message);
                this.$router.push({ name: 'dash' })
            })
    },
    methods:{
        formatDate(f){
            if (f == null) return null;
            moment.locale('es');
            return moment(f).format('DD/MM/YYYY');
        },
        totalImpLinea(lineas){
            var total = 0;
            lineas.map((lin) =>
            {
                var imp = parseFloat(lin.importe);
                var iva = parseFloat(lin.poriva)
                var irpf= parseFloat(lin.porirpf)
                total += (imp + (imp * iva / 100) - (imp * irpf / 100));

            })
            return total.toFixed(2);
        },
        create(){
            this.$router.push({ name: 'albaran.create'})
        },
        editItem (id) {
            this.$router.push({ name: 'albaran.edit', params: { id: id } })
        },
        openDialog (id){
            this.dialog = true;
            this.albaran_id = id;
        },
        destroyReg () {
            this.dialog = false;

            axios.post('/ventas/albacabs/'+this.albaran_id,{_method: 'delete'})
                .then(response => {
                this.albaranes = response.data;
                this.$toast.success('Albarán eliminado!');

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
