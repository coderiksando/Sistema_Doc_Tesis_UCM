<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Formulario de edición de rol</h3>
                    </div>
                        <div class="card-body">
                            <form role="form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Nombres</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="fillEditarRol.cNombre" @keyup.enter="setEditarRolPermisos">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Url</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" v-model="fillEditarRol.cSlug" @keyup.enter="setEditarRolPermisos">
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarRolPermisos" v-loading.fullscreen.lock="fullscreenLoading">
                                    {{globVar.btnSave}}
                                </button>
                                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">{{globVar.btnClear}}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Listar permisos</h3>
                        </div>
                        <div class="card-body table-resposive" v-loading = loading>
                              <template v-if="listPermisosFilter.length">
                                <div class="form-group row">
                                    <label title='Nombre de permiso' class="col-md-3 col-form-label">Nombre</label>
                                    <el-input class="col-md-9" placeholder="Personalizado" v-model="busqNombre"></el-input>
                                </div>
                                <div class="scrollTable">
                                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                                    <thead>
                                      <tr>
                                        <th>Acción</th>
                                        <th>Nombre</th>
                                        <th>Url</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr v-for="(item, index) in listPermisosFilter" :key="index" @click.prevent="marcarFila(index)">
                                        <template v-if="(item.name.toLowerCase().match(busqNombre.toLowerCase())) || (busqNombre == '')">
                                            <td>
                                            <!-- cheeekboh-->
                                            <el-checkbox v-model="item.checked">Opción</el-checkbox>
                                            </td>
                                            <td v-text="item.name"></td>
                                            <td v-text="item.slug"></td>
                                        </template>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>
                                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarRolPermisos" v-loading.fullscreen.lock="fullscreenLoading">
                                    {{globVar.btnSave}}
                                </button>
                                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">{{globVar.btnClear}}</button>
                              </template>
                              <template v-else>
                                <div class="callout callout-info">
                                  <h5> No se han encontrado resultados...</h5>
                                </div>
                              </template>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sistema de documentos UCM</h5>
            <button class="close" @click="abrirModal"></button>
          </div>
          <div class="modal-body">
            <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="abrirModal">{{globVar.btnClose}}</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillEditarRol:{
        nIdRol: this.$attrs.id,
        cNombre: '',
        cSlug: '',
      },
      busqNombre: '',
      listPermisos: [],
      listPermisosByRol: [],
      listPermisosFilter:[],
      listRolPermisosByUsuario: [],
      listRolPermisosByUsuarioFilter: [],
      fullscreenLoading: false,
      loading: false,
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError:[]

    }
  },
  computed: {
  },
  mounted(){
      EventBus.$emit('navegar', 'Editar rol');
      this.getListarRoles();
      this.getListarPermisos();
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarRol.cNombre = '';
    //   this.fillEditarRol.cSlug = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getListarRoles(){
      this.fullscreenLoading = true;
      var url = '/administracion/roles/getListarRoles'
      axios.get(url, {
        params: {
          'nIdRol' : this.fillEditarRol.nIdRol
        }
      }).then(response => {
          this.fillEditarRol.cNombre = response.data[0].name;
        //   this.fillEditarRol.cSlug   = response.data[0].slug;
          this.fullscreenLoading = false;
      })
    },
    getListarPermisos(){
        this.loading = true;
        var ruta = '/administracion/roles/getListarPermisos'
        axios.get(ruta, {}).then( response => {
            this.listPermisos = response.data;
            this.getListarPermisosByRol();
        })
    },
    getListarPermisosByRol(){
        var ruta = '/administracion/roles/getListarPermisosByRol'
        axios.get(ruta, {
            params: {
                'nIdRol' : this.fillEditarRol.nIdRol
            }
        }).then( response => {
            this.listPermisosByRol = response.data;
            this.filterPermisosByRol();
        })
    },
    validarEditarRolPermiso(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarRol.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        // if(!this.fillEditarRol.cSlug){
        //   this.mensajeError.push("La Url amigable es un campo obligatorio")
        // }

        let contador = 0;
        this.listPermisosFilter.map(function(x,y){
          if(x.checked == true){
            contador++;
          }
        })
        if(contador == 0){
          this.mensajeError.push("Debe seleccionar almenos un permiso");
        }

        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setEditarRolPermisos(){
      if(this.validarEditarRolPermiso()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/roles/setEditarRolPermisos'
      axios.post(url, {
        'nIdRol'             : this.fillEditarRol.nIdRol,
        'cNombre'            : this.fillEditarRol.cNombre,
        'cSlug'              : 'rol.' + this.fillEditarRol.cNombre.toLowerCase(),
        'listPermisosFilter' : this.listPermisosFilter
      }).then(response => {
        this.getListarRolPermisosByUsuario();
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
    filterPermisosByRol(){
      let me = this;
      me.listPermisos.map(function(x){
        me.listPermisosFilter.push({
          'id'     : x.id,
          'name'   : x.name,
          'slug'   : x.slug,
          'checked': (me.listPermisosByRol.find(y => y.id == x.id)) ? true : false
        });
      });
      this.loading = false;
    },
    marcarFila(index){
      this.listPermisosFilter[index].checked = !this.listPermisosFilter[index].checked;
    },
    getListarRolPermisosByUsuario(){
      var ruta = '/administracion/usuario/getListarRolPermisosByUsuario'
      axios.get(ruta).then( response => {
          this.listRolPermisosByUsuario = response.data;
          this.filterListarRolPermisosByUsuario();
      })
    },
    filterListarRolPermisosByUsuario(){
      let me = this;
      me.listRolPermisosByUsuarioFilter = [];
      me.listRolPermisosByUsuario.map(function(x,y){
          me.listRolPermisosByUsuarioFilter.push(x.slug)
      })
      localStorage.setItem('listRolPermisosByUsuario', JSON.stringify(me.listRolPermisosByUsuarioFilter));
      EventBus.$emit('notifyRolPermisosByUsuario', me.listRolPermisosByUsuarioFilter);
      this.fullscreenLoading = false;
        Swal.fire({
        icon: 'success',
        title: 'Rol Editado Correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      this.$router.push('/roles');
    },

  }// cierre methods
}
</script>

<style>

</style>
