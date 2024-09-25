<template>
    <div class="container-fluid"> 
        <create-cv></create-cv>
        <h1>Gestion CV</h1>
        <button class="btn btn-warning" @click="openModalCreateCode()" >Créer un CV</button>
        <table class="table table-bordered table-responsive">
            <thead class="table-dark table-sm">
                <tr>
                    <th class="align-middle text-center text-light sortable" @click.left="sort_column_by('name')">
                        Nom
                    </th>
                    <th class="align-middle text-center text-light">
                        Description
                    </th>
                    <th class="align-middle text-center text-light sortable">
                        A la une
                    </th>
                    <th class="align-middle text-center text-light">
                        Fichier
                    </th>
                    <th class="align-middle text-center text-light">
                        Actions
                    </th>
                </tr>

            </thead>
            <tbody v-if="cv.length > 0">
                <tr v-for="cv in filtered_cv">
                    <td class="aligne-middle">
                        <input type="text" class="form-control" v-model="cv.name">
                    </td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <div v-show="!cv.show_description" v-html="cv.desc" id="formatTexte"></div>
                            <div v-show="cv.show_description">
                                <trumbowyg id="desc" class="form-control" v-model="cv.desc"></trumbowyg>
                            </div>
                            <button class="btn btn-primary btn-sm col-auto" @click="changeStatut(cv)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td class="aligne-middle text-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" v-model="cv.front_page">
                        </div>
                    </td>
                    <td>
                        <input type="file" class="form-control" @change="checkFile($event)">
                    </td>
                    <td class="aligne-middle">
                        <button class="btn btn-success" @click="save(cv, '/gestion/cv/save')">Enregistrer</button>
                        <button class="btn btn-danger" @click="suppr(cv, '/gestion/cv')">Supprimer</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import Swal from 'sweetalert2';
import Trumbowyg from 'vue-trumbowyg';
import 'trumbowyg/dist/ui/trumbowyg.css';
import CreateCv from './CreateCv.vue';


export default {
    data() {
        return {
            code:{},
            Toast : Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    container: 'toast-perso' // Applique la classe de marge en haut
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
            }),
            file:{},
        }
    },

    props: {
        cv: Array,
    },

    components: {
        Trumbowyg,
        CreateCv,
    },

    methods: {

        checkFile(e) {
            this.file = e.target.files[0];
        },

        changeStatut(experience) {
            const index = this.cv.findIndex(p => p.id == experience.id);
            this.cv[index].show_description = !this.cv[index].show_description;
            this.$forceUpdate();
        },

        sort_column_by(val) {
            if (this.sort == val) {
                this.reverse = !this.reverse;
            } else {
                this.reverse = false;
            }
            this.sort = val;
        },

        openModalCreateCode() {
            $('#creationCvModal').modal('show')

        },


        async save(data, link) {

            if (data.name != '' || data.desc != '' ) {
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
                        let response = await axios.put(baseurl + link, { data: data }, 'PUT');
                        response = response.data;
                        if (response.statut == 'ok') {
                            this.Toast.fire(response.message, '', 'success')
                        }else {
                            this.Toast.fire(response.message, '', 'error');
                        }
                        if(this.file){
                            let file = new FormData();
                            file.append('file', this.file);
                            file.append('id', data.id);
                            let responseFile = await axios.post(baseurl + '/gestion/cv/file', file, {headers: { 'Content-Type': 'multipart/form-data' }}, 'POST');
                            responseFile = responseFile.data;
                            if(responseFile.statut == 'ok'){
                                this.Toast.fire(responseFile.message, '', 'success')
                            }
                            else if(responseFile.statut == 'error'){
                                this.Toast.fire(responseFile.message, '', 'error')
                            }
                        }
                    }
                });
            }
        },

        async suppr(data, link) {

            if (data.id != '') {
                Swal.fire({
                    title: "Supprimer ce projet ?",
                    text: "Cette action est irréversible",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let response = await axios.delete(baseurl + link, {data : {data : { id: data.id }}}, 'DELETE');
                        response = response.data;
                        if (response.statut == 'ok') {
                            this.Toast.fire(response.message, '', 'success');
                        }
                        else {
                            this.Toast.fire(response.message, '', 'error');
                        }
                    }

                });
            }
        },
    },

    computed: {
        filtered_cv() {
            if (this.cv.length != 0) {
                let cv = this.cv.filter((row) => {
                    return row;
                })
                return _.orderBy(
                    cv,
                    this.sort,
                    this.reverse ? "desc" : "asc"
                );
            }
            return [];
        }
    },

}
</script>

<style>

#formatTexte{
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: normal;
    width: 400px;
    height: 30px;
}

</style>
