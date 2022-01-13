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
            <template v-if="listRolPermisosByUsuario.includes('notaspendientes.asignar')">
                <router-link class="btn btn-info bnt-sm" :to="{name: 'notaspendientes.asignar'}">
                    <i class="fas fa-plus-square"></i> Asignar notas pendientes
                </router-link>
            </template>
        </div>
    </div>

    <div class="card-body">
      <div class="container-fluid">

        <div class="card card-info" v-if="!listRolPermisosByUsuario.includes('EsAlumno')">
          <div class="card-header">
            <h3 class="card-title">Criterios de búsqueda</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-2 col-form-label">Alumno</label>
                    <div class="col-md-5">
                        <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqNotasPendientes.cNombre" @keyup.enter="getListarNotasPendientes">
                    </div>
                    <div class="col-md-5">
                        <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqNotasPendientes.cApellido" @keyup.enter="getListarNotasPendientes">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-2 col-form-label">Estado</label>
                    <div class="col-md-10">
                        <el-select v-model="fillBsqNotasPendientes.cEstado"
                        placeholder="Seleccione un estado"
                        filterable
                        autocomplete="estadoFusionadoDeSGYAD"
                        @change="init">
                          <el-option
                            v-for="item in estadosNotaP"
                            :key="item.id"
                            :label="item.label"
                            :value="item.id">
                          </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-12 col-form-label">Rango de fechas</label>
                    <div class="col-md-12 form-group row pr-0">
                        <div class="col-md-6 pr-0">
                            <el-date-picker
                                v-model="fillBsqNotasPendientes.dFechaInicio"
                                placeholder="Fecha inicio"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </div>
                        <div class="col-md-6 pr-0">
                            <el-date-picker
                                v-model="fillBsqNotasPendientes.dFechaFin"
                                placeholder="Fecha final"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>

          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="init"
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
          <div class="card-body table-responsive">
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
                <tbody v-loading="fullscreenLoading">
                  <tr v-for="(item, index) in listarNotasPendientesPaginated" :key="index">
                    <td>
                        {{item.alumno.nombres + ' ' + item.alumno.apellidos}}
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
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
export default {
  props: ['usuario'],
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillBsqNotasPendientes:{
        cNombre: '',
        cApellido: '',
        cEstado: 0,
        dFechaInicio: '',
        dFechaFin: ''
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
      perPage: 10,
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      estadosNotaP: [
        {id: 0, label: 'Todas'},
        {id: 1, label: 'Sin prorroga'},
        {id: 2, label: 'Con prorroga'}
      ]
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
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  filters:{
    moment: function (date) {
      moment.locale('es');
      return moment(date).format('DD/MM/YYYY');
    }
  },
  methods:{
    init(){
      if(this.listRolPermisosByUsuario.includes('EsAlumno'))
      {
        this.getMiNotaP();
        this.getEstadoTesis();
      }
      else if(this.listRolPermisosByUsuario.includes('EsProfesor')){
          this.getListarNotasPendientes();
      }
      else if (this.listRolPermisosByUsuario.includes('fid.acceso.parcial')){
          this.getListarNotasPendientesByEscuela(1);
      }
      else if (this.listRolPermisosByUsuario.includes('fid.acceso.total')){
          this.getListarNotasPendientesByEscuela(0);
      }
    },
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
      this.fillBsqNotasPendientes.dFechaFin = '';
      this.fillBsqNotasPendientes.dFechaInicio = '';
      this.fillBsqNotasPendientes.cNombre = '';
      this.fillBsqNotasPendientes.cApellido = '';
    },
    limpiarBandejaNotasPendientes(){
      this.listNotasPendientes = [];
    },
    getListarNotasPendientes(){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getListarNotasPendientes'
      axios.get(url, {
        params: {
          'estado'        : this.fillBsqNotasPendientes.cEstado,
          'nombre'        : this.fillBsqNotasPendientes.cNombre,
          'apellido'      : this.fillBsqNotasPendientes.cApellido,
          'dFechaInicio'  : this.fillBsqNotasPendientes.dFechaInicio,
          'dFechaFin'     : this.fillBsqNotasPendientes.dFechaFin
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listNotasPendientes = response.data;
          console.log(response.data);
          this.fullscreenLoading = false;
      })
    },
    getListarNotasPendientesByEscuela(nivelAcceso){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getListarNotasPendientesByEscuela'
      axios.get(url, {
        params: {
          'estado'        : this.fillBsqNotasPendientes.cEstado,
          'nombre'        : this.fillBsqNotasPendientes.cNombre,
          'apellido'      : this.fillBsqNotasPendientes.cApellido,
          'dFechaInicio'  : this.fillBsqNotasPendientes.dFechaInicio,
          'dFechaFin'     : this.fillBsqNotasPendientes.dFechaFin,
          'nivelAcceso'   : nivelAcceso
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
          console.log(response.data);
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
