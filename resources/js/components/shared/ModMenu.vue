<template>
    <v-menu
        v-model="showMenuCli"
        :position-x="x"
        :position-y="y"
        nudge-left
        absolute
        offset-y
        >
        <v-list>
            <v-list-tile
            v-for="(item, index) in items"
            :key="index"
            @click="irRuta(item.name)"
            >
            <v-list-tile-title><v-icon>{{ item.icon}}</v-icon> {{ item.title }}</v-list-tile-title>
            </v-list-tile>
        </v-list>
    </v-menu>

</template>
<script>
	export default {
        props: {
            items: Array,
            showMenuCli: Boolean,
            x: Number,
            y: Number
        },
    	methods:{
            irRuta(name){
                this.$router.push({ name: name})
            }
        },
        computed:{

            showMenuCli: {
                get() { return this.$store.getters.getshowMenuCli; },
                set(newValue) { this.$store.dispatch('setshowMenuCli', newValue); }
            }
                //this.$emit('showMenuCli', this.showMenuCli);
        }
    }
</script>
