<template>
    <div class="container">
        <div class="content">
            <h1>{{ cv.name }}</h1>
            <div>
                <p style="font-family:sans-serif;" v-html="cv.desc"></p>
            </div>
            <div class="image-gallery">
                <div v-for="document in cv.documents" :key="document.path" class="image-container">
                    <img :src="`/${document.path}`" alt="Photo du projet">
                </div>
            </div>
            <button class="btn btn-warning" v-if="!!cv.file" @click="download(cv.file.path)">Télécharger</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        cv: Object
    },

    methods: {
        download(path){
            let a = document.createElement('a');
            a.href = path;
            a.download = path;
            a.click();
            a.remove();
        },
    }
}
</script>

<style scoped>
.container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
</style>