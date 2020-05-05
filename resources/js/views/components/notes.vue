<template>
    <div>
        <div class="row">
        <div class="col-lg-6 col-xs-12 text-md-center">
            <v-alert v-show="alert.show" dismissible="dismissible"
                     :type="alert.alert_type"
                     class="mb-12"
            >
                {{alert.alert_msg}}
            </v-alert>
            <h2>Create New Note</h2>
            <v-layout>
                <v-flex xs12>
                    <v-card>
                        <v-card-text>
                            <form @keyup.enter="newNote">
                                <v-text-field
                                        v-model="note.title"
                                        label="Title"
                                        required
                                >
                                </v-text-field>
                                <v-textarea
                                        name="input-7-1"
                                        filled
                                        label="Description"
                                        auto-grow
                                        value=""
                                        v-model="note.body"
                                ></v-textarea>
                            </form>
                        </v-card-text>
                        <div class="text-center">
                            <v-btn class="mb-3" success @click='newNote'>Create Note</v-btn>
                        </div>
                    </v-card>
                </v-flex>

            </v-layout>

        </div>
        <div class="col-lg-6 col-xs-12 notes">
        <div class="notes-card" v-for="note in notes" :key="note.id">
        <v-card
                class="mx-auto"
                max-width="344"
                RAISED
        >
            <v-list-item three-line>
                <v-list-item-content>
                    <div class="overline mb-12">created {{note.created_at}} by {{note.user.name}} </div>
                    <v-list-item-title class="headline mb-1">{{note.title}}</v-list-item-title>
                    <v-list-item-subtitle>{{note.body}}</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-card-actions>
                <v-btn text @click="editNote(note.id)">Edit</v-btn>
                <v-btn text @click="deleteNote(note.id)">Delete</v-btn>
            </v-card-actions>
        </v-card>
        </div>
        </div>
        </div>
        <div id="modal">
            <v-dialog v-model="dialog" persistent max-width="600px">
                <v-card>
                    <v-card-title>
                        <span class="headline">Your Note</span>
                    </v-card-title>
                    <v-card-text>
                        <v-container>
                            <v-row>
                                <v-col cols="12" sm="12" md="12">
                                    <v-text-field v-model="edit_note.title" label="Title" required></v-text-field>
                                </v-col>
                                <v-col cols="12" sm="12" md="12">
                                    <v-textarea
                                            name="input-7-1"
                                            filled
                                            label="Description"
                                            auto-grow
                                            value=""
                                            hint="Your Note description body"
                                            v-model="edit_note.body"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="blue darken-1" text @click="dialog = false">Cancel</v-btn>
                        <v-btn color="blue darken-1" text @click="updateNote">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </div>

    </div>
</template>
<script>
export default {
    data(){
        return {
            dialog: false,
            edit_note: {
                id: '',
                title: '',
                body: '',
            },
            notes:[],
            note:{
                title:"",
                body:""
            },
            alert:{
            show:false,
                alert_type:"success",
                alert_msg:"",
        }
        }
    },
    methods:{
        newNote(){
            this.$api.post('/note',this.note,{headers: {'Authorization': 'Bearer '+localStorage.getItem('token')}})
                .then(response => {
                    if(response.status == 200){
                        this.loadAllNotes();
                        this.clearForm();
                    }
                }).catch(err => {
                this.alert.show = true;
                this.alert.alert_type = 'error';
                this.alert.alert_msg = err.response.data[Object.keys(err.response.data)[0]];
            });
        },
        deleteNote(id){
          this.$api.delete('/note/'+id,{headers: {'Authorization': 'Bearer '+localStorage.getItem('token')}})
              .then(response=>{
                  this.loadAllNotes();
              })
        },
        editNote(id){
           this.$api.get('/note/'+id,{headers: {'Authorization': 'Bearer '+localStorage.getItem('token')}})
               .then(response => {
                    this.edit_note.id = response.data.id;
                    this.edit_note.title = response.data.title;
                    this.edit_note.body = response.data.body;
                    this.dialog = true;
                   })
        },
        updateNote(){
           this.$api.put('/note',this.edit_note,{headers: {'Authorization': 'Bearer '+localStorage.getItem('token')}})
               .then(response =>{
                 this.loadAllNotes();
                 this.dialog = false;
               })
        },
        clearForm(){
            this.note.title = "";
            this.note.body = "";
        },
        loadAllNotes(){
            if(this.$store.state.logged_in) {
                this.$api.get('/note', {headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}})
                    .then(response => {
                        if (response.status == 200) {
                            this.notes = response.data.data;
                        }
                    }).catch(err => {
                    if (err.response.status == 401 && err.response.statusText == 'Unauthorized')
                        localStorage.removeItem('token');
                    this.$store.state.logged_in = false;
                });
            }
        }
    },
    mounted(){
        this.loadAllNotes();
    }
}
</script>