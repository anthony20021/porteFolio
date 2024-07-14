<template >
    <div class="animate-title" :class="[start ? 'end-position' : 'start-position', 'anton-sc-bold', boucle ? 'boucle-on' : 'boucle-off']" ref="page"  @wheel="handleWheel">
        <div class="text-center" ref="1">
            <h1 style="text-decoration: underline; font-weight: 700;">
                <span style="color: #dddea5;">Gonzalez</span>
                <span style="color: #b973ad;">( ){ </span>
                <span style="color: #b973ad;">return </span>
                <span style="color: #66aac6;">Anthony</span>
                <span style="color: #b973ad;"> }</span>
            </h1>
            <p style="color: #fad76d;">Portfolio</p>
            <button class="btn btn-primary" @click="changeEtatPlus()">Next( )</button>
        </div>
        <div style="margin-top: 2300px;" ref="2">
            <h2 class="mb-5">Mes maîtrises :</h2>
            <div class="logo-container" style="display: flex; justify-content: space-around; width: 100%;">
                <div class="w-100 text-center">
                    <h4 class="mt-3">Front-End</h4>
                    <div class="logo">
                        <img src="/img/html.png">
                        <img src="/img/css.png">
                        <img src="/img/js.png">
                        <img src="/img/vue.png">
                        <img src="/img/react.png">
                    </div>
                </div>
                <div class="w-100 text-center" ref="3">
                    <h4 class="mt-3">Back-End</h4>
                    <div class="logo">
                        <img src="/img/php.png">
                        <img src="/img/laravel.png">
                        <div>
                            <img src="/img/node.png">
                            <p style="color: #77ae64;">Express JS</p>
                        </div>
                    </div>
                </div>
                <div style="max-width: 300px;" class="w-100 text-center" ref="4">
                    <h4 class="mt-3">Bases de données</h4>
                    <div class="logo" style="max-width: 300px;">
                        <img src="/img/mysql.svg">
                        <img src="/img/postgres.png">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" @click="changeEtatPlus()">Next( )</button>
            <button class="btn btn-primary" @click="changeEtatMoins()">Previous( )</button>
        </div>
        <div style="margin-top: 1800px;" ref="3">
            <h2 class="mb-5">Mes expériences :</h2>
            <div>
    
            </div>
            <button class="btn btn-primary" @click="changeEtatPlus()">Next( )</button>
            <button class="btn btn-primary" @click="changeEtatMoins()">Previous( )</button>
        </div>
        <div style="margin-top: 2000px;" ref="4">
            <h2 class="mb-5">Mes projets :</h2>
            <div>
                <div v-for="project in projects">
                    <h4>{{project.name}}</h4>
                    <div v-for="document in project.documents">
                        <img :src="document.path" alt="Photo du projet">
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" @click="changeEtatPlus()">Next( )</button>
            <button class="btn btn-primary" @click="changeEtatMoins()">Previous( )</button>
        </div>
        <div style="margin-top: 2000px;" ref="5">
            <h2 class="mb-5">Quelques bouts de code (c'est gratuit) :</h2>
            <div>
    
            </div>
            <button class="btn btn-primary" @click="changeEtatPlus()">Next( )</button>
            <button class="btn btn-primary" @click="changeEtatMoins()">Previous( )</button>
        </div>
        <div style="position: fixed; right: 0; bottom: 0;">
            <p class="m-0">Site créer avec Laravel, vueJs et postgres</p>
        </div>
    </div>
</template>


<script>
    export default {
        data() {
            return {
                start: false,
                boucle: false,
                position: 4300,
                page:2,
            }
        },

        props: {
            projects: Array,
        },

        mounted() {
            setTimeout(() => {
                this.start = true;
            }, 10);
            setTimeout(() => {
                this.invertBoucle();
            }, 7020);
        },
        methods: {
            changeEtatPlus() {
                this.position -= 2000;
                this.position -= this.$refs[this.page].getBoundingClientRect().height;
                console.log(this.$refs[this.page].getBoundingClientRect().height)
                this.page += 1;
                this.$refs.page.style.top = this.position + 'px';
            },
            handleWheel(event) {
                if (event.deltaY > 0) {
                    if(this.page<6){
                        this.changeEtatPlus();
                    }
                } else {
                    if(this.page>2){
                        this.changeEtatMoins();
                    }
                }
            },
            changeEtatMoins() {
                this.position += 2000;
                this.page -= 1;
                this.position += this.$refs[this.page].getBoundingClientRect().height;
                this.$refs.page.style.top = this.position + 'px';
            },
            invertBoucle() {
                this.boucle = !this.boucle;
                setTimeout(() => {
                    this.invertBoucle();
                }, 3000);
            }
        }
    }
</script>

<style scoped>
    .logo-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .logo {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
        max-width: 600px;
    }
    .animate-title {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: fixed;
        width: 100%;
        height: 100%;
        color: #ffbf00;
        font-weight: 900 !important;
        transition: top 3s, right 3s;
        text-shadow: rgba(0, 0, 0, 0.158) 2px 5px;
    }
    .animate-title h1 {
        transition: font-size 7s, color 6s;
        overflow: hidden;
        border-right: .15em solid orange;
        white-space: nowrap;
        margin: 0 auto;
        letter-spacing: .15em;
        animation: 
        typing 5.5s steps(40, end),
        blink-caret .75s step-end infinite;
    }
    .end-position h1 {
        font-size: 57px;
    }
    .end-position h1:hover {
        font-size: 50px;
    }
    .start-position h1 {
        font-size: 5px;
    }
    .start-position {
        top: 5000px;
    }
    .boucle-on {
        right: 10px;
    }
    .boucle-off {
        right: -10px;
    }
    .end-position {
        top: 4400px
    }
    img {
        width: auto;
        height: 128px;
    }

    @keyframes typing {
    from { width: 0 }
    to { width: 100% }
    }

    @keyframes blink-caret {
    from, to { border-color: transparent }
    50% { border-color: orange; }
    }

    @media screen and (max-width: 980px) {
        .end-position h1 {
            font-size: 23px;
        }
        .logo-container {
            flex-direction: column;
        }
        img {
            height: 64px;
            width: auto;
        }
        .end-position {
            top: 4300px;
        }
    }
</style>

