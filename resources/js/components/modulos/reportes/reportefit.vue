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
                        <label class="col-md-4 col-form-label">Facultad</label>
                        <div class="col-md-8">
                            <el-select v-model="fillBsqTesisReporte.nIdFacultad" @change="getListarEscuelaByFacultad"
                            placeholder="Asignar facultad" filterable
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
                        <label class="col-md-4 col-form-label">Escuelas</label>
                        <div class="col-md-8">
                            <el-select v-model="fillBsqTesisReporte.nIdEscuela" @change="getListarProfesorByEscuela"
                            placeholder="Asignar Escuela" filterable
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
                            <label class="col-md-4 col-form-label">Título</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" :placeholder="'Título de '+terminoTitulo" v-model="fillBsqTesisReporte.cTitulo">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Rut alumno</label>
                            <div class="col-md-8">
                                <input type="text" placeholder="11111111-1" class="form-control" v-model="fillBsqTesisReporte.nRut">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Profesor</label>
                        <div class="col-md-8">
                                <el-select v-model="fillBsqTesisReporte.nIdProfesor"
                                placeholder="Seleccione un profesor" filterable
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
                            <label class="col-md-4 col-form-label">Avances
                                <el-popover
                                title="Número de avances (Opcional)"
                                placement="top-start"
                                width="400"
                                trigger="hover"
                                content="Ingresa un rango de avances que se han realizado en un trabajo,
                                        donde [0,0] serán todos los trabajos, [0,1] si requiere un trabajo de 0 a 1 avances,
                                        [N,M] buscará los trabajos entre N y M avances.">
                                    <i slot="reference" class="fas fa-question-circle"></i>
                                </el-popover>
                            </label>
                            <div class="col-md-2">
                                <input style="width: 40px; height: 40px; margin-left: auto; margin-right: 0;" type="checkbox" v-model="countAvances" @change="activarAvances">
                            </div>
                            <div class="col-md-2 offset-md-1">
                                <input :disabled="!countAvances" type="number" class="form-control" placeholder="0" v-model="fillBsqTesisReporte.nCantAvances[0]" @input="cambioCantAvance">
                            </div>
                            <label class="col-md-1 col-form-label">
                                <p class="d-flex justify-content-center">a</p>
                            </label>
                            <div class="col-md-2">
                                <input :disabled="!countAvances" type="number" class="form-control" placeholder="0" v-model="fillBsqTesisReporte.nCantAvances[1]" @input="cambioCantAvance">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Estado de nota</label>
                        <div class="col-md-8">
                            <el-select v-model="fillBsqTesisReporte.cEstadoNotap"
                            placeholder="Seleccione un estado" filterable
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
                    <div class="col-md-6"
                        v-on:dblclick="()=>{showSeparatedStates = !showSeparatedStates;
                            fillBsqTesisReporte.cEstadoApr = '';
                            fillBsqTesisReporte.cEstadoIns= '';}">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Estado
                                <el-popover
                                :title="'Estados de '+terminoTitulo"
                                placement="top-start"
                                width="400"
                                trigger="hover"
                                content="Si requiere estados generales o estados sin registrar,
                                        efectúe doble click en la sección 'Estado' y ésta entregará más opciones.">
                                    <i slot="reference" class="fas fa-question-circle"></i>
                                </el-popover>
                            </label>
                            <div v-if="!showSeparatedStates" class="col-md-8">
                                <el-select v-model="fillBsqTesisReporte.cEstadoTesisId"
                                placeholder="Seleccione un estado" filterable>
                                <el-option
                                    v-for="item in globFunct.listStates()"
                                    :key="item.id"
                                    :label="item.resultado"
                                    :value="item.id">
                                </el-option>
                                </el-select>
                            </div>
                            <div v-if="showSeparatedStates" class="col-md-8">
                                <el-input placeholder="Personalizado" disabled></el-input>
                            </div>
                        </div>
                    </div>
                    <template v-if="showSeparatedStates">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Estado de inscripción</label>
                                <div class="col-md-8">
                                    <el-select v-model="fillBsqTesisReporte.cEstadoIns"
                                    placeholder="Seleccione un estado de inscripción" filterable>
                                    <el-option
                                        v-for="item in listEstadosTesisIns"
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
                            <label class="col-md-4 col-form-label">Estado de aprobación</label>
                            <div class="col-md-8">
                                <el-select v-model="fillBsqTesisReporte.cEstadoApr"
                                placeholder="Seleccione un estado" filterable>
                                <el-option
                                    v-for="item in listEstadosTesisApr"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                                </el-select>
                            </div>
                        </div>
                        </div>
                    </template>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">Categoría</label>
                        <div class="col-md-8">
                            <el-select v-model="fillBsqTesisReporte.cTipoVinculación"
                            @change="getListarVinculacion(fillBsqTesisReporte.cTipoVinculación)"
                            placeholder="Seleccione un tipo de vinculación" filterable
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
                        <label class="col-md-4 col-form-label">Vinculación</label>
                        <div class="col-md-8">
                            <el-select v-model="fillBsqTesisReporte.nIdVinculación"
                            placeholder="Seleccione una vinculación" filterable
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
                    <div class="col-md-6 mb-0">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Fecha ingreso alumno</label>
                            <div class="col-md-8">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dfecharango[0]"
                                    placeholder="Desde"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStart">
                                </el-date-picker>
                                <el-date-picker
                                    class="mt-1"
                                    v-model="fillBsqTesisReporte.dfecharango[1]"
                                    placeholder="Hasta"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Fecha de inscripción</label>
                            <div class="col-md-8">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dFechaFID[0]"
                                    placeholder="Desde"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStartFID">
                                </el-date-picker>
                                <el-date-picker
                                    class="mt-1"
                                    v-model="fillBsqTesisReporte.dFechaFID[1]"
                                    placeholder="Hasta"
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
                                <div title="Sección expandible" class="col-md-1" data-toggle="collapse" :data-target="'#collapseFIT'+index" aria-expanded="false" :aria-controls="'collapseFIT'+index"><a class="btn" @click.prevent="focus"><i class="fas fa-plus-circle"></i></a></div>
                                <div title="Sección expandible" class="col-md-11 noPadNoMar" data-toggle="collapse" :data-target="'#collapseFIT'+index" aria-expanded="false" :aria-controls="'collapseFIT'+index" @click.prevent="focus">
                                    <div class="float-left">
                                        {{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.titulo.slice(0, 80))}}{{((item.titulo.length > 80) ? '...':'')}}
                                    </div>
                                    <div class="float-right">
                                        {{item.escuela.nombre}}
                                    </div>
                                    <br>
                                    <div class="float-left">
                                        (Prof. Guía: {{globFunct.cutFullName(item.user__p__guia.nombres,item.user__p__guia.apellidos)}}, Alumno inscriptor: {{globFunct.cutFullName(item.fit__user[0].user.nombres, item.fit__user[0].user.apellidos)}})
                                    </div>
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapseFIT'+index" class="collapse" :aria-labelledby="'headingFitAlumno'+index" data-parent="#reportAccordion">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-3">Escuela:</dt>
                                <dd class="col-md-9">{{item.escuela.nombre}}</dd>
                                <dt class="col-md-3">Alumnos integrantes:</dt>
                                <template v-if="item.fit__user">
                                    <dd class="col-md-9">
                                        <dl class="row mb-0">
                                            <dd class="col-md-4 px-2">
                                                <p class="mb-0 font-weight-bold">Nombre</p>
                                            </dd>
                                            <dd class="col-md-2 px-2">
                                                <p class="mb-0 font-weight-bold">Rut</p>
                                            </dd>
                                            <dd class="col-md-2 px-2">
                                                <p class="mb-0 font-weight-bold">Teléfono</p>
                                            </dd>
                                            <dd class="col-md-4 px-2">
                                                <p class="mb-0 font-weight-bold">Email</p>
                                            </dd>
                                        </dl>
                                        <dl class="row mb-0" v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                            <dd class="col-md-4 px-2">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{globFunct.cutFullName(fitUser.user.nombres, fitUser.user.apellidos)}}</p>
                                            </dd>
                                            <dd class="col-md-2 px-2">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.rut}}</p>
                                            </dd>
                                            <dd class="col-md-2 px-2">
                                                <p v-if="fitUser.user.telefono" style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.telefono}}</p>
                                                <p v-else style="font-size: 0.9rem;" class="mb-0">No registrado</p>
                                            </dd>
                                            <dd class="col-md-4 px-2">
                                                <p style="font-size: 0.9rem;" class="mb-0">{{fitUser.user.email}}</p>
                                            </dd>
                                        </dl>
                                    </dd>
                                </template>
                                <template v-if="item.user__p__guia">
                                    <dt class="col-md-3">Prof. Guía:</dt>
                                    <dd class="col-md-9">{{globFunct.cutFullName(item.user__p__guia.nombres,item.user__p__guia.apellidos)}}</dd>
                                </template>
                                <template v-if="item.user__p__coguia">
                                    <dt class="col-md-3">Prof. Co-guía:</dt>
                                    <dd class="col-md-9">{{globFunct.cutFullName(item.user__p__coguia.nombres,item.user__p__coguia.apellidos)}}</dd>
                                </template>
                                <dt class="col-md-3">Título extendido:</dt>
                                <dd class="col-md-9">{{globFunct.capitalizeFirstLetter(item.titulo)}}</dd>
                                <dt class="col-md-3">Descripción:</dt>
                                <dd class="col-md-9">{{item.descripcion}}</dd>
                                <dt class="col-md-3">Comisión evaluadora:</dt>
                                <dd class="col-md-9">
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
                                                <dd v-if="item.p_externo" class="col-md-8">{{item.p_externo}}</dd>
                                                <dd v-else class="col-md-8">No registrado</dd>
                                            </template>
                                        </dl>
                                    </template>
                                    <template v-else>
                                        <dt>No conformada</dt>
                                    </template>
                                </dd>
                                <dt class="col-md-3">Tipo de documento:</dt>
                                <dd class="col-md-9">{{item.tipo}}</dd>

                                <dt class="col-md-3">Estado:</dt>
                                <dd class="col-md-9">{{globFunct.mergedStates(item).resultado}}</dd>
                                <dt class="col-md-3">Detalles de documento:</dt>
                                <dd class="col-md-9">
                                    <button :title="'Vista completa de '+ terminoTitulo" class="btn boton btn-primary" @click.prevent="redirectTo('tesis.ver',{id: item.id})">
                                        <b>{{terminoTitulo}}</b>
                                    </button>
                                    <button title="Sección de avances" :disabled="item.avances_tesis.length <= 0" class="btn boton btn-primary" @click.prevent="redirectTo('reportes.detallesavances', {id: item.id, estado: item.estado})">
                                        <b>Av</b>
                                    </button>
                                    <button title="Sección de Actas de Reunión" :disabled="item.bitacoras.length <= 0" class="btn boton btn-primary"  @click.prevent="redirectTo('reportes.detallesbitacoras', {id: item.id})">
                                        <b>AR</b>
                                    </button>
                                </dd>
                                <dt class="col-md-3">Descarga de documento:</dt>
                                <dd class="col-md-9">
                                    <button :disabled="!item.ultimoDoc" :key="'arch'+index" title="Descargar documento" class="btn boton btn-info" @click.prevent="redirectTo((item.ultimoDoc) ? item.ultimoDoc.path: '')">
                                        <b>Doc</b>
                                    </button>
                                    <button :disabled="!item.acta" :key="'acta'+index" title="Descargar acta de defensa" class="btn boton btn-info" @click.prevent="redirectTo((item.acta) ? item.acta.path : '')">
                                        <b>Ac</b>
                                    </button>
                                    <button :disabled="!item.constancia" :key="'constancia'+index" title="Descargar constancia" class="btn boton btn-info" @click.prevent="descargarConstancia(item)">
                                        <b>CE</b>
                                    </button>
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
        authUser: JSON.parse(localStorage.getItem('authUser')),
        fillBsqTesisReporte:{
          nRut: '',
          nIdEscuela:'',
          nIdFacultad: '',
          nIdProfesor:'',
          nIdTesis: '',
          cEstadoNotap: '',
          cTitulo: '',
          cNombreI1: '',
          cEstadoD: '',
          dfecharango: [],
          cEstadoTesisId: -1,
          cEstadoIns: '',
          cEstadoApr: '',
          cTipoVinculación: '',
          nIdVinculación: '',
          cTitulo: '',
          nCantAvances: [null,null],
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
          {value: '',  label: 'Todas'},
          {value: 'S', label: 'Sin nota pendiente'},
          {value: 'A', label: 'Nota pendiente activa'},
          {value: 'V', label: 'Nota pendiente vencida'}
        ],
        listTipoDeTrabajo: [
          {value: '', label: 'Todos'},
          {value: 'Tesis', label: 'Tesis'},
          {value: 'Memoria', label: 'Memoria'},
          {value: 'Proyecto de titulo', label: 'Proyecto de titulo'}
        ],
        listTipoVinculacion: [],
        listVinculacion: [],
        listEstadosTesisIns: [
          {value: '',  label: 'Todos'},
          {value: 'P', label: 'Pendiente'},
          {value: 'R', label: 'Rechazado'},
          {value: 'A', label: 'Aprobado'},
          {value: 'V', label: 'Verificado'},
          {value: 'EA',label: 'En abandono'}
        ],
        listEstadosTesisApr: [
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
        showSeparatedStates: false,
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
        },
        countAvances: false,
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
      EventBus.$on('refresh', x => {this.init()});
      this.init();
    },
    beforeDestroy(){
        EventBus.$off('refresh');
    },
    methods:{
        init(){
            this.getListarVinculacion();
            this.getListarTipoVinculacion();
            this.selectStart();
            this.selectStartFID();
            if (this.listRolPermisosByUsuario.includes('reportes.general')){
                this.getListarFacultades();
            }else{
                this.fillBsqTesisReporte.nIdEscuela = this.authUser.id_escuela;
                this.booleanFunction.facultad = true;
                this.booleanFunction.escuela = true;
            }
            this.getListarProfesorByEscuela();
        },
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
                this.listEscuelas.unshift({'id':'', 'nombre': 'Todas', 'facultad': {'id':0}});
                if (this.listRolPermisosByUsuario.includes('reportes.general')) {
                    this.fillBsqTesisReporte.nIdEscuela = '';
                }
                this.booleanFunction.escuela = true;
                if (this.globFunct.booleanElements(this.booleanFunction)) this.fullscreenLoading = false;
                this.getListarEscuelaByFacultad();
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
                this.getListarEscuelas();
            })
        },
        getListarEscuelaByFacultad(){
            this.fullscreenLoading = true;
            this.fillBsqTesisReporte.nIdEscuela = '';
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
            console.log(this.fillBsqTesisReporte.nIdEscuela);
            var url = '/administracion/reportes/getListarTesisReporte';
            if (!this.showSeparatedStates) {
                this.fillBsqTesisReporte.cEstadoIns = this.globFunct.listStates().find(item => item.id == this.fillBsqTesisReporte.cEstadoTesisId).eI;
                this.fillBsqTesisReporte.cEstadoApr = this.globFunct.listStates().find(item => item.id == this.fillBsqTesisReporte.cEstadoTesisId).eA;
            }
            axios.get(url, {
            params: {
                'nRut'              : this.fillBsqTesisReporte.nRut,
                'nIdFacultad'       : this.fillBsqTesisReporte.nIdFacultad,
                'nIdEscuela'        : this.fillBsqTesisReporte.nIdEscuela,
                'cTipoVinculación'  : this.fillBsqTesisReporte.cTipoVinculación,
                'nIdVinculación'    : this.fillBsqTesisReporte.nIdVinculación,
                'cEstadoNotap'      : this.fillBsqTesisReporte.cEstadoNotap,
                'nIdProfesor'       : this.fillBsqTesisReporte.nIdProfesor,
                'cEstadoIns'        : this.fillBsqTesisReporte.cEstadoIns,
                'cEstadoApr'        : this.fillBsqTesisReporte.cEstadoApr,
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
                response.data.forEach(data => {
                    data.estadoGeneral = this.globFunct.mergedStates(data);
                });
                this.listTesisOriginal = response.data;
                this.listTesis = response.data;
                const newListTesis = this.listTesis;
                this.listTesisOriginal = this.listTesisOriginal;
                console.log(this.listTesisOriginal)
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
        },
        redirectTo(route, objectId, newTab) {
            if (route.search("http") !== 0) {
                if (!newTab) {
                    if (objectId) this.$router.push({name: route, params: objectId});
                    else this.$router.push({name: route});
                } else {
                    const ruteData = this.$router.resolve({name: route, params: objectId});
                    window.open(ruteData.href, '_blank');
                }
            } else window.open(route, '_blank');
        },
        focus(e){
            setTimeout(x => {
            window.scrollBy({
                top: e.target.getBoundingClientRect().top -80,
                behavior: 'smooth'
            });
            },350);
        },
        print() {
            console.log(this.fillBsqTesisReporte);
        },
        activarAvances(){
            if (!this.countAvances) {
                this.fillBsqTesisReporte.nCantAvances[0] = null;
                this.fillBsqTesisReporte.nCantAvances[1] = null;
            }else{
                this.fillBsqTesisReporte.nCantAvances[0] = 0;
                this.fillBsqTesisReporte.nCantAvances[1] = 0;
            }
        },
        descargarConstancia(fid){
      if (fid.fit__user.length == 1) {
        this.redirectTo(fid.constancia.path);
      }else{
        var url = '/archivo/descargaZipByFid';
        let postConfig = {
              headers: {'X-Requested-With': 'XMLHttpRequest'},
              responseType: 'blob',
          }
        axios.post(url,
        {
          nIdFid: fid.id,
          cTipo: 'constancia_t'
        },
        postConfig
        ).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/zip'});
          var url = document.createElement('a')
          url.href = URL.createObjectURL(oMyBlob);
          url.download = 'Constancias.zip';
          url.click();
        });
      }
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
