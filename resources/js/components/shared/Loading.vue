<template>
    <v-dialog v-model="show_loading" persistent max-width="290">
      <v-card
			color="primary"
			dark
			>
			<v-card-text>
                <div v-if="process_titulo != ''"> {{process_titulo}}</div>
                <div v-else>Procesando, espere...</div>

				<v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
				></v-progress-linear>
			</v-card-text>
			</v-card>
    </v-dialog>
</template>
<script>
export default {
    props:{
        show_loading: Boolean,
        process_titulo: String
    }
}
</script>




