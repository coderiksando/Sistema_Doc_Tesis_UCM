<template>
  <div class="card">
    <div class="card-header">
      <div class="card-tools" v-if="listNotasPendientes">
        <template v-if="listRolPermisosByUsuario.includes('notaspendientes.crear')">
          <template v-if="listNotasPendientes.length == 0 && fillEstadoTesis.cEstado  == 'D'">
            <router-link class="btn btn-info bnt-sm" :to="'/notaspendientes/crear'">
              <i class="fas fa-plus-square"></i> Solicitar nota pendiente
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
          <div class="card-body table-responsive" v-loading="fullscreenLoading">
            <template v-if="listarNotasPendientesPaginated.length">

              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Alumno</th>
                    <th>Fecha de ingreso</th>
                    <th>Fecha propuesta</th>
                    <th>Fecha prórroga</th>
                    <th v-if="listRolPermisosByUsuario.includes('notaspendientes.editar')">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarNotasPendientesPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <div v-text="itemUser.nombres + ' ' + itemUser.apellidos"></div>
                        </div>
                    </td>
                    <td> {{ item.fecha_presentacion | moment }}</td>
                    <td> {{ item.fecha_propuesta | moment }}</td>
                    <td>
                        <template v-if="item.fecha_prorroga">
                          {{ item.fecha_prorroga | moment }}
                        </template>
                      </td>
                    <td v-if="listRolPermisosByUsuario.includes('notaspendientes.editar') && fillEstadoTesis.cEstado  == 'D'">
                      <router-link title="Editar" class="btn btn-info boton" :to="{name:'notaspendientes.editar', params:{id: item.id}}">
                        <i class="fas fa-pencil-alt"></i>
                      </router-link>
                      <router-link title="Solicitar prórroga" class="btn btn-success boton" :to="{name:'notaspendientes.prorroga', params:{id: item.id}}">
                        <i class="fas fa-calendar-check"></i>
                      </router-link>
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
</template>

<script>
import moment from 'moment';
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
    EventBus.$emit('navegar', 'Nota pendiente');
    if(this.listRolPermisosByUsuario.includes('EsAlumno'))
    {
      this.getMiNotaP();
      this.getEstadoTesis();
    }
    else if(this.listRolPermisosByUsuario.includes('EsProfesor')){
        this.getListarNotasPendientes();
    }
    else{
        this.getListarNotasPendientesByEscuela();
    }
  },
  filters:{
    moment: function (date) {
      moment.locale('es');
      return moment(date).format('DD/MM/YYYY');
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
    getListarNotasPendientesByEscuela(){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getListarNotasPendientesByEscuela'
      axios.get(url, {
        params: {
          'estado' : this.fillBsqNotasPendientes.estado,
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
