<template>

  <div>
    <!-- Sidebar -->
    <div class="sidebar-wrapper">
      <Sidebar id="sidebar" :ruta="ruta" :usuario="authUser" :listPermisos="listRolPermisosByUsuario"> </Sidebar>
    </div>
    <!-- navbar -->
      <Navbar id="navbar" :ruta="ruta" :usuario="authUser"> </Navbar>
    <!-- Main Sidebar Container -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" >
      <transition name="slide-fade" mode="out-in" >
        <router-view :usuario="authUser"></router-view>
      </transition>
    </div>
    <!-- /.content-wrapper -->
      <Footer></Footer>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
</template>

<script>
import Navbar from'./plantilla/Navbar';
import Sidebar from'./plantilla/Sidebar';
import Footer from './plantilla/Footer';

export default {
    props: ['ruta', 'usuario'],
    components:{Navbar, Sidebar, Footer},
    data(){
      return{
        authUser: this.usuario,
        listRolPermisosByUsuario: []
      }
    },
    mounted(){
      //console.log(JSON.parse(localStorage.getItem('listRolPermisosByUsuario')))

      this.listRolPermisosByUsuario = JSON.parse(localStorage.getItem('listRolPermisosByUsuario'))
      EventBus.$on('verifyAuthenticatedUser', data => {
        //console.log(data);
        this.authUser = data;
      })
      EventBus.$on('notifyRolPermisosByUsuario', data => {
        this.listRolPermisosByUsuario = data;
      })
    },
}
</script>

<style>
    @import '../../../public/css/modal.css';
    @import '../../../public/css/tableFixed.css';
    @import '../../../public/css/botonNormal.css';

    .sidebar::-webkit-scrollbar {
        width: 15px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        outline: 1px solid slategrey;
    }

    /* .main-sidebar{
        width: 275px;
    } */
</style>
