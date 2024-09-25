<template>
    <div class="modal" tabindex="-1" id="contactModal" style="z-index: 11111111111111;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header pb-0 mb-0">
                    <h1 style="border-radius: 5px 5px 0px 0px; padding: 5px;">Me contacter</h1>
                    <button type="button" data-bs-dismiss="modal" class="btn-close float-end btn-close-white" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="line-height: 40px;">
                    <div>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input id="nom" type="text" class="form-control" v-model="contact.name">
                        </div>
                        <div class="form-group">
                            <label for="nom">Prenom</label>
                            <input id="nom" type="text" class="form-control" v-model="contact.firstname">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email" class="form-control" v-model="contact.email">
                        </div>
                        <div class="form-group">
                            <label for="sujet">Sujet</label>
                            <input id="sujet" type="text" class="form-control" v-model="contact.sujet">
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control" v-model="contact.message"></textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="mb-5 mt-3">
                                <button class="btn btn-success" data-bs-dismiss="modal" @click="save(contact, '/contact')">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { load } from 'recaptcha-v3'

import Trumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';
import Swal from 'sweetalert2';

export default {
    components: {
        Trumbowyg,
    },

    data() {
        return {
            contact: {
                name: '',
                firstname: '',
                email: '',
                sujet: '',
                message: '',
            },
            Toast : Swal.mixin({
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
            recaptchaToken: null,
        }
    },

    methods: {
        async recaptcha() {
            const recaptcha = await load('6LdCdRcqAAAAACB7olJxSX87njo9Bie2exQZv0Vi')
            this.recaptchaToken = await recaptcha.execute();
        },
        async save(contact, url) {
            await this.recaptcha();
            if(!this.recaptchaToken){
                this.Toast.fire('Erreur de captcha', '', 'error');
                return
            }
            else{
                contact.captcha = this.recaptchaToken;
            }
            try {
                const result = await axios.post(baseurl + url, contact);
                if (result.data.statut == 'ok') {
                    this.Toast.fire(result.data.message, '', 'success');
                } else {
                    this.Toast.fire(result.data.message, '', 'error');
                }
            } catch (error) {
                this.Toast.fire(error.message, '', 'error');
            }
        }
    },
}
</script>

<style scoped>
.modal-content{
    max-height: 100%;
    background-color: #1d1d20;
    color: white;
}
input{
    background-color: black;
    color: white;
}
textarea{
    background-color: black;
    color: white;
}
</style>