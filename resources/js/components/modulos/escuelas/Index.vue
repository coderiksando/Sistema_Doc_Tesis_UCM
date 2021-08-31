<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Escuelas</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/escuelas/crear'">
              <i class="fas fa-plus-square"></i> Nueva Escuela
            </router-link>
            <router-link class="btn btn-success bnt-sm" :to="'/escuelas/crearfacultad'">
              <i class="fas fa-plus-square"></i> Nueva Facultad
            </router-link>
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
                        <label class="col-md-3 col-form-label">Nombre escuela</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" v-model="fillBsqEscuela.cNombre" @keyup.enter="getListarEscuelas">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Facultad</label>
                        <div class="col-md-9">
                            <el-select filterable v-model="fillBsqEscuela.nIdFacultad"
                            placeholder="Asignar Facultad"
                            clearable>
                            <el-option
                                v-for="item in listFacultades"
                                :key="item.id"
                                :label="item.nombre"
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
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarEscuelas" v-loading.fullscreen.lock="fullscreenLoading"
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
              <div class="card-body table-resposive">
                <template v-if="listarEscuelasPaginated.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Facultad</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarEscuelasPaginated" :key="index">
                        <td v-text="item.nombre"></td>
                        <td v-text="item.facultad.nombre"></td>
                        <td>
                            <router-link title="Editar" class="btn btn-info boton" :to="{name:'escuelas.editar', params:{id: item.id}}">
                              <i class="fas fa-pencil-alt"></i>
                            </router-link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
  </div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillBsqEscuela:{
        cNombre: '',
        nIdFacultad: ''
      },
      listEscuelas:[],
      listFacultades:[],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 5,

    }
  },
  computed: {

    pageCount(){
      //obtener el numero de paginas
      let a = this.listEscuelas.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarEscuelasPaginated(){
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listEscuelas.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listEscuelas.length,
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
    this.getListarFacultades();
  },
  methods:{

    limpiarCriteriosBsq(){
      this.fillBsqEscuela.cNombre = '';
      this.fillBsqEscuela.nIdFacultad = '';
    },
    limpiarBandejaUsuarios(){
      this.listEscuelas = [];
    },
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {
        params: {
          'cNombre'     : this.fillBsqEscuela.cNombre,
          'nIdFacultad'  : this.fillBsqEscuela.nIdFacultad
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listEscuelas = response.data;
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
    getListarFacultades(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarFacultades'
      axios.get(url, {
      }).then(response => {
          this.listFacultades = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarEscuelasByRol(id){
        var ruta = '/administracion/roles/getListarEscuelasByRol'
        axios.get(ruta, {
            params: {
                'nIdRol' : id
            }
        }).then( response => {
            this.listEscuelas = response.data;
            this.modalShow = true;
            this.modalOption = 2;
        })
    },
    abrirModalByOption(modulo, accion, data){
    switch (modulo) {
      case "roles":
        {
          switch (accion) {
            case "ver":
              {
                this.fillVerRol.cNombre = data.name;
                this.fillVerRol.cSlug = data.slug;
                //obtener los permisos por el rol seleccionado
                this.getListarEscuelasByRol(data.id);
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


  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
</style>
