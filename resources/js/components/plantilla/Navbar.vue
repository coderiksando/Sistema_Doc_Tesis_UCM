<template>

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
        <div class="headtext" v-text="url"></div>
      </li>

    </ul>

    <!-- SEARCH FORM -->


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
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
  <!-- /.navbar -->

</template>

<script>
export default {
  props: ['ruta', 'usuario'],
  data(){
    return{
      fullscreenLoading: false,
      permisos : JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      url : localStorage.getItem('url')
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
    }
  }

}
</script>

<style>

@media only screen and (min-width: 993px) {
    .menuBtn {
        display: none;
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
