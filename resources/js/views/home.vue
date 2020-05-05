<template>
    <div>
        <notes v-show="loggedIn"></notes>
        <nonAuth v-show="!loggedIn"></nonAuth>
    </div>
</template>
<script>
    import notes from "../views/components/notes";
    import nonAuth from "../views/components/nonAuth";
    export default {
        components: {
            notes,
            nonAuth
        },
        computed:{
            loggedIn(){
                 return this.$store.state.logged_in;
            }
        }
    }
</script>