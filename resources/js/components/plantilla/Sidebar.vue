<template>

  <aside class="main-sidebar sidebar-dark-navy elevation-4">
    <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img :src="ruta + '/sigad/img/ucm3.png'"
            alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">SIGAD UCM</span>
      </a>
    <!-- Sidebar -->
    <div class="sidebar text-sm p-0">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="container">
          <div style="text-align: center;">
            <a v-if="listRolByUser.length > 1">Seleccionar rol</a>
            <a v-if="listRolByUser.length == 1">Rol: </a>
            <el-input
                style="width: 80%;"
                v-if="listRolByUser.length == 1"
                v-model="listRolByUser[0].name"
                :disabled="true">
            </el-input>
          </div>
          <a>
            <el-select v-if="listRolByUser.length > 1" placeholder="Elegir rol" v-model="rolActivo" @change="cambiarRol(true)">
              <el-option
                  v-for="item in listRolByUser"
                  :key="item.id"
                  :label="item.name"
                  :value="item.name">
              </el-option>
            </el-select>
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
          <!-- PESTAÑAS ADMINISTRACION -->
          <li class="nav-item-sidebar has-treeview">
            <template v-if="permisos.includes('dashboard.index')">
              <a href="#" class="nav-link" @click.prevent="navegar('/dashboard')">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Inicio
                </p>
              </a>
            </template>
          </li>
          <template  v-if="permisos.includes('usuarios.index') || permisos.includes('roles.index') || permisos.includes('permisos.index') || permisos.includes('registrar.tesis') || permisos.includes('index.tesisfinal')">
            <li class="nav-header">CONTROL DE USUARIOS</li>
              <template v-if="permisos.includes('usuarios.index')">
                <li class="nav-item-sidebar">
                  <a href="#" class="nav-link" @click.prevent="navegar('/usuarios')">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Usuarios
                    </p>
                  </a>
                </li>
              </template>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('roles.index')">
                <a href="#" class="nav-link" @click.prevent="navegar('/roles')">
                  <i class="nav-icon fas fa-user-tag"></i>
                  <p>
                    Roles
                  </p>
                </a>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('registrar.tesis')">
                <a href="#" class="nav-link" @click.prevent="navegar('/registrodetesis')">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>
                    Registrar documento final
                    </p>
                </a>
            </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('index.tesisfinal')">
                <a href="#" class="nav-link" @click.prevent="navegar('/indextesisfinalizada')">
                    <i class="nav-icon fas fa-pencil-alt"></i>
                    <p>
                    Edición de documento final
                    </p>
                </a>
            </template>
            </li>
          </template>
          <template  v-if="permisos.includes('escuelas.index') || permisos.includes('areatesis.index')">
            <li class="nav-header">CONTROL DE ESCUELAS</li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('escuelas.index')">
                <a href="#" class="nav-link" @click.prevent="navegar('/escuelas')">
                  <i class="nav-icon fas fa-school"></i>
                  <p>
                    Escuelas
                  </p>
                </a>
              </template>
            </li>
            <!-- <li class="nav-item-sidebar">
              <template v-if="permisos.includes('areatesis.index')">
                <a class="nav-link" :to="'/areatesis'">
                  <i class="nav-icon fas fa-microscope"></i>
                  <p>
                    Areas de documentos
                  </p>
                </a>
              </template>
            </li> -->
            <!-- <li class="nav-item-sidebar">
              <template v-if="permisos.includes('vinculacion.index')">
                <a class="nav-link" :to="'/vinculacion'">
                  <i class="nav-icon fas fa-hands-helping"></i>
                  <p>
                    Vinculaciones
                  </p>
                </a>
              </template>
            </li> -->
            <!-- <li class="nav-item-sidebar">
                <template v-if="permisos.includes('escuelas.documentos.navegar')">
                  <a class="nav-link" :to="'/documentosEscuela'">
                    <i class="nav-icon fa fa-folder"></i>
                    <p>
                      Documentos de Escuela
                    </p>
                  </a>
                </template>
              </li> -->
          </template>
          <template  v-if="permisos.includes('parametros.index') || permisos.includes('reportes.logs')">
            <li class="nav-header">CONTROL DE SISTEMA</li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('parametros.index')">
                <a href="#" class="nav-link" @click.prevent="navegar('/parametros')">
                  <i class="nav-icon fas fa-cog"></i>
                  <p>
                    Parámetros
                  </p>
                </a>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('reportes.logs')">
                <a href="#" class="nav-link" @click.prevent="navegar('/reportes/logs')">
                  <i class="nav-icon fas fa-database"></i>
                  <p>
                    Registros
                  </p>
                </a>
              </template>
            </li>

          </template>
          <!-- VISTA PARA ADMINISTRACION DE REPORTES -->
          <template  v-if="permisos.includes('reportes.reportefit')">
            <li class="nav-header">REPORTES</li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('reportes.reportefit')">
                <a href="#" class="nav-link" @click.prevent="navegar('/reportes')">
                  <i class="nav-icon fas fa-database"></i>
                  <p>
                    Reportes de documentos
                  </p>
                </a>
              </template>
            </li>
          </template>
          <!-- VISTA PARA ADMINISTRACION DE DOCUMENTOS -->
          <template  v-if="permisos.includes('actadefensa.index') || permisos.includes('documentos.index')">
            <li class="nav-header">CONTROL DE DOCUMENTOS</li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('actadefensa.index')">
                <a href="#" class="nav-link" @click.prevent="navegar('/actadefensa')">
                  <i class="nav-icon fas fa-user-check"></i>
                  <p>
                    Acta de defensa y nota
                  </p>
                </a>
              </template>
            </li>
            <li class="nav-item-sidebar">
              <template v-if="permisos.includes('documentos.index')">
                <a href="#" class="nav-link" @click.prevent="navegar('/documentos')">
                  <i class="nav-icon far fa-id-card"></i>
                  <p>
                    Documentos alumnos
                  </p>
                </a>
              </template>
            </li>
          </template>
          <!-- PESTAÑAS PARA EL ALUMNO -->
          <template  v-if="permisos.includes('tesis.fit') || permisos.includes('avances.index') || permisos.includes('bitacoras.index') || permisos.includes('vinculacion.index') || permisos.includes('escuelas.documentos.navegar')">
            <li class="nav-header">SEGUIMIENTO DE DOCUMENTOS</li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('tesis.fit')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/tesis')">
                    <i class="nav-icon far fa-edit"></i>
                    <p v-if="rolActivo == 'Alumno'">
                      Inscribir/Revisar {{terminoTitulo}}
                    </p>
                    <p v-else>Revisar {{terminoTitulo}}</p>
                  </a>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('avances.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/avances')">
                    <i class="nav-icon fas fa-file-upload"></i>
                    <p>
                      Avances de documento
                    </p>
                  </a>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('bitacoras.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/bitacoras')">
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>
                      Actas de reunión
                    </p>
                  </a>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('comisiones.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/comisiones')">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                      Comisiones
                    </p>
                  </a>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('vinculacion.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/vinculacion')">
                    <i class="nav-icon fas fa-hands-helping"></i>
                    <p>
                        Vinculaciones
                    </p>
                  </a>
                </template>
              </li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('escuelas.documentos.navegar')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/documentosEscuela')">
                    <i class="nav-icon fa fa-folder"></i>
                    <p>
                      Información de Escuela
                    </p>
                  </a>
                </template>
              </li>
          </template>

          <template  v-if="permisos.includes('notaspendientes.index')">
            <li class="nav-header">SOLICITUD NOTA PENDIENTE</li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('notaspendientes.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/notaspendientes')">
                    <i class="nav-icon fas fa-calendar-day"></i>
                    <p>
                      Nota pendiente
                    </p>
                  </a>
                </template>
              </li>
          </template>
          <template  v-if="permisos.includes('abandono.index')">
            <li class="nav-header">DESCARTE DE DOCUMENTOS</li>
              <li class="nav-item-sidebar">
                <template v-if="permisos.includes('abandono.index')">
                  <a href="#" class="nav-link" @click.prevent="navegar('/abandono')">
                    <i class="fas fa-box"></i>
                    <p>
                      {{terminoTitulo}}s en abandono
                    </p>
                  </a>
                </template>
              </li>
          </template>
          <li class="nav-item-sidebar" style="height: 10vh">
          </li>
          <!-- <template  v-if="permisos.includes('sitios.interes')">
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
          </template> -->
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
      rolActivo : JSON.parse(localStorage.getItem('rolActivo')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      permisos : this.listPermisos,
      fullscreenLoading: false,
      timeout: true
    }
  },
  mounted(){
        this.cambiarRol(false);
    },
  methods:{
    logout(){
      this.fullscreenLoading = true;
      var url='/authenticate/logout'
      axios.post(url).then(response => {
        if(response.data.code == 204){
          window.location.href = '/sigad/';
          //location.reload();
          localStorage.clear();
          this.fullscreenLoading = false;
        }

      })
    },
    navegar(ruta){
      if (ruta != this.$route.path) {
        this.$router.push(ruta);
      }else{
        if (this.timeout) {
          EventBus.$emit('refresh');
          this.timeout = false;
          setTimeout(x => {
            this.timeout = true;
            },3000);
        }
      }
    },
    cambiarRol(redirect){
      // this.fullscreenLoading = true;
      localStorage.setItem('rolActivo', JSON.stringify(this.rolActivo));
      var url = '/perfil/setRol'
      axios.post(url, {
              'rol' : this.rolActivo
      }).then(response => {
          // this.fullscreenLoading = false;
          localStorage.setItem('listRolPermisosByUsuario', JSON.stringify(response.data.permisos));
          this.permisos = response.data.permisos;
          if(response.data.code == 401){
              console.log('Error')
          }
          if(response.data.code == 200 && redirect){
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
    .main-sidebar {
        background-color: #001740 !important;
    }
</style>
