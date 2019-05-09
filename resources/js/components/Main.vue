<template>
    <h1>Main</h1>
</template>
<script>
import {mapActions} from "vuex";
import {mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters([
            'isLoggedIn',
            'isRoot',
            'isAdmin'
		]),
    },
    data: () => ({

    }),
    mounted(){
        axios.get('/dash')
            .then(res => {
                this.setAuthUser(res.data.user);
            })
            .catch(err => {
                console.log(err);
        })

    },
    methods:{
        ...mapActions([
				'setAuthUser'
			]),
    }
  }
</script>
