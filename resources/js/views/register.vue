<template>
    <div>
        <v-alert v-show="alert.show" dismissible="dismissible"
                 :type="alert.alert_type"
                 class="mb-4"
        >
            {{alert.alert_msg}}
        </v-alert>
        <h2>Register</h2>
        <v-layout>
            <v-flex xs12 md6 offset-md3>
                <v-card>
                    <v-card-text>
                        <form @keyup.enter="registerUser">
                            <v-text-field
                                    v-model="register.name"
                                    label="name"
                                    required
                            >
                            </v-text-field>

                            <v-text-field
                                    v-model="register.email"
                                    label="Email"
                                    required
                            >
                            </v-text-field>
                            <v-text-field
                                    v-model="register.password"
                                    label="Password"
                                    type="password"
                                    required

                            >
                            </v-text-field>
                        </form>
                    </v-card-text>
                    <div class="text-center">
                        <v-btn class="mb-3" success @click='registerUser'>Register</v-btn>
                    </div>
                </v-card>
            </v-flex>

        </v-layout>
    </div>
</template>
<script>
    export default {
        data(){
           return {
               register: {
                   name: "",
                   email: "",
                   password: ""
               },
               alert:{
                   show:false,
                   alert_type:"success",
                   alert_msg:""
               }
           }
        },
        methods:{
            registerUser(){
                this.$api.post('/register',this.register)
                    .then(response => {
                        if(response.status == 200){
                            this.alert.show = true;
                            this.alert.alert_type = 'success';
                            this.alert.alert_msg = response.data.msg;
                            this.clearForm()
                        }
                    }).catch(err => {
                    this.alert.show = true;
                    this.alert.alert_type = 'error';
                    this.alert.alert_msg = err.response.data[Object.keys(err.response.data)[0]];
                });
            },
            clearForm(){
                this.register.name = "";
                this.register.email = "";
                this.register.password = "";
            }
        }
    }
</script>