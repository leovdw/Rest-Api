<template>
    <div class="hello">
        <h1 v-if="user">Hello, {{ user.user_name }}</h1>
        
        <ul>
            <li v-for="item in errors" v-bind:key="item" class="error">
                <div v-html="item"/>
            </li>
        </ul>
        <!--USER ACTIONS-->
        <button v-if="user" v-on:click="delete_user">Supprimer le compte</button>
        <button v-if="user" v-on:click="logout">logout</button>
        <button v-if="user" v-on:click="form_update = true">Modifier mes informations</button>
        <button v-if="user" v-on:click="form_room = true">Ajouter une chatroom</button>
        <button v-if="user" v-on:click="get_user_messages">Voir les messages que j'ai envoyé</button>

        <ul v-if="user && user_message.length > 0">
            <li v-for="item in user_message" v-bind:key="item.id">
                <div v-html="item.content"/>
                , ID de la chatroom contenant le message :
                <div v-html="item.chatroom"/>
            </li>
        </ul>

        <button v-if="!user" v-on:click="change_form">{{form_text}}</button>
        <h4 v-if="validation_mesage !== ''">{{validation_mesage}}</h4>

        <!--USER FORMS-->
        <form @submit="log_in" v-if="!user && form_type === 'log_in'">
            <label for="id">User name</label>
            <input v-model="user_log" type="text" id="id">

            <label for="id">User Password</label>
            <input v-model="user_pass" type="password">
            <input type="submit" value="Submit">
        </form>
        <form @submit="register" v-if="!user && form_type !== 'log_in'">
            <label for="id">User name</label>
            <input v-model="user_log" type="text" id="id">

            <label for="id">User Password</label>
            <input v-model="user_pass" type="password">

            <label for="name">User Name</label>
            <input v-model="user_name" type="text" id="name">

            <input type="submit" value="Submit">
        </form>
        <form @submit="update" v-if="user && form_update">
            <label for="id">User Log-in</label>
            <input v-model="user_log" type="text" id="id">

            <label for="id">User Password</label>
            <input v-model="user_pass" type="password">

            <label for="name">User Name</label>
            <input v-model="user_name" type="text" id="name">

            <input type="submit" value="Submit">
        </form>

        <!--ROOMS FORMS-->
        <form @submit="add_chatroom" v-if="user && form_room">
            <label for="chat_name">Nom de la chatroom</label>
            <input v-model="chatrooms_name" type="text" id="chat_name">

            <input type="submit" value="Submit">
        </form>
        <form @submit="update_chatroom" v-if="user && form_room_upd_form">
            <label for="chat_name">Nouveau nom de la chatroom</label>
            <input v-model="chatrooms_name" type="text" id="chat_name">

            <input type="submit" value="Submit">
        </form>


        <H3 v-if="chatrooms.length === 0 && user">Malheureusement vous n'avez aucune chatroom pour l'instant, creez en
            une !</H3>

        <!--ROOMS LIST-->
        <div v-if="chatrooms.length > 0 && user">
            <h3>Vos chatrooms</h3>
            <ul>
                <li v-for="item in chatrooms" v-bind:key="item.name">
                    <div v-on:click="change_chatroom(item.name , item.id)" v-html="item.name"/>
                    <button v-on:click="form_room_upd(item.id)">Edit</button>
                    <button v-on:click="delete_chatroom(item.id)">Delete</button>
                </li>
            </ul>
            <div v-if="current_chat">
                <h1>{{current_chat}}</h1>
                <ul class="chatroom">
                    <li v-for="item in chatrooms_message" v-bind:key="item.name">
                        <div v-html="item.username"/>
                        :
                        <div v-html="item.content"/>
                    </li>
                </ul>
                <div>
                    <form @submit="send_message">
                        <input v-model="message" type="text" id="message_text">
                        <input type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'HelloWorld',
        data() {
            return {
                user: false,
                user_log: '',
                user_pass: '',
                user_name: '',
                user_message: [],
                message: '',

                form_update: false,
                form_room: false,
                form_room_upd_form: false,
                form_type: 'log_in',
                form_text: 'Register',

                errors: [],

                chatrooms: [],
                current_chat: '',
                current_chat_id: '',
                room_update: '',
                chatrooms_name: '',
                chatrooms_message: [],

                validation_mesage: '',
            }
        },
        methods: {
            //User functions
            update: function (e) {
                e.preventDefault();
                if (this.user_log !== '' && this.user_pass !== '' && this.user_name !== '') {
                    fetch('http://localhost:8888/api_rest/api/controllers/users_controller.php?action=update', {
                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                        mode: "cors", // no-cors, cors, *same-origin
                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: "same-origin", // include, *same-origin, omit
                        headers: {
                            "Content-Type": "application/json",
                            // "Content-Type": "application/x-www-form-urlencoded",
                        },
                        redirect: "follow", // manual, *follow, error
                        referrer: "no-referrer", // no-referrer, *client
                        body: JSON.stringify(
                            {
                                id: this.user.user_id,
                                login: this.user_log,
                                password: this.user_pass,
                                name: this.user_name,
                                token: sessionStorage.getItem('User_token'),
                            }
                        )
                    })
                        .then(res => res.clone().json())
                        .then(json => {
                            if (json[1] === "Succses") {
                                this.validation_mesage = 'Utilisateur bien modifié !';
                                this.user.user_name = this.user_name;
                                this.form_update = false;
                                this.errors = '';
                            } else {
                                this.errors = json;
                            }
                        })
                }
            },

            log_in: function (e) {
                e.preventDefault();

                if (this.user_log !== '' && this.user_pass !== '') {
                    fetch('http://localhost:8888/api_rest/api/controllers/users_controller.php?action=login', {
                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                        mode: "cors", // no-cors, cors, *same-origin
                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: "same-origin", // include, *same-origin, omit
                        headers: {
                            "Content-Type": "application/json",
                            // "Content-Type": "application/x-www-form-urlencoded",
                        },
                        redirect: "follow", // manual, *follow, error
                        referrer: "no-referrer", // no-referrer, *client
                        body: JSON.stringify(
                            {
                                login: this.user_log,
                                password: this.user_pass
                            }
                        )
                    })
                        .then(res => res.clone().json())
                        .then(json => {
                            if (json[1] !== 'succes') {
                                this.errors = json[0];
                                this.validation_mesage = '';
                            } else {
                                this.user = {
                                    user_id: json[0],
                                    user_name: this.user_name,
                                    log: this.user_log,
                                };
                                sessionStorage.setItem('User_token', json[2]);
                                this.errors = [];
                                this.get_chatroom();
                            }
                        })
                }
            },

            register: function (e) {
                e.preventDefault();
                if (this.user_log !== '' && this.user_pass !== '' && this.user_name !== '') {
                    fetch('http://localhost:8888/api_rest/api/controllers/users_controller.php?action=register', {
                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                        mode: "cors", // no-cors, cors, *same-origin
                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: "same-origin", // include, *same-origin, omit
                        headers: {
                            "Content-Type": "application/json",
                            // "Content-Type": "application/x-www-form-urlencoded",
                        },
                        redirect: "follow", // manual, *follow, error
                        referrer: "no-referrer", // no-referrer, *client
                        body: JSON.stringify(
                            {
                                login: this.user_log,
                                password: this.user_pass,
                                name: this.user_name,
                            } // body data type must match "Content-Type" header
                        )
                    })
                        .then(res => res.clone().json())
                        .then(json => {
                            if (json === 'succes') {
                                this.validation_mesage = 'Utilisateur a bien été créé ! Identifiez vous !';
                            }
                        })
                } else {
                    this.validation_mesage = '';
                    this.errors = [];
                    this.errors = ['One of the fields has not been field'];
                }
            },

            logout: function () {
                this.user = '';
                this.name = '';
                this.form_type = 'log_in';
                this.form_text = 'Register';
                this.validation_mesage = 'Vous vous êtes bien déconecté';
                this.user = false;
                this.user_log = '';
                this.user_pass = '';
                this.user_name = '';

                this.message = '';

                this.form_update = false;
                this.form_room = false;
                this.form_room_upd_form = false;
                this.form_type = 'log_in';
                this.form_text = 'Register';

                this.errors = [];

                this.chatrooms = [];
                this.current_chat = '';
                this.current_chat_id = '';
                this.room_update = '';
                this.chatrooms_name = '';
                this.chatrooms_message = [];

                this.validation_mesage = '';
                sessionStorage.removeItem('User_token');
            },

            delete_user: function () {
                fetch('http://localhost:8888/api_rest/api/controllers/users_controller.php?action=delete', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                        // "Content-Type": "application/x-www-form-urlencoded",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            id: this.user.user_id,
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        this.validation_mesage = 'Votre utilisateur a bien été supprimé !';
                        this.user = '';
                        this.name = '';
                        this.form_type = 'log_in';
                        this.form_text = 'Register';
                        this.user = false;
                        this.user_log = '';
                        this.user_pass = '';
                        this.user_name = '';

                        this.message = '';

                        this.form_update = false;
                        this.form_room = false;
                        this.form_room_upd_form = false;
                        this.form_type = 'log_in';
                        this.form_text = 'Register';

                        this.errors = [];

                        this.chatrooms = [];
                        this.current_chat = '';
                        this.current_chat_id = '';
                        this.room_update = '';
                        this.chatrooms_name = '';
                        this.chatrooms_message = [];

                        sessionStorage.removeItem('User_token');
                    })
            },

            //Form functions
            change_form: function () {
                this.validation_mesage = '';
                if (this.form_type === 'log_in') {
                    this.form_type = 'register';
                    this.form_text = 'Log in';
                } else {
                    this.form_type = 'log_in';
                    this.form_text = 'Register';
                }
            },

            //Chatroom functions
            add_chatroom: function (e) {
                e.preventDefault();
                if (this.chatrooms_name !== '') {
                    fetch('http://localhost:8888/api_rest/api/controllers/chat_controller.php?action=create', {
                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                        mode: "cors", // no-cors, cors, *same-origin
                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: "same-origin", // include, *same-origin, omit
                        headers: {
                            "Content-Type": "application/json",
                            // "Content-Type": "application/x-www-form-urlencoded",
                        },
                        redirect: "follow", // manual, *follow, error
                        referrer: "no-referrer", // no-referrer, *client
                        body: JSON.stringify(
                            {
                                name: this.chatrooms_name,
                                creator: this.user.user_id,
                            } // body data type must match "Content-Type" header
                        )
                    })
                        .then(res => res.clone().json())
                        .then(json => {
                            if (json !== 'succes') {
                                this.errors = json;
                                this.validation_mesage = '';
                            } else {
                                this.validation_mesage = 'la chatroom bien créée! envoyez un message !';
                                this.errors = [];
                                this.form_room = false;
                                this.get_chatroom(json['id']);
                                this.current_chat = this.chatroom_name;
                            }
                        })
                } else {
                    this.errors = [];
                    this.errors = ['One of the fields has not been field'];
                }
            },

            get_chatroom: function () {
                fetch('http://localhost:8888/api_rest/api/controllers/chat_controller.php?action=list', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            id: this.user.user_id
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        this.chatrooms = json;
                    })
            },

            change_chatroom: function (chatroom, id) {
                this.validation_mesage = '';
                this.chatrooms_message = [];
                this.current_chat = chatroom;
                this.current_chat_id = id;
                this.get_chatroom_messages();
            },

            get_chatroom_messages: function () {
                this.validation_mesage = '';
                fetch('http://localhost:8888/api_rest/api/controllers/messages_controller.php?action=list_room', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                        // "Content-Type": "application/x-www-form-urlencoded",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            chatroom: this.current_chat_id,
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        if (json.length > 0) {
                            console.log(json);
                            this.chatrooms_message = json;
                        }
                    })
            },

            update_chatroom: function (e) {
                e.preventDefault();
                this.validation_mesage = '';
                fetch('http://localhost:8888/api_rest/api/controllers/chat_controller.php?action=update_chat', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                        // "Content-Type": "application/x-www-form-urlencoded",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            id: this.room_update,
                            name: this.chatrooms_name,
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        if (json === 'Succses') {
                            this.validation_mesage = 'Nom de la chatroom ien modifié !';
                            this.get_chatroom();
                            this.form_room_upd_form = false;
                        } else {
                            this.errors = json;
                        }

                    })
            },

            delete_chatroom: function (ID) {
                console.log(ID);
                this.validation_mesage = '';
                fetch('http://localhost:8888/api_rest/api/controllers/chat_controller.php?action=delete', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                        // "Content-Type": "application/x-www-form-urlencoded",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            id: ID,
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        this.get_chatroom();
                        this.validation_mesage = 'Chatroom Supprimée !';
                    })
            },

            form_room_upd: function (room) {
                this.room_update = room;
                this.form_room_upd_form = true;
            },

            //Message functions
            send_message: function (e) {
                e.preventDefault();
                this.validation_mesage = '';
                if (this.message !== '') {
                    fetch('http://localhost:8888/api_rest/api/controllers/messages_controller.php?action=create', {
                        method: "POST", // *GET, POST, PUT, DELETE, etc.
                        mode: "cors", // no-cors, cors, *same-origin
                        cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                        credentials: "same-origin", // include, *same-origin, omit
                        headers: {
                            "Content-Type": "application/json",
                            // "Content-Type": "application/x-www-form-urlencoded",
                        },
                        redirect: "follow", // manual, *follow, error
                        referrer: "no-referrer", // no-referrer, *client
                        body: JSON.stringify(
                            {
                                user_id: this.user.user_id,
                                user_name: this.user.user_name,
                                content: this.message,
                                chatroom: this.current_chat_id,
                            }
                        )
                    })
                        .then(res => res.clone().json())
                        .then(json => {
                            this.message = '';
                            this.get_chatroom_messages()
                        })
                }
            },

            get_user_messages: function () {
                fetch('http://localhost:8888/api_rest/api/controllers/messages_controller.php?action=user_list', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                        // "Content-Type": "application/x-www-form-urlencoded",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            user_id: this.user.user_id,
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        console.log(json);
                        this.user_message = json;
                    })
            },
        },

        computed: {},

        mounted: function () {
            const token = sessionStorage.getItem('User_token');
            if (token) {
                fetch('http://localhost:8888/api_rest/api/controllers/users_controller.php?action=checktoken', {
                    method: "POST", // *GET, POST, PUT, DELETE, etc.
                    mode: "cors", // no-cors, cors, *same-origin
                    cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
                    credentials: "same-origin", // include, *same-origin, omit
                    headers: {
                        "Content-Type": "application/json",
                    },
                    redirect: "follow", // manual, *follow, error
                    referrer: "no-referrer", // no-referrer, *client
                    body: JSON.stringify(
                        {
                            token: token
                        }
                    )
                })
                    .then(res => res.clone().json())
                    .then(json => {
                        this.user = {
                            user_id: json['id'],
                            user_name: json['firstname'],
                            log: json['login'],
                        };
                        this.get_chatroom(json['id']);
                    })
            }
        }
    }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
    h3 {
        margin: 40px 0 0;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: flex;
    }

    a {
        color: #42b983;
    }

    form {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 50%;
        margin: 0 auto;
        margin-top: 50px;
    }

    label {
        margin-top: 25px;
    }

    input {
        margin-top: 20px;
    }

    .chatroom {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .chatroom li {
        display: flex;
        justify-content: space-around;
        max-width: 200px;
        align-self: center;
    }

    .error {
        color: red;
    }

    button {
        margin-left: 20px;
    }
</style>
