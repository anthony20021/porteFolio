<template>
    <div>
        <div class="d-block w-100 shadow mb-3" id="sideBarAdminDashboard">
            <ul class="m-0  d-flex flex-wrap flex-row">
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
            </ul>
        </div>

        <div class="pt-3" style="width: 100%;">
            <!-- Mettre des components -->
            <project-page v-if="components == 1" :projects="projects" ></project-page>
            <code-page v-if="components == 2" :codes="codes" ></code-page>
        </div>

    </div>
</template>
<script>
import ProjectPage from './projects/ProjectPage.vue';
import CodePage from './codes/CodePage.vue';

export default {

    props: {
        codes: Array,
        experiences: Array,
        projects: Array,    
    },

    data() {
        return {
            components:0,
        }
    },

    Components: {
        CodePage,
        ProjectPage,
    },

    methods: {
        loadComponent(component) {
            this.components = component;
        }
    }

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
    color: white
}
</style>
