<template>
    <div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
        <a class="navbar-brand">SIGAD UCM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            <router-link  :to="'/busqueda'">
              <li class="nav-item">
                <a class="nav-link">Búsqueda</a>
              </li>
            </router-link>
            <router-link  :to="'/'">
              <li class="nav-item">
                <a class="nav-link">Ingresar</a>
              </li>
            </router-link>
            </ul>
        </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div style="padding-top: 100px; padding-bottom: 100px;" class="container">

      <div class="card">
        <div class="card-body">
          <div class="container-fluid">
              <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Criterios de búsqueda de documentos</h3>
              </div>
              <div class="card-body">
                <div>
                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="noPadNoMar col-md-12 col-form-label">Título de documento</label>
                            <div class="input-group">
                                <input type="text" class="form-control" v-model="fillBsqTesis.cTitulo" v-on:keyup.enter="getListarTesisHome" placeholder="Inserte el título del documento" >
                                <button v-if="!mostrarMas" title="Mostrar más opciones" class="btn boton btn-info float-right" @click.prevent="setEstadoMostrar">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button v-if="mostrarMas" title="Mostrar menos" class="btn boton btn-info float-right" @click.prevent="setEstadoMostrar">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <template v-if="mostrarMas">
                    <div class="noPadNoMar col-md-12">
                        <div class="form-group row">
                        <div class="col-md-5">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Escuelas</label>
                            <div class="col-md-9">
                                <el-select filterable v-model="fillBsqTesis.nIdEscuela" @change="getListarProfesorByEscuela"
                                placeholder="Asignar Escuela"
                                clearable>
                                <el-option
                                    v-for="item in listEscuelas"
                                    :key="item.id"
                                    :label="item.nombre"
                                    :value="item.id"
                                >
                                </el-option>
                                </el-select>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-5">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Profesor</label>
                            <div class="col-md-9">
                                    <el-select filterable v-model="fillBsqTesis.nIdProfesor"
                                    placeholder="Asignar Profesor"
                                    clearable>
                                    <el-option
                                        v-for="item in listProfesores"
                                        :key="item.id"
                                        :label="item.fullname"
                                        :value="item.id_user">
                                    </el-option>
                                    </el-select>
                                </div>
                        </div>
                        </div>
                        <div class="col-md-2">
                            <button title="Buscar documento" class="btn boton btn-info float-right" @click.prevent="getListarTesisHome">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    </template>
                  </div>
                </div>
              </div>

            <div id="accordion">
                <template v-if="listTesis.length">
                    <template v-for="(item, index) in listTesis">
                        <div class="card" :key="index">
                            <div class="card-body" :id="'heading'+index">
                            <h3 class="mb-0">
                                <button class="btn btn-link col-md-12 noPadNoMar d-flex" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="false" :aria-controls="'collapse'+index">
                                    <div title="Sección expandible" class="col-md-1"><a class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i></a></div>
                                    <div title="Sección expandible" class="col-md-8 noPadNoMar"><p class="float-left">{{item.titulo.slice(0, 40)}}</p></div>
                                    <div class="col-md-3 noPadNoMar">
                                        <a title="Descargar documento"  class="float-right btn btn-warning" :href="item.path" target="_blank"><i class="fas fa-file-download"></i></a>
                                    </div>

                                </button>
                            </h3>
                            </div>

                            <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordion">
                            <div class="card-footer">
                                <dl class="row">
                                    <dt class="col-md-2">Fecha:</dt>
                                    <dd class="col-md-10">{{moment(item.updated_at).format("DD-MM-YYYY")}}</dd>
                                    <dt class="col-md-2">Título extendido:</dt>
                                    <dd class="col-md-10">{{item.titulo}}</dd>
                                    <dt class="col-md-2">Descripción:</dt>
                                    <dd class="col-md-10">{{item.descripcion}}</dd>
                                    <dt class="col-md-2">Prof. Guía:</dt>
                                    <dd class="col-md-10">{{item.nombres + ' ' + item.apellidos}}</dd>
                                </dl>
                            </div>
                            </div>
                        </div>
                    </template>
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
                  <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
            </div>

              <!-- <div class="card-body table table-responsive">
                <template v-if="listTesis.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>PDF</th>
                        <th>Título</th>
                        <th>Profesor</th>
                        <th>Escuela</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listTesis" :key="index">
                        <td>
                          <a class="btn btn-flat btn-warning btn-sm" :href="item.path" target="_blank"><i class="fas fa-file-download"> </i> PDF</a>
                        </td>
                        <td>{{item.titulo.slice(0, 40)}}</td>
                        <td v-text="item.nombres + ' ' + item.apellidos"></td>
                        <td v-text="item.nombre"></td>
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
                  <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div> -->
            </div>
          </div>
        </div>
      </div>

    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer style="position: fixed; bottom: 0; width: 100%;" class="py-3 bg-dark">
        <div class="container" style="text-align: center">
          <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <strong >Sistema de gestión y administración de documentos <a href="https://www.ucm.cl">UCM</a>.</strong>&nbsp; All rights
    reserved.
        </div>
        <!-- Por favor por respeto a los colaboradores de este proyecto no eliminar las referencias de las personas que han participado-->
        <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    </div>
</template>

<script>
import moment from "moment";
import globFunct from '../../../services/globFunct';
export default {
    data(){
      return{
        moment: moment,
        globFunct: new globFunct(),
        fillBsqTesis:{
          cTitulo: '',
          cAutor: '',
          nIdEscuela:'',
          nIdProfesor:'',
          cEstadoTesis: ''
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),

        listTesis:[],
        listEscuelas:[],
        listProfesores:[],
        fullscreenLoading: false,
        pageNumber: 0,
        perPage: 10,
        modalShow: false,
        modalOption: 0,
        mostrarModal: {
          display: 'block',
          background: '#0000006b',
        },
        ocultarModal: {
          display: 'none',
        },
        error: 0,
        mensajeError:[],
        mostrarMas: false
      }
    },
    computed: {
      pageCount(){
        //obtener el numero de paginas
        let a = this.listTesis.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      listarTesisPaginated(){
        //
        let inicio = this.pageNumber * this.perPage,
          fin = inicio + this.perPage;
        return this.listTesis.slice(inicio, fin);
      },
      pagesList(){
        let a = this.listTesis.length,
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
      this.getListarEscuelas();
      this.getListarProfesorByEscuela();
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
      getListarProfesorByEscuela(){
        this.fillBsqTesis.nIdProfesor = null;
        this.fullscreenLoading = true;
        var url = '/reportes/getListarProfesorByEscuela'
        axios.get(url, {
          params: {
            'nIdEscuela' : this.fillBsqTesis.nIdEscuela,
          }
        }).then(response => {
            this.listProfesores = response.data;
            this.fullscreenLoading = false;
        })
      },
      setGenerarDocumento(){
        //this.fullscreenLoading = true;
        var url = '/administracion/reportes/export'
        axios.get(url, {
            responseType: 'blob',
            params:{
                'listTesis': JSON.stringify(this.listTesis)
            }
        }).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/vnd.ms-excel'});
          var url = document.createElement('a')
          url.href = URL.createObjectURL(oMyBlob);
          url.download = 'usuarios.xlsx'
          url.click()
        })
      },
      getListarTesisHome(){
        this.fullscreenLoading = true;
        var url = '/administracion/reportes/getListarTesisHome'
        axios.get(url, {
          params: {
            'cTitulo'        : this.fillBsqTesis.cTitulo,
            'nIdEscuela'  : this.fillBsqTesis.nIdEscuela,
            'cEstadoNotap'  : this.fillBsqTesis.cEstadoNotap,
            'dFechaUR'  : this.fillBsqTesis.dFechaUR,
            'nIdProfesor' : this.fillBsqTesis.nIdProfesor,
            'cTipo'       : this.fillBsqTesis.cTipo,
            'cVinculacion': this.fillBsqTesis.cVinculacion,
            'cEstadoTesis': this.fillBsqTesis.cEstadoTesis,
            'dFechaInicio': (!this.fillBsqTesis.dfecharango) ? '' : this.fillBsqTesis.dfecharango[0],
            'dFechaFin'   : (!this.fillBsqTesis.dfecharango) ? '' : this.fillBsqTesis.dfecharango[1],
          }
        }).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
            this.listTesis.forEach(tesis => {
                tesis.titulo = this.globFunct.capitalizeFirstLetter(tesis.titulo);
            });
            this.fullscreenLoading = false;
        })
      },
      limpiarCriteriosBsq(){
        this.fillBsqTesis.cTitulo = '';
        this.fillBsqTesis.nIdEscuela = '';
        this.fillBsqTesis.cEstadoNotap = '';
        this.fillBsqTesis.dFechaUR = '';
        this.fillBsqTesis.nIdProfesor = '';
        this.fillBsqTesis.cTipo = '';
        this.fillBsqTesis.cVinculacion = '';
        this.fillBsqTesis.cEstadoTesis = '';
        this.fillBsqTesis.dfecharango = '';
      },
      limpiarBandejaUsuarios(){
        this.listTesis = [];
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
      abrirModal(){
        this.modalShow = !this.modalShow;
        this.limpiarModal();
      },
      limpiarModal(){
        this.fillVerFIT.cNombre = ''
        this.fillVerFIT.cSlug = ''
        this.listPermisos = [];
        this.modalOption = 0;
      },
      setEstadoMostrar() {
          if (this.mostrarMas) {
              this.fillBsqTesis.nIdEscuela  = null;
              this.fillBsqTesis.nIdProfesor = null;
          }
          this.mostrarMas = !this.mostrarMas;
      }
    }
}
</script>

<style>

</style>
