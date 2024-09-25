<template>
    <div class="animate-title" :class="[start ? 'end-position' : 'start-position', 'anton-sc-bold', boucle ? 'boucle-on' : 'boucle-off']" ref="page" @wheel="handleWheel">
      <div v-for="(section, index) in sections" :key="index" class="section" :ref="'section' + index">
        <div class="section-content"  :class="(index === 4 || index === 3 || index === 5) ? 'w-100' : ''">
          <!-- Section 1: Accueil -->
          <div v-if="index === 0">
            <h1 style="text-decoration: underline; font-weight: 700;" class="title-text">
              <span style="color: #dddea5;">Gonzalez</span>
              <span style="color: #b973ad;">( ){ </span>
              <span style="color: #b973ad;">return </span>
              <span style="color: #66aac6;">Anthony</span>
              <span style="color: #b973ad;"> }</span>
            </h1>
            <p>full stack web developer</p>
            <p style="color: #fad76d;">Portfolio</p>
          </div>
  
          <!-- Section 2: Maîtrises -->
          <div v-if="index === 1" class="mb-1">
            <h2 class="mb-1">Mes maîtrises :</h2>
            <div class="logo-container">
              <div class="w-100 text-center">
                <h4 class="mt-3">Front-End</h4>
                <div class="logo">
                  <img src="/img/html.png" alt="HTML">
                  <img src="/img/css.png" alt="CSS">
                  <img src="/img/js.png" alt="JavaScript">
                  <img src="/img/vue.png" alt="Vue.js">
                  <img src="/img/react.png" alt="React">
                </div>
              </div>
              <div class="w-100 text-center">
                <h4 class="mt-3">Back-End</h4>
                <div class="logo">
                  <img src="/img/php.png" alt="PHP">
                  <img src="/img/laravel.png" alt="Laravel">
                  <div>
                    <img src="/img/node.png" alt="Node.js">
                    <p style="color: #77ae64;">Express JS</p>
                  </div>
                </div>
              </div>
              <div style="max-width: 300px;" class="w-100 text-center">
                <h4 class="mt-3">Bases de données</h4>
                <div class="logo" style="max-width: 300px;">
                  <img src="/img/mysql.svg" alt="MySQL">
                  <img src="/img/postgres.png" alt="PostgreSQL">
                </div>
              </div>
            </div>
          </div>
  
          <!-- Section 3: Expériences -->
          <div v-if="index === 2">
            <h2 class="mb-5">Mes expériences :</h2>
            <div v-for="experience in experiences" class="d-flex mb-1" style="align-items: center; justify-content: space-between; width: 100%;">
              <h4>{{experience.name}}</h4>
              <div style="max-width: 70%; max-height: 256px; overflow: scroll; border-radius: 20px;" class="me-3">
                <p class="p-2" style="background-color: black; font-family:sans-serif;" v-html="experience.desc"></p>
              </div>
            </div>
          </div>
  
          <!-- Section 4: Projets -->
          <div v-if="index === 3">
            <h2 class="mb-5">Mes projets :</h2>
            <div v-for="project in projects" class="d-flex" style="align-items: center; justify-content: space-around; width: 100%;">
              <h4>{{project.name}}</h4>
              <div v-if="document.type === 'png' || document.type === 'jpg' || document.type === 'jpeg' || document.type === 'svg' || document.type === 'webp'" v-for="document in project.documents">
                <img :src="document.path" alt="Photo du projet">
              </div>
              <a :href="`/project/${project.id}`">
                <button  class="btn btn-warning">Voir</button>
              </a>
            </div>
          </div>
  
          <!-- Section 5: Code Snippets -->
          <div v-if="index === 4" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <h2 class="mb-5 text-start">Quelques bouts de code (c'est gratuit) :</h2>
            <div v-for="code in codes" class="flex-responsive mb-1 p-1" style="align-items: center; justify-content: space-evenly; width: 90%; overflow: scroll; background-color: black; border-radius: 20px;">
              <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; max-width: 40%;">
                <h4>{{code.name}}</h4>
                <div style="font-family:sans-serif" v-html="code.desc">
                </div>
              </div>
              <img v-for="document in code.documents" :src="document.path" alt="Photo du projet" class="image">
              <button class="btn btn-warning" v-if="!!code.file" @click="download(code.file.path)">Télécharger</button>
            </div>
          </div>


        </div>
        <div class="navigation-buttons">
          <button v-if="index > 0" class="btn btn-primary" @click="changeEtatMoins">Previous( )</button>
          <button v-if="index < sections.length - 1" class="btn btn-primary" @click="changeEtatPlus">Next( )</button>
        </div>
      </div>
      <div style="position: fixed; right: 0; bottom: 0;">
        <p class="m-0 site">Site créé avec Laravel, Vue.js et PostgreSQL</p>
      </div>
    </div>
  </template>

  <script>

  export default {
    data() {
      return {
        start: false,
        boucle: false,
        currentSection: 0,
        sections: [
          { name: 'Accueil' },
          { name: 'Maîtrises' },
          { name: 'Expériences' },
          { name: 'Projets' },
          { name: 'Code Snippets' },
        ]
      }
    },
    props: {
      projects: Array,
      codes: Array,
      experiences : Array,
      cv : Array,
    },

    mounted() {
      this.initializeAnimation();
      this.setupSections();
    },
    methods: {
      initializeAnimation() {
        setTimeout(() => {
          this.start = true;
        }, 10);
        setTimeout(() => {
          this.invertBoucle();
        }, 7020);
      },

      download(path){
        let a = document.createElement('a');
        a.href = path;
        a.download = path;
        a.click();
        a.remove();
      },

      setupSections() {
        this.$nextTick(() => {
          this.sections.forEach((_, index) => {
            const sectionEl = this.$refs['section' + index][0];
            sectionEl.style.height = '100vh';
            sectionEl.style.display = 'flex';
            sectionEl.style.flexDirection = 'column';
            sectionEl.style.justifyContent = 'center';
          });
        });
      },
      changeEtatPlus() {
        if (this.currentSection < this.sections.length - 1) {
          this.currentSection++;
          this.scrollToSection();
        }
      },
      changeEtatMoins() {
        if (this.currentSection > 0) {
          this.currentSection--;
          this.scrollToSection();
        }
      },
      scrollToSection() {
        const sectionEl = this.$refs['section' + this.currentSection][0];
        sectionEl.scrollIntoView({ behavior: 'smooth' });
      },
      handleWheel(event) {
        if (event.deltaY > 0) {
          this.changeEtatPlus();
        } else {
          this.changeEtatMoins();
        }
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
  .animate-title {
    position: fixed;
    width: 100%;
    height: 100vh;
    overflow-y: auto;
    scroll-snap-type: y mandatory;
    color: #ffbf00;
    font-weight: 900 !important;
    transition: top 3s, right 9s;
    text-shadow: rgba(0, 0, 0, 0.158) 2px 5px;
  }
  
  .section {
    scroll-snap-align: center;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  .section-content {
    width: auto;
    max-height: 80vh;
    overflow-y: auto;
  }
  
  .navigation-buttons {
    margin-top: 20px;
  }
  
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
  
  .animate-title h1 {
    transition: font-size 5s, color 6s;
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
    top: 600px;
  }
  
  .boucle-on {
    right: -10px;
  }
  
  .boucle-off {
    right: 0px;
  }

  .title-text{
    display: flex;
  }
  
  .end-position {
    top: 10px;
  }
  .flex-responsive{
    display: flex;
    flex-direction:row;
    align-items: center;
    justify-content: center;
  }
  img {
    width: auto;
    height: 128px;
  }
  .image{
    max-width: 50%;
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
      font-size: 45px;
    }
    .flex-responsive{
      flex-direction: column;
    }
    .logo-container {
      flex-direction: column;
    }
    img {
      height: 64px;
      width: auto;
    }
    .image{
      max-width: 100%;
    }
    .site{
      font-size: 8px;
    }
    .title-text{
      flex-direction: column;
    }
  }
  </style>