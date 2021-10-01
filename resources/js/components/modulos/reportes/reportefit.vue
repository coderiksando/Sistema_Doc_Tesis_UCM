<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
          <div class="card card-info">
            <div class="btn card-header" data-toggle="collapse" href="#busquedaCollapse" role="button" aria-expanded="false" aria-controls="busquedaCollapse">
                <h3 class="card-title">Criterios de búsqueda</h3>
            </div>
          <div class="collapse show" id="busquedaCollapse">
            <div class="card-body">
                <form role="form">
                <div class="row">
                    <div class="col-md-6" v-if="listRolPermisosByUsuario.includes('reportes.general')">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Facultad</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.nIdFacultad" @change="getListarEscuelaByFacultad"
                            placeholder="Asignar facultad"
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
                    <div class="col-md-6" v-if="listRolPermisosByUsuario.includes('reportes.general')">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Escuelas</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.nIdEscuela" @change="getListarProfesorByEscuela"
                            placeholder="Asignar Escuela"
                            >
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
                            <label class="col-md-3 col-form-label">Título</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" :placeholder="'Título de '+terminoTitulo" v-model="fillBsqTesisReporte.cTitulo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rut alumno</label>
                            <div class="col-md-9">
                                <input type="text" placeholder="11111111-1" class="form-control" v-model="fillBsqTesisReporte.nRut">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Profesor</label>
                        <div class="col-md-9">
                                <el-select v-model="fillBsqTesisReporte.nIdProfesor"
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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Cantidad de avances</label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="0" v-model="fillBsqTesisReporte.nCantAvances[0]" @input="cambioCantAvance">
                            </div>
                            <label class="col-md-1 col-form-label"><p class="d-flex justify-content-center">a</p></label>
                            <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="0" v-model="fillBsqTesisReporte.nCantAvances[1]" @input="cambioCantAvance">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nota pendiente</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.cEstadoNotap"
                            placeholder="Seleccione un estado"
                            clearable>
                            <el-option
                                v-for="item in listTipoDeNotap"
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
                        <label class="col-md-3 col-form-label">Estado del documento</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.cEstadoTesis"
                            placeholder="Seleccione un estado"
                            clearable>
                            <el-option
                                v-for="item in listEstadosTesis"
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
                        <label class="col-md-3 col-form-label">Tipo de vinculación</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.cTipoVinculación"
                            @change="getListarVinculacion(fillBsqTesisReporte.cTipoVinculación)"
                            placeholder="Seleccione una vinculación"
                            clearable>
                            <el-option
                                v-for="item in listTipoVinculacion"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                            </el-select>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Vinculación</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.nIdVinculación"
                            placeholder="Seleccione una vinculación"
                            clearable>
                            <el-option
                                v-for="item in listVinculacion"
                                :key="item.id"
                                :label="item.nombre"
                                :value="item.id"
                                >
                            </el-option>
                            </el-select>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Rango de fechas de estudio de alumno</label>
                            <div class="col-md-5">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dfecharango[0]"
                                    placeholder="Inicio"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStart">
                                </el-date-picker>
                            </div>
                            <div class="col-md-5">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dfecharango[1]"
                                    placeholder="Término"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Rango de fechas de creación de {{terminoTitulo}}</label>
                            <div class="col-md-5">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dFechaFID[0]"
                                    placeholder="Inicio"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStartFID">
                                </el-date-picker>
                            </div>
                            <div class="col-md-5">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dFechaFID[1]"
                                    placeholder="Término"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOptionFID">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="row">
                <div class="col-md-4 offset-4">
                    <!-- consulta los datos de la fecha son iguales en forma booleana  -->
                    <button :disabled="!!fillBsqTesisReporte.dfecharango[0] !== !!fillBsqTesisReporte.dfecharango[1]"
                    class="btn btn-flat btn-info btnWidth" @click.prevent="getListarTesisReporte"
                    v-loading.fullscreen.lock="fullscreenLoading">
                        {{globVar.btnSearch}}
                    </button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
                </div>
                </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <div class="row">
              <div class="col-md-10">
                <h3 class="card-title">Bandeja de resultados</h3>
              </div>
              <div class="col-md-2">
                <template v-if="listTesis.length">
                    <el-tooltip class="item" effect="dark" content="Exportar en Formato Excel" placement="right">
                      <el-button @click.prevent="setGenerarDocumento">
                      <i  class="fas fa-file-excel"></i>
                      Exportar
                      </el-button>
                    </el-tooltip>
                </template>
              </div>
            </div>
          </div>
          <div id="reportAccordion">
            <template v-if="listTesis.length">
                <template v-for="(item, index) in listTesis">
                    <div class="card mb-1" :key="'fitAlumno'+index">
                        <div class="card-body py-1" :id="'headingFitAlumno'+index">
                        <h3 class="mb-0">
                            <div class="btn btn-link col-md-12 noPadNoMar d-flex">
                                <div title="Sección expandible" class="col-md-1"  data-toggle="collapse" :data-target="'#collapseFIT'+index" aria-expanded="false" :aria-controls="'collapseFIT'+index"><a class="btn"><i class="fas fa-plus-circle"></i></a></div>
                                <div title="Sección expandible" class="col-md-8 noPadNoMar"  data-toggle="collapse" :data-target="'#collapseFIT'+index" aria-expanded="false" :aria-controls="'collapseFIT'+index">
                                    <p class="float-left">
                                        {{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.titulo.slice(0, 40))}}
                                    </p>
                                </div>
                                <div class="col-md-3 noPadNoMar">
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapseFIT'+index" class="collapse" :aria-labelledby="'headingFitAlumno'+index" data-parent="#reportAccordion">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-4">Alumnos integrantes:</dt>
                                <template v-if="item.fit__user">
                                    <dd class="col-md-8">
                                        <dl class="row mb-0">
                                            <dd class="col-md-4 px-1">
                                                <p class="mb-0 font-weight-bold">Nombre</p>
                                            </dd>
                                            <dd class="col-md-2 px-1">
                                                <p class="mb-0 font-weight-bold">Rut</p>
                                            </dd>
                                            <dd class="col-md-2 px-1">
                                                <p class="mb-0 font-weight-bold">Teléfono</p>
                                            </dd>
                                            <dd class="col-md-4 px-1">
                                                <p class="mb-0 font-weight-bold">Email</p>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-0" v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                            <dd class="col-md-4 px-1">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{globFunct.cutFullName(fitUser.user.nombres, fitUser.user.apellidos)}}</p>
                                            </dd>
                                            <dd class="col-md-2 px-1">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.rut}}</p>
                                            </dd>
                                            <dd class="col-md-2 px-1">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.telefono}}</p>
                                            </dd>
                                            <dd class="col-md-4 px-1">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.email}}</p>
                                            </dd>
                                        </dl>
                                    </dd>
                                </template>
                                <dt class="col-md-4">Escuela:</dt>
                                <dd class="col-md-8">{{item.escuela.nombre}}</dd>
                                <dt class="col-md-4">Título extendido:</dt>
                                <dd class="col-md-8">{{globFunct.capitalizeFirstLetter(item.titulo)}}</dd>
                                <dt class="col-md-4">Descripción:</dt>
                                <dd class="col-md-8">{{item.descripcion}}</dd>
                                <template v-if="item.user__p__guia">
                                    <dt class="col-md-4">Prof. Guía:</dt>
                                    <dd class="col-md-8">{{globFunct.cutFullName(item.user__p__guia.nombres,item.user__p__guia.apellidos)}}</dd>
                                </template>
                                <template v-if="item.user__p__coguia">
                                    <dt class="col-md-4">Prof. Co-guía:</dt>
                                    <dd class="col-md-8">{{globFunct.cutFullName(item.user__p__coguia.nombres,item.user__p__coguia.apellidos)}}</dd>
                                </template>
                                <dt class="col-md-4">Comisión evaluadora:</dt>
                                <dd class="col-md-8">
                                    <template v-if="item.comisiones">
                                        <dl class="row">
                                            <template v-if="item.comisiones.user_p1">
                                                <dt class="col-md-4">1° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{globFunct.cutFullName(item.comisiones.user_p1.nombres, item.comisiones.user_p1.apellidos)}}</dd>
                                            </template>
                                            <template v-if="item.comisiones.user_p2">
                                                <dt class="col-md-4">2° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{globFunct.cutFullName(item.comisiones.user_p2.nombres,item.comisiones.user_p2.apellidos)}}</dd>
                                            </template>
                                            <template>
                                                <dt class="col-md-4">Profesor(es) Externo(s):</dt>
                                                <dd class="col-md-8">{{item.p_externo}}</dd>
                                            </template>
                                        </dl>
                                    </template>
                                    <template v-else>
                                        <dt>No conformada</dt>
                                    </template>
                                </dd>
                                <dt class="col-md-4">Tipo de documento:</dt>
                                <dd class="col-md-8">{{item.tipo}}</dd>

                                <dt class="col-md-4">Estado de aprobación:</dt>
                                <dd class="col-md-8" v-if="item.estado == 'A'">Aprobado</dd>
                                <dd class="col-md-8" v-if="item.estado == 'D'">Desarrollo</dd>
                                <dd class="col-md-8" v-if="item.estado == 'R'">Reprobado</dd>
                                <dt class="col-md-4">Detalles de documento:</dt>
                                <dd class="col-md-8">
                                    <router-link :title="'Vista completa de '+ terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                                        <b>{{terminoTitulo}}</b>
                                    </router-link>
                                    <router-link title="Sección de avances" v-if="item.avances_tesis.length > 0" class="btn boton btn-success" :to="{name:'reportes.detallesavances', params:{id: item.id, estado: item.estado}}">
                                        <b>Av</b>
                                    </router-link>
                                    <router-link title="Sección de Actas de Reunión" v-if="item.bitacoras.length > 0" class="btn boton btn-secondary" :to="{name:'reportes.detallesbitacoras', params:{id: item.id}}">
                                        <b>AR</b>
                                    </router-link>
                                </dd>
                                <dt class="col-md-4">Descarga de documento:</dt>
                                <dd class="col-md-8">
                                    <template v-if="item.ultimoDoc">
                                        <a :key="'arch'+index" title="Descargar documento" class="btn boton btn-success" :href="item.ultimoDoc.path" target="_blank">
                                            <b>Doc</b>
                                        </a>
                                    </template>
                                    <template v-if="item.acta">
                                        <a :key="'acta'+index" title="Descargar acta de defensa" class="btn boton btn-warning" :href="item.acta.path" target="_blank">
                                            <b>Ac</b>
                                        </a>
                                    </template>
                                    <template v-if="item.constancia">
                                        <a :key="'constancia'+index" title="Descargar constancia" class="btn boton btn-info" :href="item.constancia.path" target="_blank">
                                            <b>C</b>
                                        </a>
                                    </template>
                                </dd>
                            </dl>
                        </div>
                        </div>
                    </div>
                </template>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item" v-if="pageNumber > 0">
                        <a href="#" class="page-link" @click.prevent="prevPage2">Ant</a>
                        </li>
                        <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                        :class="[page == pageNumber ? 'active' : '']"
                        :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
                        <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
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
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import moment from "moment";
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
  export default {
    props: ['usuario'],
    data(){
      return{
        globVar: new globVar(),
        globFunct: new globFunct(),
        moment: moment,
        fillBsqTesisReporte:{
          nRut: '',
          nIdEscuela:'',
          nIdFacultad: '',
          nIdProfesor:'',
          nIdTesis: '',
          cEstadoNotap: '',
          cTitulo: '',
          cNombreI1: '',
          cEstadoPg: '',
          cEstadoD: '',
          dfecharango: [],
          cEstadoTesis: '',
          cTipoVinculación: '',
          nIdVinculación: '',
          cTitulo: '',
          nCantAvances: [0,0],
          dFechaFID: []
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
        terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
        listFechaUR: [
          {value: '1', label: 'hace 1 año'},
          {value: '2', label: 'hace 2 años'},
          {value: '3', label: 'hace 3 años'},
          {value: '4', label: 'mas de 3 años'}
        ],
        listTipoDeNotap: [
          {value: '', label: 'Sin nota pendiente'},
          {value: 'A', label: 'Activa'},
          {value: 'V', label: 'Vencida'}
        ],
        listTipoDeTrabajo: [
          {value: '', label: 'Todos'},
          {value: 'Tesis', label: 'Tesis'},
          {value: 'Memoria', label: 'Memoria'},
          {value: 'Proyecto de titulo', label: 'Proyecto de titulo'}
        ],
        listTipoVinculacion: [],
        listVinculacion: [],
        listEstadosTesis: [
          {value: '', label: 'Todos'},
          {value: 'A', label: 'Aprobada'},
          {value: 'R', label: 'Reprobada'},
          {value: 'D', label: 'En Desarrollo'}
        ],
        listTesis: [],
        listTesisOriginal: [],
        listFacultades: [],
        listEscuelas: [],
        listEscuelasOriginal: [],
        listProfesores: [],
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
        endOptionFID: {
          disabledDate(time) {
            return time.getTime() > Date.now();
          }
        },
        booleanFunction: {
            vinculacion: false,
            escuela: false,
            facultad: false,
            profesor: false,
            tipoVinculacion: false
        }
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
      EventBus.$emit('navegar', 'Reportes de documentos');
      this.getListarVinculacion();
      this.getListarTipoVinculacion();
      this.getListarEscuelas();
      this.getListarFacultades();
      this.getListarProfesorByEscuela();
      this.selectStart();
      this.selectStartFID();
    },
    methods:{
        getListarVinculacion(tipo){
            this.fullscreenLoading = true;
            const url = '/administracion/vinculacion/getListarVinculacion';
            axios.get(url, {
                params: {
                    'cTipo': tipo
                }
            }).then(response => {
                this.listVinculacion = response.data;
                this.booleanFunction.vinculacion = true;
                if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
            })
        },
        getListarTipoVinculacion(){
          this.fullscreenLoading = true;
          const url = '/administracion/vinculacion/getListarTipoVinculacion';
          axios.get(url, {
          }).then(response => {
              this.listTipoVinculacion = response.data;
              this.booleanFunction.tipoVinculacion = true;
              if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
          })
        },
        getListarEscuelas(){
            this.fullscreenLoading = true;
            var url = '/administracion/escuelas/getListarEscuelas'
            axios.get(url, {
            }).then(response => {
                this.listEscuelasOriginal = response.data;
                this.listEscuelas = response.data;
                if (this.listRolPermisosByUsuario.includes('reportes.general')) {
                  this.listEscuelas.unshift({'id':'', 'nombre': 'Todas', 'facultad': {'id':0}});
                }
                this.booleanFunction.escuela = true;
                if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
            })
        },
        getListarFacultades(){
            this.fullscreenLoading = true;
            var url = '/administracion/escuelas/getListarFacultades'
            axios.get(url, {
            }).then(response => {
                this.listFacultades = response.data;
                this.booleanFunction.facultad = true;
                if (this.listFacultades){
                    let primerFacultad = this.listFacultades.find(facultad => facultad.nombre == 'Facultad de Ciencias de la Ingeniería')
                    this.fillBsqTesisReporte.nIdFacultad = primerFacultad.id;
                }
                if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
            })
        },
        getListarEscuelaByFacultad(){
            this.fullscreenLoading = true;
            this.listEscuelas = this.listEscuelasOriginal;
            const idFacultad = this.fillBsqTesisReporte.nIdFacultad;
            if (idFacultad != '') {
                this.listEscuelas = this.listEscuelas.filter(function(escuela) {
                    return escuela.facultad.id === idFacultad || escuela.id == 0;
                });
            }
            this.fullscreenLoading = false;
        },
        getListarProfesorByEscuela(){
            this.fullscreenLoading = true;
            var url = '/reportes/getListarProfesorByEscuela'
            axios.get(url, {
            params: {
                'nIdEscuela' : this.fillBsqTesisReporte.nIdEscuela,
            }
            }).then(response => {
                this.listProfesores = response.data;
                this.booleanFunction.profesor = true;
                if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
            })
        },
        setGenerarDocumento(){
            //this.fullscreenLoading = true;
            var url = '/administracion/reportes/export';
            let postConfig = {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                responseType: 'blob',
            }
            axios.post(url,
            {
                'listTesis': JSON.stringify(this.listTesisOriginal)
            }, postConfig
            ).then(response => {
            var oMyBlob = new Blob([response.data], {type : 'application/vnd.ms-excel'});
            var url = document.createElement('a')
            url.href = URL.createObjectURL(oMyBlob);
            url.download = 'usuarios.xlsx'
            url.click()
            })
        },
        getListarTesisReporte(){
            this.fullscreenLoading = true;
            var url = '/administracion/reportes/getListarTesisReporte';
            axios.get(url, {
            params: {
                'nRut'              : this.fillBsqTesisReporte.nRut,
                'nIdFacultad'       : this.fillBsqTesisReporte.nIdFacultad,
                'nIdEscuela'        : this.fillBsqTesisReporte.nIdEscuela,
                'cTipoVinculación'  : this.fillBsqTesisReporte.cTipoVinculación,
                'nIdVinculación'    : this.fillBsqTesisReporte.nIdVinculación,
                'cEstadoNotap'      : this.fillBsqTesisReporte.cEstadoNotap,
                'nIdProfesor'       : this.fillBsqTesisReporte.nIdProfesor,
                'cEstadoTesis'      : this.fillBsqTesisReporte.cEstadoTesis,
                'dFechaInicio'      : (!this.fillBsqTesisReporte.dfecharango) ? '' : this.fillBsqTesisReporte.dfecharango[0],
                'dFechaFin'         : (!this.fillBsqTesisReporte.dfecharango) ? '' : this.fillBsqTesisReporte.dfecharango[1],
                'cTitulo'           : this.fillBsqTesisReporte.cTitulo,
                'nCantAvances0'     : this.fillBsqTesisReporte.nCantAvances[0],
                'nCantAvances1'     : this.fillBsqTesisReporte.nCantAvances[1],
                'dFechaFIDIni'      : this.fillBsqTesisReporte.dFechaFID[0],
                'dFechaFIDFin'      : this.fillBsqTesisReporte.dFechaFID[1]
            }
            }).then(response => {
                this.inicializarPaginacion();
                this.listTesisOriginal = response.data;
                this.listTesis = response.data;
                console.log(this.listTesis)
                const newListTesis = this.moveIndex(this.listTesis);
                this.listTesisOriginal = this.moveIndex(this.listTesisOriginal);
                this.listTesis = newListTesis;
                this.fullscreenLoading = false;
            })
        },
        limpiarCriteriosBsq(){
            this.fillBsqTesisReporte.nRut = '';
            this.fillBsqTesisReporte.nIdEscuela = '';
            this.fillBsqTesisReporte.cEstadoNotap = '';
            this.fillBsqTesisReporte.nIdProfesor = '';
            this.fillBsqTesisReporte.cEstadoTesis = '';
            this.fillBsqTesisReporte.dfecharango = '';
            this.fillBsqTesisReporte.nIdVinculación = '';
            this.fillBsqTesisReporte.nIdFacultad = '';
            this.getListarEscuelaByFacultad();
            if (this.listRolPermisosByUsuario.includes('reportes.general')) {
                this.fillBsqTesisReporte.nIdEscuela = 0;
            }

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
        selectStart() {
            this.fillBsqTesisReporte.dfecharango[1] = null;
            this.endOption = {
                disabledDate: (time) => {
                    return time.getTime() < Date.parse(this.fillBsqTesisReporte.dfecharango[0]) || time.getTime() > Date.now();
                }
            };
        },
        selectStartFID() {
            this.fillBsqTesisReporte.dFechaFID[1] = null;
            this.endOptionFID = {
                disabledDate: (time) => {
                    return time.getTime() < Date.parse(this.fillBsqTesisReporte.dFechaFID[0]) || time.getTime() > Date.now();
                }
            };
        },
        moveIndex( listOfElements ) {
            let i=0;
            let newList = [];
            if (Array.isArray(listOfElements)) {
                listOfElements.forEach(element => {
                    if (element) {
                        newList[i]=element;
                        i++;
                    }
                });
            } else {
                const array = Object.keys(listOfElements);
                const key = array[0];
                newList[0] = listOfElements[key];
            }
            return newList;
        },
        cambioCantAvance() {
            this.fillBsqTesisReporte.nCantAvances[0] = Math.abs(this.fillBsqTesisReporte.nCantAvances[0]);
            if (this.fillBsqTesisReporte.nCantAvances[0]>=this.fillBsqTesisReporte.nCantAvances[1])
                this.fillBsqTesisReporte.nCantAvances[1]=JSON.parse(JSON.stringify(this.fillBsqTesisReporte.nCantAvances[0]));
        }

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
.disabled{
    cursor: not-allowed;
    opacity: .65;
}
</style>
