<template>
    <div class="container-fluid"> 
        <photo-modal :table="'project'" :data="project"></photo-modal>
        <create-project></create-project>
        <h1>Gestion des projects</h1>
        <button class="btn btn-warning" @click="openModalCreateProject()" >Créer un projet</button>
        <table class="table table-bordered table-responsive">
            <thead class="table-dark table-sm">
                <tr>
                    <th class="align-middle text-center text-light sortable" @click.left="sort_column_by('name')">
                        Nom du projet
                    </th>
                    <th class="align-middle text-center text-light">
                        Description
                    </th>
                    <th class="align-middle text-center text-light">
                        Lien
                    </th>
                    <th class="align-middle text-center text-light sortable">
                        A la une
                    </th>
                    <th class="align-middle text-center text-light">
                        Photos
                    </th>
                    <th class="align-middle text-center text-light">
                        Actions
                    </th>
                </tr>

            </thead>
            <tbody v-if="projects.length > 0">
                <tr v-for="project in filtered_project">
                    <td class="aligne-middle">
                        <input type="text" class="form-control" v-model="project.name">
                    </td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <div v-show="!project.show_description" v-html="project.desc" id="formatTexte"></div>
                            <div v-show="project.show_description">
                                <trumbowyg id="desc" class="form-control" v-model="project.desc"></trumbowyg>
                            </div>
                            <button class="btn btn-primary btn-sm col-auto" @click="changeStatut(project)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" viewBox="0 0 16 16">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td class="aligne-middle text-center">
                        <div class="form-check form-switch">
                            <input class="form-control" type="text" v-model="project.path">
                        </div>
                    </td>
                    <td class="aligne-middle text-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" v-model="project.front_page">
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-secondary" @click="openModalPhoto(project)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                            <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z"/>
                            </svg>
                        </button>
                    </td>
                    <td class="aligne-middle">
                        <button class="btn btn-success" @click="save(project, '/gestion/project/save')">Enregistrer</button>
                        <button class="btn btn-danger" @click="suppr(project, '/gestion/project')">Supprimer</button>
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
import PhotoModal from '../modal/PhotoModal.vue';
import CreateProject from './CreateProject.vue';


export default {
    data() {
        return {
            project:{},
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
                }
            }),
        }
    },

    props: {
        projects: Array,
    },

    components: {
        Trumbowyg,
        CreateProject,
        PhotoModal,
    },

    methods: {

        changeStatut(project) {
            const index = this.projects.findIndex(p => p.id == project.id);
            this.projects[index].show_description = !this.projects[index].show_description;
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

        openModalCreateProject() {
            $('#creationProjectModal').modal('show')

        },

        openModalPhoto(project) {
            this.project = project
            $('#photoModal').modal('show')
        },

        async save(data, link) {

            if (data.name != '' || data.desc != '' || data.path != '') {
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

                        data.documents.forEach(async photo => {
                            let result = await axios.delete(baseurl + '/gestion/photo', {data: {data:{id: photo.id,table: 'project'}}},'DELETE');
                            result = result.data;
                            if(result.statut == 'error'){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: result.message,
                                });
                                return;
                            }
                        });

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
        filtered_project() {
            if (this.projects.length != 0) {
                let projects = this.projects.filter((row) => {
                    return row;
                })
                return _.orderBy(
                    projects,
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
