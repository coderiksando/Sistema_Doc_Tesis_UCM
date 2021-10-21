<template>
  <div class="card">
    <template v-if="listRolPermisosByUsuario.includes('abandono.index')">
    <div class="card-header">
    </div>
    </template>
    <div class="card-body" style="min-height: 70vh !important">
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
                    <label class="col-md-2 col-form-label">Alumno</label>
                    <div class="col-md-5">
                        <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqTesis.cNombre" @keyup.enter="getListarTesis">
                    </div>
                    <div class="col-md-5">
                        <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqTesis.cApellido" @keyup.enter="getListarTesis">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-2 col-form-label">Estado</label>
                    <div class="col-md-10">
                        <el-select v-model="fillBsqTesis.cEstado"
                        placeholder="Seleccione un estado"
                        filterable
                        autocomplete="estadoFusionadoDeSGYAD"
                        @change="getListarTesis">
                          <el-option
                            v-for="item in globFunct.listStates()"
                            :key="item.id"
                            :label="item.resultado"
                            :value="[item.eI,item.eA]">
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
                                v-model="fillBsqTesis.dateRange.startDate"
                                placeholder="Fecha inicio"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                                :picker-options="startOption"
                                @change="selectStart">
                            </el-date-picker>
                        </div>
                        <div class="col-md-6 pr-0">
                            <el-date-picker
                                v-model="fillBsqTesis.dateRange.endDate"
                                placeholder="Fecha final"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd"
                                :picker-options="endOption"
                                @change="getListarTesis">
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
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarTesis"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Bandeja de resultados</h3>
                </div>
                <div class="col-md-6" style="text-align: right;">
                    <button class="btn btn-secondary" @click.prevent="mostrarModalAyuda">Ayuda
                        <i class="fas fa-question-circle" ></i>
                    </button>
                </div>
            </div>
          </div>
          <div class="card-body table table-responsive" v-loading="fullscreenLoading">
            <template v-if="listTesis.length">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Alumno(s)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarTesisPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.listUsers" :key="index">
                            <div v-text="itemUser.nombres + ' ' + itemUser.apellidos"></div>
                        </div>
                    </td>
                    <td>{{globFunct.mergedStates(item).resultado}}</td>
                    <td>
                      <router-link :title="'Ver '+ terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                        <i class="far fa-eye fa-lg"></i>
                      </router-link>
                        <button v-if="item.aprobado_pg != 'EA'" :disabled="item.estado != 'D'"
                        :title="'Establecer ' + terminoTitulo + ' en abandono'" class="btn btn-danger boton mx-1 btn-w" @click.prevent="setEstadoDocumento(item, 'EA')">
                            <i class="fas fa-box"></i>
                        </button>
                        <button v-else :title="'Establecer ' + terminoTitulo + ' como pendiente'" class="btn btn-success boton mx-1 btn-w" @click.prevent="setEstadoDocumento(item, 'P')">
                            <i class="fas fa-box-open"></i>
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
    props: ['usuario'],
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillBsqTesis:{
        cNombre       : '',
        cApellido     : '',
        cEstadoPg     : '',
        cEstado       : ["",""],
        dateRange: {
            startDate: null,
            endDate: null
        }
      },
      fillVerFIT:{
        cNombre: '',
        cSlug: ''
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      authUser: JSON.parse(localStorage.getItem('authUser')),
      listTesis: 1,
      listAllTesis:1,
      listMiTesis:[],
      listProfesores: [],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 5,
      modalShow: false,
      modalAyuda: false,
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
      rolActivo : JSON.parse(localStorage.getItem('rolActivo')),
      idTesis: '',
      startOption: {
          disabledDate(time) {
          return time.getTime() > Date.now();
          }
      },
      endOption: {
          disabledDate(time) {
          return time.getTime() > Date.now();
          }
      },
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
  created(){
    this.init();
  },
  mounted(){
    EventBus.$emit('navegar', this.terminoTitulo + 's en abandono');
    EventBus.$on('refresh', x => {this.init()});
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarTesis();
      this.selectStart();
    },
    getListarTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarTesis';
      axios.get(url, {
        params:{
          'allStates' :   true,
          'nombre'    :   this.fillBsqTesis.cNombre,
          'apellido'  :   this.fillBsqTesis.cApellido,
          'estadoI'   :   this.fillBsqTesis.cEstadoPg,
          'estado'    :   this.fillBsqTesis.cEstado,
          'fechaSt'   :   (!this.fillBsqTesis.dateRange.startDate) ? '' : this.fillBsqTesis.dateRange.startDate,
          'fechaEn'   :   (!this.fillBsqTesis.dateRange.endDate) ? '' : this.fillBsqTesis.dateRange.endDate,
        }
      }).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
            this.fullscreenLoading = false;
      })
    },
    setEstadoDocumento (item, estado) {
        let titulo = '';
        if (estado == 'EA') titulo = 'Estás seguro que quieres establecer este '+this.terminoTitulo+' en abandono?';
        else titulo = 'Estás seguro que quieres establecer este '+this.terminoTitulo+' como pendiente de revisión?';
        Swal.fire({
            title: titulo,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                this.fullscreenLoading = true;
                var url = '/alumno/setEditarEstadoTesis';
                axios.post(url, {
                        'idTesis': item.id,
                        'estado': estado
                }).then(response => {
                        this.getListarTesis();
                        this.fullscreenLoading = false;
                })
            }
        });
    },
    limpiarCriteriosBsq(){
      this.fillBsqTesis.cNombre = '';
      this.fillBsqTesis.cApellido = '';
      this.fillBsqTesis.cEstado = '';
      this.fillBsqTesis.cEstadoPg = '';
      this.fillBsqTesis.dfecha = '';

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
    selectStart() {
        this.fillBsqTesis.dateRange.endDate = null;
        this.endOption = {
            disabledDate: (time) => {
                return time.getTime() < Date.parse(this.fillBsqTesis.dateRange.startDate) || time.getTime() > Date.now();
            }
        };
    },
  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }

  .mine{
    padding: 1.25rem 1.25rem 0 1.25rem !important;
  }
</style>
