<template>
    <div class="modal" tabindex="-1" id="creationExperienceModal">
        <div class="d-flex justify-content-center modal-dialog modal-lg" style="margin-top: 100px; line-height: 30px; ">
            <div class="m-2 modal-content">
                <div class="modal-header pb-0 mb-0">
                        <h1 style="border-radius: 5px 5px 0px 0px; padding: 5px;">Créer une experience</h1>
                        <button type="button" data-bs-dismiss="modal" class="btn-close float-end" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="line-height: 40px;">
                    <div>
                        <div class="form-group">
                            <label for="nom">Nom</label>
                            <input id="nom" type="text" class="form-control" v-model="experience.name">
                        </div>
                        <div class="form-group">
                            <label for="desc">Description de l'experience</label>
                            <trumbowyg class="form-control" id="desc" v-model="experience.desc"></trumbowyg>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="mb-5 mt-3">
                                <button class="btn btn-success" data-bs-dismiss="modal" @click="save(experience, '/gestion/experience/save')">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Trumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            experience: {
                name: "",
                desc: "",
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
                }
            }),
        }
    },
    components: {
        Trumbowyg,
    },

    methods: {

        async save(data, link) {

            if (data.name != '' || data.desc != '') {
                Swal.fire({
                    title: "Sauvegarder ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let response = await axios.post(baseurl + link, { data: data }, 'POST');
                        response = response.data
                        console.log(response);
                        if (response.statut == 'ok') {
                            this.Toast.fire(response.message, '', 'success');
                        }
                        else {
                            this.Toast.fire(response.message, '', 'error');
                        }
                    }



                });

            }
            else {
                this.Toast.fire("Veuillez remplir tous les champs", '', 'error');
            }
        },

    }
}

</script>

<style scoped>

.modal-content{
    max-height: 100%;
    background-color: #1d1d20;
}
input{
    background-color: black;
    color: white;
}
</style>
<style>
.trumbowyg-button-pane{
    background: black !important;
}
.trumbowyg-button-pane .trumbowyg-button-group button svg{
    filter: invert(1);
}
.trumbowyg-button-pane .trumbowyg-button-group .trumbowyg-active svg{
    filter: invert(0);
}
</style>
