<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Notas pendientes</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools" v-if="listNotasPendientes">
            <!-- /.container-fluid -->
                            <!-- /.container-fluid -->
            <template v-if="listRolPermisosByUsuario.includes('EsAlumno') && listNotasPendientes.length > 0">
              <div>
              <router-link class="btn btn-info bnt-sm link-disabled" :to="''">
                <i class="fas fa-plus-square"></i>Usted ya ingreso una nota pendiente
              </router-link>
              </div>
            </template>
            <template v-if="listRolPermisosByUsuario.includes('notaspendientes.crear')">
              <template v-if="listNotasPendientes.length == 0 && fillEstadoTesis.cEstado  == 'D'">
                <router-link class="btn btn-info bnt-sm" :to="'/notaspendientes/crear'">
                  <i class="fas fa-plus-square"></i>Solicitar nota pendiente
                </router-link>
              </template>
            </template>
          </div>
        </div>

        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Bandeja de resultados</h3>
              </div>
              <div class="card-body table-responsive">
                <template v-if="listarNotasPendientesPaginated.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Fecha de ingreso</th>
                        <th>Fecha propuesta</th>
                        <th>Fecha prórroga</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarNotasPendientesPaginated" :key="index">
                        <td> <!-- itera mostrando la cantidad total de estudiantes -->
                            <div v-for="(itemUser, index) in item.alumnos" :key="index">
                                <p v-text="itemUser.nombres + ' ' + itemUser.apellidos"></p>
                            </div>
                        </td>
                        <td v-text="item.fecha_presentacion"></td>
                        <td v-text="item.fecha_propuesta"></td>
                        <td v-text="item.fecha_prorroga"></td>
                        <td>
                            <template  v-if="listRolPermisosByUsuario.includes('notaspendientes.editar') && fillEstadoTesis.cEstado  == 'D'">
                              <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'notaspendientes.editar', params:{id: item.id}}">
                                <i class="fas fa-pencil-alt"></i> Editar
                              </router-link>
                            </template>
                            <template v-if="listRolPermisosByUsuario.includes('notaspendientes.editar') && fillEstadoTesis.cEstado  == 'D'">
                              <router-link class="btn btn-flat btn-success btn-sm" :to="{name:'notaspendientes.prorroga', params:{id: item.id}}">
                                <i class="fas fa-calendar-check"></i> Solicitar prórroga
                              </router-link>
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
            </div>
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
export default {
  props: ['usuario'],
  data(){
    return{
      fillBsqNotasPendientes:{
        estado: '',
        dfecharango: '',
      },
      fillEstadoTesis:{
        cEstado: '',
      },
      listEstadosNotaP: [
        {value: 'P', label: 'Activa'},
        {value: 'R', label: 'Rechazada'},
        {value: 'V', label: 'Vencida'}
      ],
      listNotasPendientes: [],
      listAlumnos:[],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 5,
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
    }
  },
  computed: {
    pageCount(){
      let a = this.listNotasPendientes.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarNotasPendientesPaginated(){
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listNotasPendientes.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listNotasPendientes.length,
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
    if(this.listRolPermisosByUsuario.includes('EsAlumno'))
    {
      this.getMiNotaP();
      this.getEstadoTesis();
    }
    else{
      this.getListarNotasPendientes();
    }
  },
  methods:{
    getListarAlumnosByprofesor(){
      this.fullscreenLoading = true;
      var url = '/avances/getListarAlumnosByprofesor'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacion();
          this.listAlumnos = response.data;
          this.fullscreenLoading = false;
      })
    },
    getEstadoTesis(){
        var url = '/avances/getEstadoTesis'
        axios.get(url, {
      }).then(response => {
          if(response.data){
            this.fillEstadoTesis.cEstado     = response.data;
          }
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqNotasPendientes.estado = '';
      this.fillBsqNotasPendientes.dfecharango = '';
    },
    limpiarBandejaNotasPendientes(){
      this.listNotasPendientes = [];
    },
    getListarNotasPendientes(){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getListarNotasPendientes'
      axios.get(url, {
        params: {
          'estado' : this.fillBsqNotasPendientes.estado,
          'dFechaInicio'  :   (!this.fillBsqNotasPendientes.dfecharango) ? '' : this.fillBsqNotasPendientes.dfecharango[0],
          'dFechaFin'     :   (!this.fillBsqNotasPendientes.dfecharango) ? '' : this.fillBsqNotasPendientes.dfecharango[1],
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listNotasPendientes = response.data;
          this.fullscreenLoading = false;
      })
    },
    getMiNotaP(){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getMiNotaP'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacion();
          this.listNotasPendientes = response.data;
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
  .el-range-editor.el-input__inner{
    width: 100% !important;
  }
  .el-date-editor .el-range-separator{
    width: 7% !important;
  }
</style>
