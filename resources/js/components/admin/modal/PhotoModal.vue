<template>
    <div class="modal" tabindex="-1" id="photoModal">
        <div class="d-flex justify-content-center modal-dialog modal-lg" style="margin-top: 100px; line-height: 30px; ">
            <div class="m-2 modal-content">
                <div class="modal-header pb-0 mb-0">
                        <h1 style="border-radius: 5px 5px 0px 0px; padding: 5px;">Photo</h1>
                        <button type="button" data-bs-dismiss="modal" class="btn-close float-end" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="line-height: 40px;">
                    <div>
                        <div>
                            <input type="file" class="form-control" @change="checkPhoto($event)">
                            <div v-if="previewPhotoMetier">
                                <img :src="previewPhotoMetier" alt="Prévisualisation de la photo du métier"
                                    class="preview-img">
                            </div>
                            <button class="btn btn-success" data-bs-dismiss="modal" @click="uploadPhoto()">Valider</button>
                        </div>
                    </div>
                    <div>
                        <div v-for="photo in data.documents">
                            <img :src="photo.path" alt="Prévisualisation de la photo du metier" class="preview-img">
                            <button class="btn btn-danger" @click="deletePhoto(photo.id)">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from 'sweetalert2';




export default {
    data() {
        return {
            previewPhotoMetier: null,
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

    props:{
        table : String,
        data : Object,
    },

    methods:{
            checkPhoto(event){
                const file = event.target.files[0];
                this.photo = file;
                if(file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewPhotoMetier = e.target.result;
                    };
                    reader.readAsDataURL(file);
                    }
            },

            async uploadPhoto(){
                if(this.photo == null) {
                    console.log('Aucune photo sélectionnée');
                }
                let formData = new FormData();
                formData.append('file', this.photo);
                formData.append('id', this.data.id)
                formData.append('name', this.data.name)
                formData.append('table', this.table)

                try{
                    let response = await axios.post(baseurl + '/gestion/photo', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    response = response.data;

                    if(response.statut == 'ok'){
                        Swal.fire({
                            title: 'Ajouté !',
                            text: 'Votre image a bien été sauvegarder',
                            icon: 'success'
                        });
                    }
                    else if(response.statut == 'error'){
                        Swal.fire({
                            title: 'Erreur',
                            text: response.error,
                            icon: 'error'
                        });
                    }
                }
                catch(error){
                    console.log(error);
                }
            },

            async deletePhoto(id){

                Swal.fire({
                    title: "Supprimer cette photo ?",
                    text: "Cette action est irréversible",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler"
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        let result = await axios.delete(baseurl + '/gestion/photo', {data: {data:{id: id,table: this.table}}},'DELETE');
                        result = result.data;
                        if(result.statut == 'ok'){
                            this.Toast.fire({
                                icon: 'success',
                                title: 'Suppression reussie'
                            });
                        }
                        else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: result.message,
                            });
                        }
                    }
                })
            }
        },

    }
</script>

<style scoped>

.modal-content{
    max-height: 100%;
    background-color: #1d1d20;
}

.preview-img{
    max-width: 300px;
    max-height: 300px;
    margin-top: 10px;
}

.modal-content{
    max-width: 500px;
    max-height: 675px;
}
input{
    background-color: black;
    color: white;
}
</style>
