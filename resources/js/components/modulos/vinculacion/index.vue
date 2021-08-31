<template>
  <div class="card">
    <template  v-if="listRolPermisosByUsuario.includes('vinculacion.crear')">
      <div class="card-header">
        <div class="card-tools">
          <router-link class="btn btn-info bnt-sm" :to="'/vinculacion/crear'">
            <i class="fas fa-plus-square"></i> Nueva vinculación
          </router-link>
        </div>
      </div>
    </template>
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
                    <label class="col-md-3 col-form-label">Nombre</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqVinculacion.cNombre" @keyup.enter="getListarVinculacion">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Tipos</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqVinculacion.cTipo"
                        placeholder="Seleccione un Tipo"
                        clearable>
                          <el-option
                            v-for="item in listTipo"
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
                    <label class="col-md-3 col-form-label">Descripción</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqVinculacion.cDescripcion" @keyup.enter="getListarVinculacion">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqVinculacion.cEstado"
                        placeholder="Seleccione un estado"
                        >
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
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarVinculacion" v-loading.fullscreen.lock="fullscreenLoading"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Bandeja de resultados</h3>
          </div>
          <div class="card-body table table-responsive">
            <template v-if="listarVinculacionPaginated.length">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>tipo</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <template  v-if="listRolPermisosByUsuario.includes('vinculacion.editar')">
                      <th>Acciones </th>
                    </template>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarVinculacionPaginated" :key="index">
                    <td v-text="item.nombre"></td>
                    <td v-text="item.tipo"></td>
                    <td v-text="item.descripcion"></td>
                    <td>
                      <template v-if="item.estado == 'A'">
                        <span class="badge badge-success" >Activo</span>
                      </template>
                      <template v-else>
                        <span class="badge badge-danger" >Inactivo</span>
                      </template>
                    </td>
                    <td>
                      <template  v-if="listRolPermisosByUsuario.includes('vinculacion.editar')">
                      <router-link title="Editar" class="btn btn-info boton" :to="{name:'vinculacion.editar', params:{id: item.id}}">
                        <i class="fas fa-pencil-alt"></i>
                      </router-link>
                      <template v-if="item.estado == 'A'">
                        <button title="Desactivar" class="btn btn-danger boton" @click.prevent="setCambiarVinculacion(1, item.id)">
                          <i class="fas fa-ban"></i>
                        </button>
                      </template>
                      <template v-else>
                        <button title="Activar" class="btn btn-success boton" @click.prevent="setCambiarVinculacion(2, item.id)">
                          <i class="fas fa-check"></i>
                        </button>
                      </template>
                    </template>
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
                :class="[page == pageNumber ? 'active' : '']">
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
export default {
  props: ['usuario'],
  data(){
    return{
      globVar: new globVar(),
      fillBsqVinculacion:{
        cNombre: '',
        cTipo: '',
        cDescripcion:'',
        cEstado: 'T'
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listTipo: [
        {value: 'Fondo Concursable', label: 'Fondo Concursable'},
        {value: 'Empresa', label: 'Empresa'},
        {value: 'Comunidad', label: 'Comunidad'}
      ],
      listVinculacion:[],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 5,
      listEstados: [
        {value: 'A', label: 'Activo'},
        {value: 'I', label: 'Inactivo'},
        {value: 'T', label: 'Todos'}
      ]

    }
  },
  computed: {

    pageCount(){
      //obtener el numero de paginas
      let a = this.listVinculacion.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarVinculacionPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listVinculacion.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listVinculacion.length,
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
  methods:{
    limpiarCriteriosBsq(){
      this.fillBsqVinculacion.cNombre = '';
      this.fillBsqVinculacion.cTipo = '';
      this.fillBsqVinculacion.cDescripcion = '';
      this.fillBsqVinculacion.cEstado = '';
    },
    limpiarBandejaUsuarios(){
      this.listVinculacion = [];
    },
    getListarVinculacion(){
      this.fullscreenLoading = true;
      var url = '/administracion/vinculacion/getListarVinculacion'
      axios.get(url, {
        params: {
          'cNombre'       : this.fillBsqVinculacion.cNombre,
          'cTipo'         : this.fillBsqVinculacion.cTipo,
          'cDescripcion'  : this.fillBsqVinculacion.cDescripcion,
          'cEstado'       : this.fillBsqVinculacion.cEstado
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listVinculacion = response.data;
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
    abrirModalByOption(modulo, accion, data){
    switch (modulo) {
      case "roles":
        {
          switch (accion) {
            case "ver":
              {
                this.fillVerRol.cNombre = data.name;
                this.fillVerRol.cTipo = data.slug;
                //obtener los permisos por el rol seleccionado
                this.getListarVinculacionByRol(data.id);
              }
              break;
            default:
              break;
          }
        }

        break;

      default:
        break;
    }
  },
  setCambiarVinculacion(op, id){
      Swal.fire({
      title: 'Estas seguro? ' + ((op == 1) ? 'desactivar ' : 'activar ') + ' la vinculacion',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: ((op == 1) ? 'Si, desactivar ' : 'Si, activar ')
    }).then((result) => {
      if (result.value) {
        this.fullscreenLoading = true;
        var url = '/administracion/vinculacion/setCambiarVinculacion'
        axios.post(url, {
          'nIdVinculacion' : id,
          'cEstadopg'    : (op == 1) ? 'I' : 'A'
        }).then(response => {
            Swal.fire({
            icon: 'success',
            title: 'Se ' + ((op == 1) ? 'desactivo ' : 'activo ') +'la vinculacion',
            showConfirmButton: false,
            timer: 1500
            })
            this.getListarVinculacion();
        })
      }
    })
    }


  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
</style>
