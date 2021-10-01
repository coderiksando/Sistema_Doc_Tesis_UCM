<template>
      <div class="card">
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Criterios de búsqueda de documentos</h3>
                </div>
                <div class="card-body">
                <form role="form">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12 col-form-label">Nombre de alumno</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" v-model="fillBsqTesis.cAlumno">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12 col-form-label">Título</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" v-model="fillBsqTesis.cTitulo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-12 col-form-label">Fecha de creación de documento</label>
                                <label style="width: 100% !important;" class="col-md-6">
                                <el-date-picker
                                    v-model="fillBsqTesis.dFechaInicio"
                                    type="month"
                                    placeholder="Inicio"
                                    format="MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStart">
                                </el-date-picker>
                                </label>
                                <label style="width: 100% !important;" class="col-md-6">
                                <el-date-picker
                                    v-model="fillBsqTesis.dFechaFin"
                                    type="month"
                                    placeholder="Término"
                                    format="MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption">
                                </el-date-picker>
                                </label>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                                :value="item.id">
                            </el-option>
                            </el-select>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                        <label class="col-md-3 col-form-label">Profesor Guía</label>
                        <div class="col-md-9">
                                <el-select filterable v-model="fillBsqTesis.cProfesor"
                                placeholder="Asignar Profesor"
                                clearable>
                                <el-option
                                    v-for="item in listProfesores"
                                    :key="item.id"
                                    :label="item.fullname"
                                    :value="item.fullname">
                                </el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                        <label class="col-md-3 col-form-label">Estado</label>
                        <div class="col-md-9">
                                <el-select filterable v-model="fillBsqTesis.cEstado"
                                placeholder="Estado de aprobación"
                                clearable>
                                <el-option
                                    v-for="item in listEstado"
                                    :key="item.id"
                                    :label="item.detalle"
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
                    <button class="btn btn-flat btn-primary btnWidth" @click.prevent="getListarTesisTerminadas" v-loading.fullscreen.lock="fullscreenLoading"
                        >{{globVar.btnSearch}}</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
                    </div>
                </div>
                </div>
            </div>
            <div class="card card-info">
              <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title">Listado de documentos finalizados</h3>
                    </div>
                </div>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listTesis.length">
                  <table class ="table table-hover table-head-fixed text-nowrap projects" v-loading.fullscreen.lock="fullscreenLoading">
                    <thead>
                      <tr>
                        <th>Alumno(s)</th>
                        <th>Profesor Guía</th>
                        <th>Título</th>
                        <th>Acciones</th>
                        <th>Fecha de creación</th>
                        <th>Escuela</th>
                        <th>Estado aprobación</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarTesisPaginated" :key="index">
                        <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <template v-if="item.fit__user">
                            <div v-for="(itemUser, index) in item.fit__user" :key="index">
                                <p v-text="itemUser.user.nombres + ' ' + itemUser.user.apellidos"></p>
                            </div>
                        </template>
                        </td>
                        <td>
                            <p v-text="item.user__p__guia.nombres + ' ' + item.user__p__guia.apellidos"></p>
                        </td>
                        <td style="min-width: 200px">
                            <textarea v-bind:id='"text-" + index' readonly v-model="item.titulo" rows="1" @click="resizeTextarea" @keyup="resizeTextarea">
                            </textarea>
                        </td>
                        <td>
                          <router-link :title="'Editar '+terminoTitulo" class="btn boton btn-primary" :to="{name:'editar.tesisfinal', params:{id: item.id}}">
                            <i class="fas fa-edit"></i>
                          </router-link>
                          <!-- <template>
                            <button :title="'Generar documento '+terminoTitulo" class="btn boton btn-warning" @click.prevent="setGenerarDocumento(item.id)">
                              <i class="fas fa-file-download"></i>
                            </button>
                          </template> -->
                          <template v-if="item.archivo_pdf.length">
                             <template v-for="(fileItem, fileIndex) in item.archivo_pdf">
                                 <template v-if="fileItem.tipo_pdf == 'final_t'">
                                    <a :key="'arch'+fileIndex" title="Descargar documento final" class="btn boton btn-success" :href="fileItem.path" target="_blank">
                                        <b>DF</b>
                                    </a>
                                 </template>
                                 <template v-if="fileItem.tipo_pdf == 'acta'">
                                    <a :key="'acta'+fileIndex" title="Descargar acta de defensa" class="btn boton btn-success" :href="fileItem.path" target="_blank">
                                        <b>AD</b>
                                    </a>
                                 </template>
                                 <template v-if="fileItem.tipo_pdf == 'constancia_t'">
                                    <a :key="'const'+fileIndex" title="Descargar constancia de examen" class="btn boton btn-success" :href="fileItem.path" target="_blank">
                                        <b>CE</b>
                                    </a>
                                 </template>
                                 <template></template>
                            </template>
                          </template>
                        </td>
                        <td>
                            <p>{{moment(item.fecha).format("DD-MM-YYYY")}}</p>
                        </td>
                        <td>
                            <p v-text="item.escuela.nombre"></p>
                        </td>
                        <td>
                          <template v-if="item.estado == 'D'">
                            <span class="badge badge-warning" >En desarrollo</span>
                          </template>
                          <template v-else-if="item.estado == 'A'">
                            <span class="badge badge-success" >Aprobada</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger" >Reprobada</span>
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
import moment from "moment";
import Multiselect from 'vue-multiselect';
import globVar from '../../../services/globVar';
export default {
  components: { Multiselect },
  props: ['usuario'],
  data(){
    return{
        globVar: new globVar(),
        moment: moment,
        fillBsqTesis:{
            cAlumno: '',
            cTitulo: '',
            cProfesor: '',
            cEstado: '',
            nIdEscuela: '',
            dFechaInicio: null,
            dFechaFin: null
        },
        listEstado:[
            {id: 'A', detalle:'Aprobado'},
            {id: 'R', detalle:'Reprobado'},
            {id: 'D', detalle:'Desarrollo'}
        ],
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
        terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
        listTesis: 1,
        fullscreenLoading: false,
        pageNumber: 0,
        perPage: 5,
        error: 0,
        mensajeError:[],
        listEscuelas: [],
        listProfesores: [],
        pickerOptions: {
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
    this.getListarEscuelas();
    this.getListarProfesorByEscuela();
    this.getListarTesisTerminadas();
    this.selectStart();
  },
  mounted(){
    EventBus.$emit('navegar', 'Documentos finalizados');
  },
  methods:{
    setGenerarDocumento(nIdTesis){
        //this.fullscreenLoading = true;
        var config = {
            responseType: 'blob'
        }
        var url = '/administracion/tesis/setGenerarDocumento'
        axios.post(url, {
            'nIdTesis' :nIdTesis
        }, config).then(response => {
            var oMyBlob = new Blob([response.data], {type : 'application/pdf'});
            var url = URL.createObjectURL(oMyBlob);
            window.open(url);
        })
    },
    getListarTesisTerminadas(){
        this.fullscreenLoading = true;
        var url = '/alumno/getListarTesisTerminadas';
        axios.get(url,
        {params: {
            'cAlumno'       : this.fillBsqTesis.cAlumno,
            'cTitulo'       : this.fillBsqTesis.cTitulo,
            'nIdEscuela'    : this.fillBsqTesis.nIdEscuela,
            'cProfesor'     : this.fillBsqTesis.cProfesor,
            'cEstadoTesis'  : this.fillBsqTesis.cEstado,
            'dFechaInicio'  : this.fillBsqTesis.dFechaInicio,
            'dFechaFin'     : this.fillBsqTesis.dFechaFin,
        }},
        ).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
            this.listTesis.forEach(archivoPdf => {
                archivoPdf.archivo_pdf = _.orderBy(archivoPdf.archivo_pdf, "tipo_pdf", "asc");
            });
            this.fullscreenLoading = false;
        })
    },
    getListarEscuelas(){
        this.fullscreenLoading = true;
        var url = '/administracion/escuelas/getListarEscuelas'
        axios.get(url, {
        }).then(response => {
            this.listEscuelas = response.data;
        })
    },
    getListarProfesorByEscuela(){
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
            this.fullscreenLoading = false;
    },
    limpiarCriteriosBsq(){
        this.fillBsqTesis.cAlumno = '';
        this.fillBsqTesis.cTitulo = '';
        this.fillBsqTesis.cProfesor = '';
        this.fillBsqTesis.cEstado = '';
        this.fillBsqTesis.nIdEscuela = '';
        this.fillBsqTesis.dFechaInicio = null;
        this.fillBsqTesis.dFechaFin = null;
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
        this.fillBsqTesis.dFechaFin = null;
        this.endOption = {
            disabledDate: (time) => {
                return time.getTime() < Date.parse(this.fillBsqTesis.dFechaInicio) || time.getTime() > Date.now();
            }
        };
    },
    resizeTextarea(e) {
      let area = e.target;
      if(area.style.height != area.scrollHeight + 'px'){
        area.style.height = area.scrollHeight + 'px'
      }else{
        area.style.height = null;
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
  .el-date-editor.el-input, .el-date-editor.el-input__inner {
    width: 100%;
  }

  .mine{
    padding: 1.25rem 1.25rem 0 1.25rem !important;
  }
</style>
