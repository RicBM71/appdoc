<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on }">
                <v-btn
                    v-on="on"
                    color="white"
                    icon
                    @click="goIndex"
                >
                    <v-icon color="primary">list_alt</v-icon>
                </v-btn>
            </template>
            <span>Filtrar</span>
        </v-tooltip>
    </div>
</template>
<script>
export default {
    props:{
        id: Number
    },
    data () {
      return {
          dialog: false,
      }
    },
    methods:{
        goIndex(){
            this.$router.push({ name: 'extracto.index' })
        },
    }
}
</script>
