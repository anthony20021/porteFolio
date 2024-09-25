<template>
    <div>
        <ContactModal></ContactModal>
        <nav v-if="screenWidth > 900" id="mainNavBar" class="navbar navbar-expand-md navbar-light position-fixed col-12 bg-black mb-1 anton-sc-regular"
        style="top:0px; z-index: 1000000; box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.493);">
            <div class="container">
                <a class="navbar-brand d-flex justify-content-around align-items-center" href="/home">
                    <img src="/img/portefolio.png" alt="Laravel" style="filter: invert(1); height: 48px; width: 52px;">
                </a>
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size: 16px">
                    <ul class="navbar-nav me-auto" style="align-items: center">
                        <li class="nav-items">
                            <button @click="openModal()" class="btn btn-warning text-dark">Me contacter</button>
                        </li>
                        <li class="nav-items">
                            <a class="nav-link  text-light" href="/cv">Mon CV</a>
                        </li>
                        <li class="nav-items">
                            <a class="nav-link  text-light" href="/projects">Mes projets</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li v-if="current_user" class="nav-item">
                            <a class="nav-link  text-light" style="cursor: pointer;" @click="logout()">Déconnexion</a>
                        </li>
                        <li v-if="current_user" class="nav-items">
                            <a class="nav-link  text-light" href="/dashboard">Tableau de bord</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div v-else>
            <nav id="mainNavBarTel" class="navbar navbar-expand-md navbar-light  position-fixed col-12"
            style="top:0px; z-index: 1000000;">
                <div class="mx-2" style="display: flex; justify-content: space-between; width: 100%; height: 100%;">
                    <a class="navbar-brand" style="display: flex; align-items: center; justify-content: center;" href="/home">
                        <img src="/img/portefolio.png" alt="Laravel" style="filter: invert(1); height: 48px; width: 48px;">
                    </a>
                    <div class="d-flex justify-content-center" id="burger" style="align-items: center; height: 100%" @click="toggleNavBar">
                        <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 18H21V16H3V18ZM3 13H21V11H3V13ZM3 6V8H21V6H3Z" fill="white"/>
                        </svg>
                    </div>
                </div>
            </nav>

            <div id="navBar" :style="{ right: navBarRight }" class=" position-fixed bg-black">
                <ul class="navbar-nav mt-5">
                    <li class="nav-items">
                        <button @click="openModal()" class="btn btn-warning text-light">Me contacter</button>
                    </li>
                    <li class="nav-items">
                        <a class="nav-link  text-light" href="/cv">Mon CV</a>
                    </li>
                    <li class="nav-items">
                        <a class="nav-link  text-light" href="/projects">Mes projets</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li v-if="current_user" class="nav-item">
                        <a class="nav-link  text-light" style="cursor: pointer;" @click="logout()">Déconnexion</a>
                    </li>
                    <li v-if="current_user" class="nav-items">
                        <a class="nav-link  text-light" href="/dashboard">Tableau de bord</a>
                    </li>
                </ul>
            </div>
            <div style="position: fixed; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.493); z-index: 999; top: 0px;" @click="toggleNavBar" v-if="navBarRight !== '-200px'">
            </div>
        </div>
    </div>
</template>

<script>

import ContactModal from './ContactModal.vue';

export default {
    data() {
        return {
            screenWidth: window.innerWidth,
            navBarRight: '-200px',
        };
    },

    props:{
        current_user: Object
    },

    components: {
        ContactModal,
    },

    methods: {
        updateScreenWidth() {
            this.screenWidth = window.innerWidth;
        },

        openModal() {
            $('#contactModal').modal('show')
        },

        async logout() {
            window.location.href = baseurl + '/logout';
        },

        toggleNavBar() {
            this.navBarRight = this.navBarRight === '0px' ? '-200px' : '0px';
        }
    },

    mounted() {
        window.addEventListener('resize', this.updateScreenWidth);

    },

    beforeDestroy() {
        window.removeEventListener('resize', this.updateScreenWidth);
    }
};
</script>

<style scoped>
    #navBar{
        transition: right 0.6s;
        position: fixed;
        width: 200px;
        top: 0px;
        height: 100%;
        z-index: 1000001;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        box-shadow: -10px 5px 5px rgba(0, 0, 0, 0.267);
    }
</style>
