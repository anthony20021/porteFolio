<template>
    <div v-if="!rep">
        <div class="d-block w-100 shadow mb-3" id="sideBarAdminDashboard">
            <ul class="m-0 d-flex flex-wrap flex-row">
                <li class="col p-2" @click="loadComponent(1)">
                    <p class="m-0">Projets</p>
                </li>
                <li class="col p-2" @click="loadComponent(2)">
                    <p class="m-0">Codes</p>
                </li>
                <li class="col p-2" @click="loadComponent(3)">
                    <p class="m-0">Experiences</p>
                </li>
                <li class="col p-2" @click="loadComponent(4)">
                    <p class="m-0">CV</p>
                </li>
                <li class="col p-2" @click="loadComponent(5)">
                    <p class="m-0">Messages</p>
                </li>
            </ul>
        </div>

        <div class="pt-3" style="width: 100%;">
            <!-- Mettre des components -->
            <project-page v-if="components === 1" :projects="projects"></project-page>
            <code-page v-if="components === 2" :codes="codes"></code-page>
            <experience-page v-if="components === 3" :experiences="experiences"></experience-page>
            <cv-page v-if="components === 4" :cv="cv"></cv-page>

            <div v-if="components === 5">
                <h1>Messages</h1>

                <table class="table table-bordered table-responsive">
                    <thead class="table-dark table-sm">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Prénom</th>
                            <th scope="col">Email</th>
                            <th scope="col">Sujet</th>
                            <th scope="col">Message</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="message in messages" :key="message.id">
                            <td>{{ message.name }}</td>
                            <td>{{ message.firstname }}</td>
                            <td>{{ message.email }}</td>
                            <td>{{ message.sujet }}</td>
                            <td>{{ message.message }}</td>
                            <td>
                                <button class="btn btn-danger" @click="deleteMessage(message.id)">Supprimer</button>
                                <button class="btn btn-warning" @click="changeScreen(message.email)">Répondre</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div v-else-if="rep" class="d-flex flex-column justify-content-center align-items-center" style="margin-top: 120px;">
        <div class="d-flex justify-content-around w-50">
            <h3>Répondre</h3>
            <button class="btn btn-danger" @click="rep = false">Retour</button>
        </div>
        <div class="w-80 h-50">
            <div class="form-group">
                <label for="sujet">Sujet</label>
                <input id="sujet" type="text" class="form-control" v-model="data.sujet">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <trumbowyg v-model="data.content"></trumbowyg>
            </div>
            <div>
                <button class="btn btn-warning" @click="sendMessage">Valider</button>
            </div>
        </div>
    </div>
</template>

<script>
import CvPage from './cv/CvPage.vue';
import ExperiencePage from './experiences/ExperiencePage.vue';
import ProjectPage from './projects/ProjectPage.vue';
import CodePage from './codes/CodePage.vue';
import Swal from 'sweetalert2';
import Trumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';

export default {
    props: {
        codes: Array,
        experiences: Array,
        projects: Array,
        cv: Array, 
        messages: Array,   
    },

    data() {
        return {
            components: 0,
            rep: false,
            data: {
                content: '',
                sujet: '',
                to: '',
            },
            Toast: Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    container: 'toast-perso'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
            }),
        }
    },

    methods: {
        changeScreen(email) {
            this.data.to = email;  // Make sure to update the correct object
            this.rep = true;
        },
        async sendMessage() {
            try {
                const response = await axios.post(baseurl + '/gestion/message', this.data);
                if (response.data.statut === 'ok') {
                    this.Toast.fire(response.data.message, '', 'success');
                } else {
                    this.Toast.fire(response.data.message, '', 'error');
                }
            } catch (error) {
                this.Toast.fire('An error occurred while sending the message', '', 'error');
            }
        },

        async deleteMessage(id){
            try {
                const response = await axios.delete(baseurl + '/gestion/message/', { data: { data : { id : id } } } );
                if (response.data.statut === 'ok') {
                    this.Toast.fire(response.data.message, '', 'success');
                } else {
                    this.Toast.fire(response.data.message, '', 'error');
                }
            } catch (error) {
                this.Toast.fire('An error occurred while deleting the message', '', 'error');
            }
        },

        loadComponent(component) {
            this.components = component;
        }
    },

    components: {
        CodePage,
        ProjectPage,
        ExperiencePage,
        CvPage,
        Trumbowyg,
    },
}
</script>

<style>
#sideBarAdminDashboard {
    position: relative;
    height: 100%;
    width: 200px;
    left: 0px;
    top: 0px;
}

input{
    background-color: black !important;
    color: white !important;
}

#sideBarAdminDashboard ul {
    margin-top: 80px;
    list-style-type: none;
    padding: 0px;
}

#sideBarAdminDashboard ul li {
    text-decoration: none;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    transition: box-shadow 0.5s, background-color 0.5s, color 0.5s;
    font-weight: 450;
    font-size: 15px;
}

#sideBarAdminDashboard ul li:hover {
    box-shadow: -5px 5px 10px rgba(0, 0, 0, 0.226);
    background-color: #ffc107;
    color: white;
}

.mt-5 {
    margin-top: 5rem !important;
}
</style>
