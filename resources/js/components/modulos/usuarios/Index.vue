<template>
  <div class="card">
    <div class="card-header">
      <div class="card-tools">
        <template v-if="listRolPermisosByUsuario.includes('usuarios.crear')">
            <router-link class="btn btn-info bnt-sm" :to="{name: 'usuarios.crear'}">
              <i class="fas fa-plus-square"></i> Nuevo Usuario
          </router-link>
        </template>
        <template v-if="listRolPermisosByUsuario.includes('usuarios.importar')">
            <router-link class="btn btn-success bnt-sm" :to="{name: 'usuarios.importar'}">
              <i class="fas fa-plus-square"></i> Importar
          </router-link>
        </template>
      </div>
    </div>
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Criterios de búsqueda</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nombres</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqUsuarios.cNombre" @keyup.enter="getListarUsuarios">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Apellidos</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqUsuarios.cApellido" @keyup.enter="getListarUsuarios">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Correo</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqUsuarios.cCorreo" @keyup.enter="getListarUsuarios">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqUsuarios.cEstado"
                        placeholder="Seleccione un estado"
                        clearable>
                          <el-option
                            v-for="item in listEstados"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                          </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Escuela</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqUsuarios.nEscuela"
                        placeholder="Seleccione una carrera"
                        filterable
                        clearable>
                          <el-option
                            v-for="item in listCarrera"
                            :key="item.id"
                            :label="item.nombre"
                            :value="item.id">
                          </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Roles</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqUsuarios.nIdRol"
                        placeholder="Seleccione una carrera"
                        filterable
                        clearable>
                          <el-option
                            v-for="item in listRol"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                          </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
              </div>
            </form>

          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarUsuarios" v-loading.fullscreen.lock="fullscreenLoading"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Bandeja de resultados</h3>
            <div style="text-align: right;">
                <el-popover
                title="Ayuda"
                placement="left"
                width="20"
                trigger="hover">
                    <button slot="reference" class="btn btn-secondary" @click.prevent="redirectTo('dashboard.index', {open: 'usuarios', focus: 'usuarios'}, true)">
                        <i class="fas fa-question-circle" ></i>
                    </button>
                </el-popover>
            </div>
          </div>
          <div class="card-body table table-responsive">
            <template v-if="listarUsuariosPaginated.length">

              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Escuela</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarUsuariosPaginated" :key="index">
                    <td>
                      <template v-if="!item.profile_image">
                        <li class="user-block">
                          <img src="/img/avatar.png" :alt="item.username" class="profile-avatar-img img-fluid img-circle">
                        </li>
                      </template>
                      <template v-else>
                        <li class="user-block">
                          <img :src="item.profile_image" :alt="item.username" class="profile-avatar-img img-fluid img-circle">
                        </li>
                      </template>
                    </td>
                    <td v-text="item.nombres"></td>
                    <td v-text="item.apellidos"></td>
                    <td v-text="item.email"></td>
                    <td v-text="item.nombreEscuela"></td>
                    <td v-if="item.roles.length">{{globFunct.concatAll(item.roles.map(x=>{return x.name}))}}</td>
                    <td>
                      <template v-if="item.state == 'A'">
                        <span class="badge badge-success">Activo</span>
                      </template>
                      <template v-else>
                        <span class="badge badge-danger">Inactivo</span>
                      </template>
                    </td>
                    <td>
                      <router-link title="Ver y Editar" class="btn boton btn-primary" :to="{name:'usuarios.ver', params:{id_user: item.id_user}}">
                        <i class="fas fa-eye"></i>
                      </router-link>
                      <template v-if="item.state == 'A'">
                        <router-link title="Permisos" class="btn boton btn-primary" :to="{name:'usuarios.permisos', params:{id_user: item.id_user}}">
                          <i class="fas fa-key"></i>
                        </router-link>
                        <button title="Desactivar usuario" class="btn boton btn-danger" @click.prevent="setCambiarEstadoUsuario(1, item.id_user)">
                          <i class="fas fa-ban"></i>
                        </button>

                      </template>
                      <template v-else>
                        <button title="Activar usuario" class="btn boton btn-success" @click.prevent="setCambiarEstadoUsuario(2, item.id_user)">
                          <i class="fas fa-check-circle"></i>
                        </button>
                      </template>
                        <button title="Eliminar usuario" class="btn boton btn-danger" @click.prevent="eliminarUsuario(item.id_user)">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </template>
            <template v-else>
              <div class="callout callout-info">
                <h5> No se han encontrado resultados...</h5>
              </div>
            </template>
          </div>
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item" v-if="pageNumber > 0">
                <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
              </li>
              <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                :class="[page == pageNumber ? 'active' : '']"
                :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
                <a href="#" class=page-link @click.prevent="selectPage(page)"> {{page+1}}</a>
              </li>
              <li class="page-item" v-if="pageNumber < pageCount -1">
                <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
export default {
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillBsqUsuarios:{
        cNombre: '',
        cApellido: '',
        cCorreo: '',
        cEstado: '',
        nEscuela: '',
        nIdRol: ''
      },
      fullscreenLoading: false,
      listUsuarios:[],
      listCarrera: [],
      listRol: [],
      listEstados: [
        {value: 'A', label: 'Activo'},
        {value: 'I', label: 'Inactivo'}
      ],
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 10
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listUsuarios.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarUsuariosPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listUsuarios.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listUsuarios.length,
          b = this.perPage;
      let pageCount = Math.ceil(a / b);
      let count = 0,
        pagesArray = [];
      while (count < pageCount){
        pagesArray.push(count);
        count++;
      }
      return pagesArray;

    }
  },
  mounted(){
    EventBus.$emit('navegar', 'Usuarios');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarEscuelas();
      this.getListarRoles();
      this.getListarUsuarios()
    },
    getListarEscuelas(){
        var url = "/administracion/escuelas/getListarEscuelas";
        axios.get(url, {
        })
        .then((response) => {
            this.listCarrera = response.data;
        });
    },
    getListarRoles(){
        const url = "/administracion/roles/getListarRoles";
        axios.get(url, {
        })
        .then((response) => {
            this.listRol = response.data;
        });
    },
    limpiarCriteriosBsq(){
      this.fillBsqUsuarios.cNombre = '';
      this.fillBsqUsuarios.cApellido = '';
      this.fillBsqUsuarios.cCorreo = '';
      this.fillBsqUsuarios.cEstado = '';
    },
    limpiarBandejaUsuarios(){
      this.listUsuarios = [];
    },
    getListarUsuarios(){
      this.fullscreenLoading = true;
      var url = '/administracion/usuario/getListarUsuarios'
      axios.get(url, {
        params: {
          'cNombre'     : this.fillBsqUsuarios.cNombre,
          'cApellido'   : this.fillBsqUsuarios.cApellido,
          'cCorreo'     : this.fillBsqUsuarios.cCorreo,
          'cEstado'     : this.fillBsqUsuarios.cEstado,
          'nEscuela'    : this.fillBsqUsuarios.nEscuela,
          'nIdRol'        : this.fillBsqUsuarios.nIdRol
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listUsuarios = response.data;
          this.fullscreenLoading = false;
      })
    },
    nextPage(){
      this.pageNumber++;
    },
    prevPage(){
      this.pageNumber--;
    },
    selectPage(page){
      this.pageNumber = page;
    },
    inicializarPaginacion(){
      this.pageNumber = 0;
    },
    setCambiarEstadoUsuario(op, id_user){
        Swal.fire({
            title: '¿Está seguro de que desea ' + ((op == 1) ? 'desactivar ' : 'activar ') + ' el usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: ((op == 1) ? 'Si, desactivar ' : 'Si, activar ')
            }).then((result) => {
            if (result.value) {
                this.fullscreenLoading = true;
                var url = '/administracion/usuario/setCambiarEstadoUsuario'
                axios.post(url, {
                'nIdUsuario' : id_user,
                'cEstado'    : (op == 1) ? 'I' : 'A'
                }).then(response => {
                    Swal.fire({
                    icon: 'success',
                    title: 'Se ' + ((op == 1) ? 'desactivo ' : 'activo ') +'el usuario',
                    showConfirmButton: false,
                    timer: 1500
                    })
                    this.getListarUsuarios();
                })
            }
        })
    },
    eliminarUsuario(id_user) {
        Swal.fire({
          title: '¿Está seguro de que desea eliminar definitivamente a éste usuario? (será eliminado de forma permanente)',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText:'Si, eliminar para siempre'
        }).then((result) => {
          if (result.isConfirmed) {
            this.fullscreenLoading = true;
            var url = '/administracion/usuario/setEliminarUsuario';
            axios.post(url, {
                'idUser' : id_user
            }).then(response => {
                this.fullscreenLoading = false;
                if (response.data.estado == 'Ok') {
                    Swal.fire({
                        icon: 'success',
                        title: response.data.mensaje,
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                      this.getListarUsuarios();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: response.data.mensaje,
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }).catch(response => {
                this.fullscreenLoading = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error en eliminación inesperada.',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
          }
        });
    },
    redirectTo(route, objectId, newTab) {
        if (route.search("http") !== 0) {
            if (!newTab) {
                if (objectId) this.$router.push({name: route, params: objectId});
                else this.$router.push({name: route});
            } else {
                const ruteData = this.$router.resolve({name: route, params: objectId});
                window.open(ruteData.href, '_blank');
            }
        } else window.open(route, '_blank');
    }
  }//cierre de methods
}
</script>

<style>

</style>
