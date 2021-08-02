<template>

  <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar">
    <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img :src="ruta + '/img/ucm3.png'"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">SGYAD UCM</span>
      </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <template v-if="!usuario.id_files">
            <img :src="ruta + '/img/avatar.png'" class="img-circle elevation-2" :alt="usuario.fullname">
          </template>

          <template v-else>
            <img :src="usuario.file.path" class="img-circle elevation-2" :alt="usuario.fullname" style="height:34px !important;">
          </template>

        </div>
        <div class="info">
          <router-link class="d-block" :to="{name: 'usuarios.ver', params:{id_user: usuario.id_user}}">
              {{usuario.nombres}}
          </router-link>
        </div>
      </div>


      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="container">
          <a href="#" class="btn btn-danger btn-block" @click.prevent="logout" v-loading.fullscreen.lock="fullscreenLoading">
            Cerrar sesión
          </a>
          <template v-if="listRolByUser.length > 1">
          <div style="text-align: center;">
            <a>Seleccionar rol</a>
          </div>
          <a>
            <el-select placeholder="Elegir rol" v-model="rolActivo" @change="cambiarRol">
              <el-option
                  v-for="item in listRolByUser"
                  :key="item.id"
                  :label="item.name"
                  :value="item.name">
              </el-option>
            </el-select>
          </a>
          </template>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
          <!-- PESTAÑAS ADMINISTRACION -->
          <li class="nav-item-sidebar has-treeview">
            <template v-if="listPermisos.includes('dashboard.index')">
              <router-link class="nav-link" :to="'/dashboard'">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Inicio
                </p>
              </router-link>
            </template>
          </li>
          <template  v-if="listPermisos.includes('usuarios.index', 'roles.index', 'permisos.index')">
            <li class="nav-header">CONTROL DE USUARIOS</li>
              <template v-if="listPermisos.includes('usuarios.index')">
                <li class="nav-item-sidebar">
                  <router-link class="nav-link" :to="'/usuarios'">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Usuarios
                    </p>
                  </router-link>
                </li>
              </template>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('roles.index')">
                <router-link class="nav-link" :to="'/roles'">
                  <i class="nav-icon fas fa-user-tag"></i>
                  <p>
                    Roles
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('registrar.tesis')">
                <router-link class="nav-link" :to="'/registrodetesis'">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>
                    Registrar tesis finalizadas
                    </p>
                </router-link>
            </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('index.tesisfinal')">
                <router-link class="nav-link" :to="'/indextesisfinalizada'">
                    <i class="nav-icon fas fa-pencil-alt"></i>
                    <p>
                    Edición de tesis final
                    </p>
                </router-link>
            </template>
            </li>
          </template>
          <template  v-if="listPermisos.includes('escuelas.index', 'areatesis.index')">
            <li class="nav-header">CONTROL DE ESCUELAS</li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('escuelas.index')">
                <router-link class="nav-link" :to="'/escuelas'">
                  <i class="nav-icon fas fa-school"></i>
                  <p>
                    Escuelas
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('areatesis.index')">
                <router-link class="nav-link" :to="'/areatesis'">
                  <i class="nav-icon fas fa-microscope"></i>
                  <p>
                    Areas de tesis
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('vinculacion.index')">
                <router-link class="nav-link" :to="'/vinculacion'">
                  <i class="nav-icon fas fa-hands-helping"></i>
                  <p>
                    Vinculaciones
                  </p>
                </router-link>
              </template>
            </li>
          </template>
          <template  v-if="listPermisos.includes('parametros.index')">
            <li class="nav-header">CONTROL DE SISTEMA</li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('parametros.index')">
                <router-link class="nav-link" :to="'/parametros'">
                  <i class="nav-icon fas fa-cog"></i>
                  <p>
                    Parámetros
                  </p>
                </router-link>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('reportes.logs')">
                <router-link class="nav-link" :to="'/reportes/logs'">
                  <i class="nav-icon fas fa-database"></i>
                  <p>
                    Registros
                  </p>
                </router-link>
              </template>
            </li>

          </template>
          <!-- VISTA PARA ADMINISTRACION DE REPORTES -->
          <template  v-if="listPermisos.includes('reportes.reportefit') && rolActivo == 'Coordinador'">
            <li class="nav-header">REPORTES</li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('reportes.reportefit')">
                <router-link class="nav-link" :to="'/reportes'">
                  <i class="nav-icon fas fa-database"></i>
                  <p>
                    Reportes Tesis
                  </p>
                </router-link>
              </template>
            </li>
          </template>
          <!-- VISTA PARA ADMINISTRACION DE DOCUMENTOS -->
          <template  v-if="listPermisos.includes('actadefensa.index') && (rolActivo == 'Director' || rolActivo == 'Secretaria' )">
            <li class="nav-header">CONTROL DE DOCUMENTOS</li>
            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('actadefensa.index')">
                <router-link class="nav-link" :to="'/actadefensa'">
                  <i class="nav-icon fas fa-user-check"></i>
                  <p>
                    Acta de defensa y nota
                  </p>
                </router-link>
              </template>
            </li>
          </template>
          <template  v-if="listPermisos.includes('documentos.index')">

            <li class="nav-item-sidebar">
              <template v-if="listPermisos.includes('documentos.index') && (rolActivo == 'Director' || rolActivo == 'Secretaria' )">
                <router-link class="nav-link" :to="'/documentos'">
                  <i class="nav-icon far fa-id-card"></i>
                  <p>
                    Documentos alumnos
                  </p>
                </router-link>
              </template>
            </li>
          </template>
          <!-- PESTAÑAS PARA EL ALUMNO -->
          <template  v-if="listPermisos.includes('tesis.fit')">
            <li class="nav-header">SEGUIMIENTO DE TESIS</li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('tesis.fit')">
                  <router-link class="nav-link" :to="'/tesis'">
                    <i class="nav-icon far fa-edit"></i>
                    <p>
                      Inscribir/Revisa FID
                    </p>
                  </router-link>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('avances.index')">
                  <router-link class="nav-link" :to="'/avances'">
                    <i class="nav-icon fas fa-file-upload"></i>
                    <p>
                      Avances de tesis
                    </p>
                  </router-link>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('bitacoras.index')">
                  <router-link class="nav-link" :to="'/bitacoras'">
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>
                      Bitácoras
                    </p>
                  </router-link>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('comisiones.index')">
                  <router-link class="nav-link" :to="'/comisiones'">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Comisiones
                    </p>
                  </router-link>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('vinculacion.index')">
                  <router-link class="nav-link" :to="'/vinculacion'">
                    <i class="nav-icon fas fa-hands-helping"></i>
                    <p>
                        Vinculaciones
                    </p>
                  </router-link>
                </template>
              </li>
          </template>

          <template  v-if="listPermisos.includes('notaspendientes.index')">
            <li class="nav-header">SOLICITUD NOTA PENDIENTE</li>
              <li class="nav-item-sidebar">
                <template v-if="listPermisos.includes('notaspendientes.index')">
                  <router-link class="nav-link" :to="'/notaspendientes'">
                    <i class="nav-icon fas fa-calendar-day"></i>
                    <p>
                      Nota pendiente
                    </p>
                  </router-link>
                </template>
              </li>
          </template>

          <template  v-if="listPermisos.includes('sitios.interes')">
            <li class="nav-header">SITIOS DE INTERES</li>
            <li class="nav-item-sidebar">
              <a href="http://www.sibib.ucm.cl/" target="_blank" class="nav-link">
                <i class="nav-icon fas fa-book-reader"></i>
                <p>Sibib UCM</p>
              </a>
            </li>
            <li class="nav-item-sidebar">
              <a href="https://scholar.google.com//" target="_blank" class="nav-link">
                <i class="nav-icon fas fa-book-reader"></i>
                <p>Google Scholar</p>
              </a>
            </li>
          </template>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
<script>
export default {
  props: ['ruta', 'usuario', 'listPermisos'],
  data(){
    return{
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      fullscreenLoading: false,
      rolActivo : JSON.parse(localStorage.getItem('rolActivo'))
    }
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
    cambiarRol(){
      localStorage.setItem('rolActivo', JSON.stringify(this.rolActivo));
      var url = '/perfil/setRol'
      axios.post(url, {
              'rol' : this.rolActivo
      }).then(response => {
        //   console.log(response.data.rol)
          if(response.data.code == 401){
              console.log('Error')
          }
          if(response.data.code == 200){
            if (this.$route.name != 'dashboard.index') {
              this.$router.push({name: 'dashboard.index'})
            }
          }
      })
    }
  },
}
</script>

<style>

</style>
