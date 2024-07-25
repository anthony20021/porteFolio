<template>
    <div class="container">
        <div class="content">
            <h1>{{ project.name }}</h1>
            <div>
                <p v-html="project.desc"></p>
                <a v-if="!!project.path" :href="project.path" target="_blank"><button class="btn btn-warning">Go to gitHub</button></a>
            </div>
            <div class="image-gallery">
                <div v-for="document in project.documents" :key="document.path" class="image-container">
                    <img :src="`/${document.path}`" alt="Photo du projet">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        project: Object
    }
}
</script>

<style scoped>
.container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.content {
    width: 80%;
}

.image-gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 10px;
    margin-top: 20px;
}

.image-container {
    flex: 1 1 calc(33.333% - 20px); 
    display: flex;
    justify-content: center;
    align-items: center;
}

.image-container img {
    max-width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 5px;
}

@media screen and (max-width: 768px) {
    .image-container {
        flex: 1 1 calc(50% - 20px); 
    }
    .image-gallery {
        gap: 5px;
        flex-direction: column;
    }
}
</style>