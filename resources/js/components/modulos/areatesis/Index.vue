<template>
  <div class="card">
    <div class="card-header">
      <div class="card-tools">
        <router-link class="btn btn-info bnt-sm" :to="'/areatesis/crear'">
          <i class="fas fa-plus-square"></i> Nuevo Tema
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
                    <label class="col-md-3 col-form-label">Nombre</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqAreaTesis.cNombre" @keyup.enter="getListarAreaTesis">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Escuela</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqAreaTesis.nIdEscuela"
                        placeholder="Seleccione una escuela"
                        clearable>
                          <el-option
                            v-for="item in listEscuelas"
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
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarAreaTesis" v-loading.fullscreen.lock="fullscreenLoading"
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
            <template v-if="listarAreaTesisPaginated.length">

              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Escuela</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarAreaTesisPaginated" :key="index">
                    <td v-text="item.nombre"></td>
                    <td v-text="item.escuela_alias">

                    </td>

                    <td>
                        <router-link title="Editar" class="btn btn-info boton" :to="{name:'areatesis.editar', params:{id: item.id}}">
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
                    :class="[page == pageNumber ? 'active' : '']"
                    :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
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
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillBsqAreaTesis:{
        cNombre: '',
        nIdEscuela: ''
      },
      listAreaTesis:[],
      listEscuelas:[],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 10,

    }
  },
  mounted(){
    EventBus.$emit('navegar', 'Areas de documento');
    this.getListarEscuelas();
  },
  computed: {

    pageCount(){
      //obtener el numero de paginas
      let a = this.listAreaTesis.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarAreaTesisPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listAreaTesis.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listAreaTesis.length,
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
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {

      }).then(response => {
          this.listEscuelas = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqAreaTesis.cNombre = '';
      this.fillBsqAreaTesis.nIdEscuela = '';
    },
    limpiarBandejaUsuarios(){
      this.listAreaTesis = [];
    },
    getListarAreaTesis(){
      this.fullscreenLoading = true;
      var url = '/administracion/areatesis/getListarAreaTesis'
      axios.get(url, {
        params: {
          'cNombre' : this.fillBsqAreaTesis.cNombre,
          'nIdEscuela' : this.fillBsqAreaTesis.nIdEscuela,
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listAreaTesis = response.data;
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

  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
</style>
