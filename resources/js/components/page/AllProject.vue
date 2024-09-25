<template>
    <div class="container">
        <div class="content">
            <h1>Tous les projets</h1>
            <div class="image-gallery">
                <div v-for="project in projects" :key="project.id" class="image-container">
                    <a :href="`/project/${project.id}`" class="project-link">
                        <h3 class="project-title">{{ project.name }}</h3>
                        <img v-if="project.documents[0]" :src="`/${project.documents[0].path}`" alt="Photo du projet" class="project-image">
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            projects: Array
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
    justify-content: center;
}

.image-container {
    margin: 10px;
}

.project-link {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    box-shadow: -52px 44px 58px 0px rgba(0, 0, 0, 0.2);
    background-color: rgba(255, 255, 255, 0.015);
    border-radius: 15px;
    width: 330px;
    height: 370px;
    transition: width 1s, height 1s, background-color 1s;
}

.project-link:hover {
    width: 360px;
    height: 390px;
    background-color: rgba(255, 255, 255, 0.075);
}

.project-title {
    color: #ffc107;
    margin-bottom: 10px;
}

.project-image {
    width: 300px;
    height: 300px;
    object-fit: cover;
}
</style>
