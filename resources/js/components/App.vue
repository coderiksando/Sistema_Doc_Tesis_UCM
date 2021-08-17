<template>

  <div>
    <!-- Main Sidebar Container -->
    <Sidebar id="sidebar" class="sidebar" :ruta="ruta" :usuario="authUser" :listPermisos="listRolPermisosByUsuario"> </Sidebar>
    <!-- navbar -->
    <Navbar class="navbar" :ruta="ruta"> </Navbar>
    <!-- Content Wrapper. Contains page content -->
    <div style="padding-top:50px;" class="content-wrapper" >
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


    .sidebar{
        /* overflow-x: hidden !important; */
        min-height: 85vh;
        max-height: 85vh;
        padding-left: 0rem;
        padding-right: 0rem;
        position: fixed !important;
    }
    .sidebar::-webkit-scrollbar {
        width: 15px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: darkgrey;
        outline: 1px solid slategrey;
    }
    .nav-item-sidebar {
        width: 235px;
    }
    .navbar{
      position: fixed;
      width: 100%;
    }
</style>
