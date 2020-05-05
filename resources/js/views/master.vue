<template>
    <v-app >
        <v-app-bar app>
            <v-toolbar-title class="headline text-uppercase">
                <router-link :to="{name:'home'}"> <span>APP LOGO</span></router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <router-link  v-show="!loggedIn" :to="{name:'login'}">
                <v-btn text>
                    <span class="mr-1">Login</span>
                </v-btn>
            </router-link>
            <router-link v-show="!loggedIn" :to="{name:'register'}">
                <v-btn text>
                    <span class="mr-1">Register</span>
                </v-btn>
            </router-link>
            <v-btn v-show="loggedIn" @click="logOut" text>
                <span class="mr-1">Log Out</span>
            </v-btn>
        </v-app-bar>
        <v-content>
            <v-container class="container">
                <router-view/>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        methods:{
          logOut(){
              localStorage.removeItem('token');
              this.$store.state.logged_in = false;
              this.$router.push('/login');
          }
        },
        computed:{
            loggedIn(){
                return this.$store.state.logged_in;
            }
        }
    }
</script>