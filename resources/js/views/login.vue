<template>
    <div class="text-md-center">
        <v-alert v-show="alert.show" dismissible="dismissible"
                 :type="alert.alert_type"
                 class="mb-4"
        >
            {{alert.alert_msg}}
        </v-alert>
        <h2>Login</h2>
        <v-layout>
            <v-flex xs12 md6 offset-md3>
                <v-card>
                    <v-card-text>
                        <form @keyup.enter="logIn">
                            <v-text-field
                                    v-model="login.email"
                                    label="Email"
                                    required
                            >
                            </v-text-field>

                            <v-text-field
                                    v-model="login.password"
                                    label="Password"
                                    type="password"
                                    required

                            >
                            </v-text-field>
                        </form>
                    </v-card-text>
                    <div class="text-center">
                        <v-btn class="mb-3" success @click='logIn'>Login</v-btn>
                    </div>
                </v-card>
            </v-flex>

        </v-layout>

    </div>
</template>
<script>
export default {
    data() {
        return {
            login:{
                email:"",
                password:""
            },
            alert:{
                show:false,
                alert_type:"success",
                alert_msg:"",
            }
        }
    },
    methods:{
        logIn(){
            this.$api.post('/login',this.login)
                .then(response => {
                    let newToken = response.data.api_key;
                    localStorage.setItem('token',newToken);
                    this.$store.state.logged_in = true;
                    this.$router.push('/');
                }).catch(err => {
                this.alert.show = true;
                this.alert.alert_type = 'error';
                this.alert.alert_msg = err.response.data[Object.keys(err.response.data)[0]];
            });
        }
    }
}
</script>
<style>

</style>