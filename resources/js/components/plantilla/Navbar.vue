<template>
  <div>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav ml-left">

        <li class="nav-item">
          <a class="nav-link menuBtn" data-widget="pushmenu" data-slide="true" href="" role="button">
            <i class="fa fa-bars"></i>
          </a>
        </li>
        <li class="nav-item">
          <h4 style="color: white" v-text="url"></h4>
        </li>

      </ul>

      <!-- SEARCH FORM -->


      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <li class="nav-item">
          <a class="btn-lg btn btn-outline-light" style="color: white" @click.prevent="showCalendar">
              <i class="far fa-calendar-alt"></i>
          </a>
        </li>
        <li class="nav-item mr-3 mt-1">
          <div class="container">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i>
              <span class="btn-back"> Regresar</span>
          </a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="btn-lg btn btn-outline-light" data-toggle="dropdown" href="#">
            <i class="fa fa-user-circle"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

            <div class="container">
              <div class="dropdown-divider"></div>
                <div class="flex p-2">
                  <div class="d-flex justify-content-center">
                    <template v-if="!usuario.id_files">
                      <img :src="ruta + '/img/user.jpg'" class="img-circle mx-auto" :alt="usuario.fullname">
                    </template>

                    <template v-else>
                      <img :src="usuario.file.path" class="img-circle elevation-2" :alt="usuario.fullname" style="height:125px; width:125px !important;">
                    </template>

                  </div>
                  <div class="col-sm text-center p-1">
                    <router-link v-if="permisos.includes('usuarios.ver')" class="block" :to="{name: 'usuarios.ver', params:{id_user: usuario.id_user}}">
                        {{usuario.nombres.split(' ')[0] + ' ' + usuario.apellidos.split(' ')[0]}}
                    </router-link>
                    <p style="font-size: 1rem;" v-if="!permisos.includes('usuarios.ver')" class="block">
                        {{usuario.nombres.split(' ')[0] + ' ' + usuario.apellidos.split(' ')[0]}}
                    </p>
                  </div>
                </div>


              <div class="dropdown-divider"></div>
                <div class="col-sm-12 text-center">
                  <a href="#" class="btn btn-danger d-block" @click.prevent="logout" v-loading.fullscreen.lock="fullscreenLoading">
                    Cerrar sesión
                  </a>
                </div>
              <div class="dropdown-divider p-2"></div>
            </div>
          </div>
        </li>
      </ul>
    </nav>

  <template v-if="modalCalendario">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="hideCalendar">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show modalCalendario" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true"  v-on:mousedown.stop>
          <div class="swal2-content" v-loading="!renderCalendar">
            <div class="flex" v-if="renderCalendar">
              <FullCalendar
                :options="calendarOptions" 
                /> 
            </div>

          </div>
        </div>
      </div>
    </template>
  <!-- /.navbar -->

</div>

</template>

<script>
import '@fullcalendar/core/vdom'
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import allLocales from '@fullcalendar/core/locales-all';
import timeGridPlugin from '@fullcalendar/timegrid';

export default {
  props: ['ruta', 'usuario'],
  components: {
    FullCalendar
  },
  data(){
    return{
      fullscreenLoading: false,
      permisos : JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      url : localStorage.getItem('url'),
      eventos: [],
      calendarOptions: {
        plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin],
        locales: allLocales,
        locale: 'es-mx',
        initialView: 'dayGridMonth',
        weekends: true,
        headerToolbar: {
          start: 'botonCerrar',
          center: 'title',
          end: 'dayGridMonth timeGridWeek prev today next'
        },
        customButtons: {
          botonCerrar: {
            text: ' X ',
            click: () => this.hideCalendar()
          }
        },
        dayHeaderContent: function(arg) {
          var DAY_NAMES = ['Lun','Mar','Mie','Jue','Vie','Sab','Dom'];
          return DAY_NAMES[arg.date.getDay()];
        },
        events: []
      },

      modalCalendario: false,
      renderCalendar: false,
      
    }
  },
  mounted(){
    EventBus.$on('navegar', url => {
      this.url = url;
    })
  },
  methods:{
    logout(){
      this.fullscreenLoading = true;
      var url='/authenticate/logout'
      axios.post(url).then(response => {
        if(response.data.code == 204){
          window.location.href = '/';
          //location.reload();
          localStorage.clear();
          this.fullscreenLoading = false;
        }

      })
    },
    updateUrl(){
      this.url = localStorage.getItem('url');
      console.log('hola');
    },
    showCalendar(){
      this.caldendarData();
      var self = this;
      this.modalCalendario = true;
      setTimeout( x => {
        self.renderCalendar = true;
      }, 300)
    },
    hideCalendar(){
      this.renderCalendar = false;
      this.modalCalendario = false;
    },
    caldendarData(){
      this.fullscreenLoading = true;
      var url='/administracion/usuario/calendarData'
      axios.post(url, {permisos: this.permisos})
      .then(response => {
        this.calendarOptions.events = response.data;
        this.fullscreenLoading = false;
      })
    }
  }

}
</script>

<style>

.modalCalendario{
  display: flex;
  z-index: 2;
  width: 55% !important;
  min-width: 360px !important;
  height: 90% !important;
  min-height: 360px !important
}

@media only screen and (min-width: 993px) {
    .menuBtn {
        display: none;
    }
}

@media only screen and (max-width: 993px){
  .modalCalendario{
    display: flex;
    z-index: 2;
    width: 55% !important;
    min-width: 360px !important;
    height: 55% !important;
    min-height: 360px !important
  }
  .fc-timeGridWeek-button{
    display: none !important;
  }
  .fc-dayGridMonth-button{
    display: none !important;
  }
}

.headtext{
  height: 100%;
  color: white;
  font-size: 2rem;
}

@media only screen and (max-width: 420px) {
    .btn-back {
        display: none;
    }
}



</style>
