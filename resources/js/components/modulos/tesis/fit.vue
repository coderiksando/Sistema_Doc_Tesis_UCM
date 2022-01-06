<template>
  <div class="card">
    <template v-if="listRolPermisosByUsuario.includes('tesis.crear')">
    <div class="card-header">
      <div class="card-tools">
        <template v-if="listTesis.length === 0">
          <router-link class="btn btn-info bnt-sm" :to="'/tesis/crear'">
            <i class="fas fa-plus-square"></i> Ingresar formulario de inscripción
          </router-link>
        </template>
        <template v-else>
          <router-link class="btn btn-danger bnt-sm link-disabled" :to="''">
          Usted ya ingresó un formulario
          </router-link>
        </template>
      </div>
    </div>
    </template>
    <div class="card-body" style="min-height: 70vh !important">
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
                    <label class="col-md-3 col-form-label">Alumno</label>
                    <div class="col-md-9 form-group row pr-0">
                        <div class="col-md-6 pr-0">
                            <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqTesis.cNombre" @keyup.enter="getListarTesis">
                        </div>
                        <div class="col-md-6 pr-0">
                            <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqTesis.cApellido" @keyup.enter="getListarTesis">
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqTesis.cEstado"
                        placeholder="Seleccione un estado"
                        filterable
                        autocomplete="estadoFusionadoDeSGYAD"
                        @change="getListarTesis">
                          <el-option
                            v-for="item in globFunct.listStates([6])"
                            :key="item.id"
                            :label="item.resultado"
                            :value="[item.eI,item.eA]">
                          </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Inscripción</label>
                    <div class="col-md-9 form-group row pr-0">
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
                <div v-if="rolActivo != ('Director'||'Secretaria')" class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Escuela</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqTesis.nIdEscuela"
                        placeholder="Seleccione una escuela"
                        filterable
                        autocomplete="fillBsqTesis.nIdEscuela"
                        @change="getListarTesis">
                          <el-option
                            v-for="item in listarEscuelasByFidsByProfesor"
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
                    <label class="col-md-3 col-form-label">Rut alumno</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="11111111-1" class="form-control" v-model="fillBsqTesis.nRut" @keyup.enter="getListarTesis">
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
                    <el-popover
                    title="Ayuda"
                    placement="left"
                    width="300"
                    trigger="hover"
                    :content="'Se redirigirá a la sección Revisar '+terminoTitulo+'s'">
                        <button slot="reference" class="btn btn-secondary" @click.prevent="redirectTo('dashboard.index', {open: 'tesis', focus: 'tesis'}, true)">
                            <i class="fas fa-question-circle" ></i>
                        </button>
                    </el-popover>
                </div>
            </div>
          </div>
          <div class="card-body table table-responsive">
            <template v-if="listTesis.length">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Alumno(s)</th>
                    <th>Estado</th>
                    <th v-if="rolActivo != ('Director'||'Secretaria')">Escuela</th>
                    <th v-if="rolActivo == 'Profesor'">Rol de participación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody v-loading="fullscreenLoading">
                  <tr v-for="(item, index) in listarTesisPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.listUsers" :key="index">
                            <div v-text="itemUser.nombres + ' ' + itemUser.apellidos"></div>
                        </div>
                    </td>
                    <td>{{globFunct.mergedStates(item).resultado}}</td>
                    <td :title="item.escuela.nombre" v-if="rolActivo != ('Director'||'Secretaria')">
                        {{item.escuela.nombre_abreviado}}
                    </td>
                    <td v-if="rolActivo == 'Profesor'">
                        <template v-if="item.id_p_guia == authUser.id_user">
                            Prof. Guía
                        </template>
                        <template v-else-if="item.id_p_co_guia == authUser.id_user">
                            Prof. Co-guía
                        </template>
                    </td>
                    <td>
                        <router-link :title="'Ver '+ terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                            <i class="far fa-eye fa-lg"></i>
                        </router-link>
                        <button :title="'Editar '+terminoTitulo" class="btn boton btn-primary"
                        :disabled="!((rolActivo == 'Alumno' && item.aprobado_pg == 'R') || (rolActivo == 'Profesor' && item.aprobado_pg == 'P'))"
                        @click.prevent="redirectTo('tesis.editar', {id: item.id})">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <template v-if="listRolPermisosByUsuario.includes('tesis.subirconstancia')">
                            <button :disabled="!(item.aprobado_pg == 'A' || item.aprobado_pg == 'V')"
                            title="Subir constancia de examen" class="btn btn-success boton"
                            @click.prevent="redirectTo('tesis.subirconstancia')">
                            <i class="fas fa-file-upload"></i>
                            </button>
                        </template>
                        <button :title="'Generar documento PDF de '+terminoTitulo" class="btn boton btn-primary"
                        :disabled="!(item.aprobado_pg == 'A' || item.aprobado_pg == 'V')"
                        @click.prevent="setGenerarDocumento(item.id)">
                          <b>{{terminoTitulo}}</b>
                        </button>
                      <template v-if="rolActivo != 'Alumno'">
                        <button :disabled="!(item.constancia)"
                        title="Descargar constancia de examen" class="btn boton btn-info"
                        @click.prevent="descargarConstancia(item)">
                          <b>CE</b>
                        </button>
                        <button v-if="!listRolPermisosByUsuario.includes('actadefensa.index')"
                        class="btn boton" title="Registro de fecha de defensas" :disabled="!item.constancia"
                        :class="((item.fecha_defensa && item.constancia) || (!item.fecha_defensa && !item.constancia)) ? 'btn-primary': 'btn-warning'"
                        @click.prevent="modalFidCalendario(item)">
                            <i class="fas fa-calendar-day"></i>
                        </button>
                      </template>
                      <template v-if="rolActivo == 'Alumno'">
                        <router-link title="Ver revisiones de comisión"
                        class="btn boton btn-primary" :to="'tesis/revisiones'">
                          <i class="fa fa-list-alt"></i>
                        </router-link>
                      </template>
                        <template  v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                            <button :disabled="(rolActivo == 'Profesor' && item.aprobado_pg != 'P') || (rolActivo != 'Profesor' && item.aprobado_pg == 'V') || (item.aprobado_pg == 'EA') || (rolActivo == 'Profesor' && item.id_p_co_guia == authUser.id_user) || (rolActivo != 'Profesor' && item.aprobado_pg == 'R')"
                            :title="'Aprobar '+terminoTitulo" class="btn boton btn-success" @click.prevent="acceptHandler(item)">
                            <i class="far fa-thumbs-up"></i>
                            </button>
                            <button v-if="(item.aprobado_pg != 'R')" :disabled="((rolActivo == 'Profesor' && item.aprobado_pg == 'V') || (item.aprobado_pg == 'EA')) || (rolActivo == 'Profesor' && item.id_p_co_guia == authUser.id_user) || (item.aprobado_pg == 'R')"
                            :title="'Rechazar '+terminoTitulo" class="btn boton btn-danger" @click.prevent="modalRechazo(item)">
                            <i class="far fa-thumbs-down"></i>
                            </button>
                        </template>
                        <button v-if="item.aprobado_pg == 'R'" title="Ver razón de rechazo"
                            class="btn boton btn-danger" @click.prevent="verRazonRechazo(item.motivo_pg)">
                            <i class="fas fa-exclamation-circle"></i>
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

    <div class="modal fade" :class="{ show: modalAyuda }" :style="modalAyuda ? mostrarModal : ocultarModal">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Ventana de ayuda de íconos</h5>
            <button class="close" @click="mostrarModalAyuda"></button>
            </div>
            <div class="modal-body">
            <table class ="table table-hover table-head-fixed t-fixed projects ">
                <thead>
                    <tr>
                        <th class="col-md-1">Ícono</th>
                        <th class="col-md-2">Nombre</th>
                        <th class="col-md-9">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="fas fa-plus-square"></i></td>
                        <td>Agregar</td>
                        <td>Módulo de registro de datos de {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-eye"></i></td>
                        <td>Ver</td>
                        <td>Visión protegida de los datos ingresados en {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-file-download"></i></td>
                        <td>Descarga</td>
                        <td>Descarga de los datos inscritos en la plataforma</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-pencil-alt"></i></td>
                        <td>Edición</td>
                        <td>Visión y cambios de datos de {{terminoTitulo}}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-exclamation-circle"></i></td>
                        <td>Ver motivo</td>
                        <td>Se muestra en pantalla el motivo de un posible rechazo de su {{terminoTitulo}}</td>
                    </tr>
                    <template v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                        <tr>
                            <td><i class="fas fa-check"></i></td>
                            <td>Aprobar</td>
                            <td>Aprobación de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-times"></i></td>
                            <td>Rechazar</td>
                            <td>Rechazo de los datos inscritos por el alumno (envío de correo)</td>
                        </tr>
                    </template>
                </tbody>
                </table>
            </div>
            <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">{{globVar.btnClose}}</button>
            </div>
        </div>
        </div>
    </div>

    <template v-if="mostrarModalRechazo">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="dismissModal()">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2;" v-on:mousedown.stop>
          <div class="swal2-header">
            <div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;">
              <div class="swal2-icon-content">!</div>
            </div>
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">¿Escriba el motivo de su rechazo (opcional)?</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content">
            <div id="swal2-content" class="swal2-html-container" style="display: none;"></div>
            <textarea v-model="motivo" class="swal2-textarea" style="display: flex;"></textarea>
            <div class="swal2-validation-message" id="swal2-validation-message"></div>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); --darkreader-inline-bgcolor:#2166a7; --darkreader-inline-border-left:#1d5a93; --darkreader-inline-border-right:#1d5a93;"  @click.prevent="setCambiarEstadoFITRechazo">Si, Rechazar</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="dismissModal()">Cancelar</button>
          </div>
        </div>
      </div>
    </template>



    <template v-if="mostrarModalApruebo">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="mostrarModalApruebo=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; width: 70% !important; min-width: 360px !important" v-on:mousedown.stop>
          <div class="swal2-header">
            <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Sistema de documentos UCM</h5>
                    <button class="close" @click="modalShow = !modalShow"></button>
                  </div>
                  <div class="modal-body">
                    <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalShow = !modalShow">{{globVar.btnClose}}</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;">
              <div class="swal2-icon-content">!</div>
            </div> -->
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Ingrese su comisión sugerida</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content text-left">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label" v-text="(selectedFid.listUsers.length > 1) ? 'Alumnos' : 'Alumno' "></label>
                    <div class="col-md-9 p-0 m-0">
                      <div class="col-md-12 my-1" v-for="(itemUser, index) in selectedFid.listUsers" :key="index">
                        <input type="text" class="form-control"  disabled :value="itemUser.nombres + ' ' + itemUser.apellidos">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. comisión 1 (*)</label>
                    <div class="col-md-9">
                      <el-select v-model="fillCrearComision.Profesor1"
                      placeholder="Asignar profesor para comisión"
                      filterable
                      clearable>
                      <el-option
                          v-for="item in listProfesores"
                          :key="item.id_user"
                          :label="item.fullname"
                          :value="item.id_user">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. comisión 2</label>
                    <div class="col-md-9">
                      <el-select v-model="fillCrearComision.Profesor2"
                      placeholder="Asignar profesor para comisión"
                      filterable
                      clearable>
                      <el-option
                          v-for="item in listProfesores"
                          :key="item.id_user"
                          :label="item.fullname"
                          :value="item.id_user">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Nombre profesor externo" class="form-control" v-model="fillCrearComision.NombrePEx" @keyup.enter="setCambiarEstadoFIT" @change.prevent="validarProfesorExterno()">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Email prof. externo</label>
                    <div class="col-md-9">
                      <input :disabled = !fillCrearComision.NombrePEx type="text" placeholder="Email profesor externo" :class="{'is-invalid' : (!validEmail && fillCrearComision.EmailPEx)}" class="form-control" v-model="fillCrearComision.EmailPEx" @keyup.enter="setCambiarEstadoFIT" @change.prevent="validarEmail()">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Institución prof. externo</label>
                    <div class="col-md-9">
                      <input :disabled = !fillCrearComision.NombrePEx type="text" placeholder="Institución profesor externo" class="form-control" v-model="fillCrearComision.InstitucionPEx" @keyup.enter="setCambiarEstadoFIT">
                    </div>
                  </div>
                    <span>(*) Campo requerido.</span>
                </div>
              </div>
            </form>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); --darkreader-inline-bgcolor:#2166a7; --darkreader-inline-border-left:#1d5a93; --darkreader-inline-border-right:#1d5a93;"  @click.prevent="setCambiarEstadoFIT">Si, Aprobar</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="mostrarModalApruebo=false">Cancelar</button>
          </div>
        </div>
      </div>
    </template>

    <template v-if="showModal">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="showModal=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; width: 70% !important; min-width: 360px !important; bottom: 15%;" v-on:mousedown.stop>
          <div class="swal2-header">
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Registrar fecha de defensa de actas</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Fecha de defensa</label>
                    <el-date-picker
                        class="col-md-9"
                        v-model="fechaDefensa"
                        type="datetime"
                        size="large"
                        default-time="08:00:00"
                        placeholder="Selecionar fecha y hora de defensa de tesis">
                    </el-date-picker>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Sala de defensa</label>
                    <el-input class="col-md-9" placeholder="Escriba la sala donde se realizará la defensa" v-model="salaDefensa">
                    </el-input>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="swal2-content text-left">
          </div>
          <div class="swal2-actions">
            <button class="swal2-confirm swal2-styled" @click.prevent="setRegistrarDefensa" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: #6c757d; --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="showModal=false">{{globVar.btnClose}}</button>
          </div>
        </div>
      </div>
    </template>

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
        nRut          : '',
        nIdEscuela    : '',
        dateRange: {
            startDate: null,
            endDate: null
        }
      },
      fillVerFIT:{
        cNombre: '',
        cSlug: ''
      },
      fillCrearComision:{
        Profesor1: '',
        Profesor2: '',
        NombrePEx: '',
        EmailPEx: '',
        InstitucionPEx: ''
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      authUser: JSON.parse(localStorage.getItem('authUser')),
      listEstadosFIT: [
        {value: 'V', label: 'Verificado'},
        {value: 'A', label: 'Aprobado'},
        {value: 'P', label: 'Pendiente'},
        {value: 'R', label: 'Rechazado'}
      ],
      listEstadosTesis: [
        {value: 'A', label: 'Aprobada'},
        {value: 'R', label: 'Reprobada'},
        {value: 'D', label: 'En Desarrollo'}
      ],
      listarEscuelasByFidsByProfesor: [],
      listTesis: 1,
      listAllTesis:1,
      listMiTesis:[],
      listProfesores: [],
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 10,
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
      mostrarModalRechazo: false,
      mostrarModalApruebo: false,
      motivo: '',
      idTesis: '',
      selectedFid: {},
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
      validEmail: true,
      showModal: false,
      fechaDefensa: '',
      salaDefensa: '',
      tesisSelected: ''
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
    let navegar = 'Ingresar/Revisar FID';
    if (this.rolActivo != 'Alumno') navegar = 'Revisar FID';
    EventBus.$emit('navegar', navegar);
    EventBus.$on('refresh', x => {this.init()});
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarEscuelasByFITsByProfesor();
      this.getListarTesis();
      this.getListarProfesores();
      this.selectStart();
    },
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
    getListarTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarTesis';
      axios.get(url, {
        params:{
          'nombre'    :   this.fillBsqTesis.cNombre,
          'apellido'  :   this.fillBsqTesis.cApellido,
          'estadoI'   :   this.fillBsqTesis.cEstadoPg,
          'estado'    :   this.fillBsqTesis.cEstado,
          'rut'       :   this.fillBsqTesis.nRut,
          'fechaSt'   :   (!this.fillBsqTesis.dateRange.startDate) ? '' : this.fillBsqTesis.dateRange.startDate,
          'fechaEn'   :   (!this.fillBsqTesis.dateRange.endDate) ? '' : this.fillBsqTesis.dateRange.endDate,
          'nIdEscuela':   this.fillBsqTesis.nIdEscuela
        }
      }).then(response => {
            this.inicializarPaginacion();
            this.listTesis = response.data;
            this.fullscreenLoading = false;
      })
    },
    getListarEscuelasByFITsByProfesor () {
        var url = '';
        if (this.rolActivo == 'Profesor') url = '/user/getListarEscuelasByFITsByProfesor';
        else url = '/administracion/escuelas/getListarEscuelas';
        axios.get(url, {
            params:{
                'nIdProfesor' :   this.authUser.id_user
            }
        }).then(response => {
            this.listarEscuelasByFidsByProfesor = response.data;
            this.listarEscuelasByFidsByProfesor.unshift({
                created_at: null,
                facultad: '',
                id: '',
                id_facultad: '',
                nombre: 'Todas',
                updated_at: null
            });
        })
    },
    getListarAllTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarAllTesis'
      axios.get(url, {
      }).then(response => {
          this.listAllTesis = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqTesis.cNombre = '';
      this.fillBsqTesis.cApellido = '';
      this.fillBsqTesis.cEstado = '';
      this.fillBsqTesis.cEstadoPg = '';
      this.fillBsqTesis.dfecha = '';
      this.fillBsqTesis.nRut = '';

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

  setCambiarEstadoFIT(){
    if(this.validarRegistrarComision()){
      this.modalShow = true;
      return;
    }
    this.mostrarModalApruebo = false;
    this.fullscreenLoading = true;
    var url = '/comisiones/setRegistrarComision';
    axios.post(url, {
      'idTesis'           : this.idTesis,
      'Profesor1'         : this.fillCrearComision.Profesor1,
      'Profesor2'         : this.fillCrearComision.Profesor2,
      'NombrePEx'         : this.fillCrearComision.NombrePEx,
      'EmailPEx'          : this.fillCrearComision.EmailPEx,
      'InstitucionPEx'    : this.fillCrearComision.InstitucionPEx
    }).then(response => {
        var url = '/alumno/setCambiarEstadoFIT'
        axios.post(url, {
        'nIdTesis' : this.idTesis,
        'cEstadoPg'    : 'A'
        }).then(response => {
            this.fullscreenLoading = false;
            Swal.fire({
            icon: 'success',
            title: 'Se aprobó el formulario de inscripción',
            showConfirmButton: false,
            timer: 1500
            });
            this.getListarTesis();
        })
    })
  },
    aprobarFIT(){
       Swal.fire({
        title: '¿Está seguro que desea aprobar el formulario de inscripción?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aprobar',
        cancelButtonText: 'Cancelar',
        }).then((result) => {
        if (result.value) {
            this.fullscreenLoading = true;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
            'nIdTesis' : this.idTesis,
            'cEstadoPg'    : 'A'
            }).then(response => {
                this.fullscreenLoading = false;
                Swal.fire({
                icon: 'success',
                title: 'Se aprobó el formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                })
                this.getListarTesis();
            })
        }
        })
    },
    setCambiarEstadoFITRechazo () {
      this.mostrarModalRechazo = false;
      var url = '/alumno/setCambiarEstadoFIT'
      axios.post(url, {
          'nIdTesis'  : this.idTesis,
          'cEstadoPg' : 'R',
          'motivo'    : this.motivo
      })
      .then(response => {
          Swal.fire({
          icon: 'success',
          title: 'Se rechazó el formulario de inscripción',
          showConfirmButton: false,
          timer: 1500
          })
          this.getListarTesis();
      })
    },

    mostrarModalAyuda() {
        this.modalAyuda = !this.modalAyuda;
    },
    verRazonRechazo(data) {
      if (data) {
        Swal.fire({
            icon: 'warning',
            title: 'Motivo de rechazo',
            padding: 0,
            html: '<div style="white-space: pre-wrap; max-height: 50vh; overflow: auto; padding: 10px">'+ data +'</div>'
        })
      }else{
        Swal.fire({
            icon: 'warning',
            title: 'No se especificó un motivo de rechazo',
            padding: 0,
        })
      }

    },
    modalRechazo(fit){
      if (this.rolActivo == 'Profesor') {
        var url = '/alumno/getTesisById';
        axios.get(url, {
          params: {
            'id' : fit.id
          }
        }).then(response => {
            if (response.data.aprobado_pg == 'V') {
              Swal.fire({
                icon: 'warning',
                title: 'No puedes rechazar este FID',
                padding: 0,
                timer: 1500
              }).then(x => {
                this.getListarTesis();
              });
            }else{
              this.motivo = fit.motivo_pg;
              this.idTesis = fit.id;
              this.mostrarModalRechazo = true;
            }
        })
      }else{
        this.motivo = fit.motivo_pg;
        this.idTesis = fit.id;
        this.mostrarModalRechazo = true;
      }
    },

    dismissModal(){
      this.listTesis.find(fit => fit.id == this.idTesis).motivo_pg = this.motivo;
      this.mostrarModalRechazo = false;
    },
    getListarProfesores(){
        var url = '/alumno/getListarProfesores';
        axios.get(url, {
            params: {
                'allTeacher' : true
            }
        }).then(response => {
            this.listProfesores = response.data;
        })
    },
    acceptHandler(fit){
      this.selectedFid = fit;
      this.idTesis = fit.id;
      if (this.rolActivo == 'Profesor' && !fit.comisiones) {
        this.fillCrearComision.Profesor2       = '';
        this.fillCrearComision.NombrePEx       = '';
        this.fillCrearComision.EmailPEx        = '';
        this.fillCrearComision.InstitucionPEx  = '';
        if (fit.id_p_co_guia) {
          this.fillCrearComision.Profesor1 = fit.id_p_co_guia;
        }else{
          this.fillCrearComision.Profesor1 = '';
        }


        this.mostrarModalApruebo = true;
      }else{
        this.aprobarFIT();
      }

    },
    validarRegistrarComision(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.idTesis){
          this.mensajeError.push("La selección de documento es obligatoria");
        }
        if(!this.fillCrearComision.Profesor1){
          this.mensajeError.push("La selección del primer profesor es obligatoria");
        }
        if(this.fillCrearComision.Profesor1 == this.fillCrearComision.Profesor2){
          this.mensajeError.push("El primer y segundo profesor no pueden ser iguales");
        }
        if (this.fillCrearComision.EmailPEx && !this.validEmail) {
          this.mensajeError.push("El email del profesor externo no tiene un formato valido");
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    selectStart() {
        this.fillBsqTesis.dateRange.endDate = null;
        this.endOption = {
            disabledDate: (time) => {
                return time.getTime() < Date.parse(this.fillBsqTesis.dateRange.startDate) || time.getTime() > Date.now();
            }
        };
    },
    validarEmail() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.validEmail = re.test(this.fillCrearComision.EmailPEx);
    },
    validarProfesorExterno(){
      if (!this.fillCrearComision.NombrePEx) {
        this.fillCrearComision.EmailPEx = '';
        this.fillCrearComision.InstitucionPEx = '';
      }
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
    descargarConstancia(fid){
      if (fid.listUsers.length == 1) {
        this.download(fid.constancia);
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
    },
    modalFidCalendario(item) {
        this.fechaDefensa   = '';
        this.salaDefensa    = '';
        if (item.fecha_defensa) {
            this.fechaDefensa   = item.fecha_defensa.fecha;
            this.salaDefensa    = item.fecha_defensa.sala;
        }
        this.tesisSelected = item.id;
        this.showModal = !this.showModal;
    },
    setRegistrarDefensa() {
        this.showModal = false;
        if (typeof(this.fechaDefensa) == 'string') {
            this.fechaDefensa = new Date(this.fechaDefensa);
        }
        if (this.fechaDefensa && this.salaDefensa) {
            this.fullscreenLoading = true;
            var url = '/secretaria/setRegistrarDefensaActa';
            var userTimezoneOffset = this.fechaDefensa.getTimezoneOffset() * 60000;
            this.fechaDefensa = new Date(this.fechaDefensa.getTime() - userTimezoneOffset);
            axios.post(url, {
                'nIdFit'        : this.tesisSelected,
                'fechaDefensa'  : this.fechaDefensa,
                'salaDefensa'   : this.salaDefensa
            }).then(response => {
                this.getListarTesis();
                Swal.fire({
                    icon: 'success',
                    title: 'La fecha y sala ha sido guardada.',
                    showConfirmButton: true,
                    timer: 1500
                });
                this.fullscreenLoading = false;
            })
        } else {
          setTimeout(function(){
            Swal.fire({
              icon: 'warning',
              title: 'Todos los datos son obligatorios.',
              showConfirmButton: true,
              timer: 1500
            });
          }, 50);
        }
    },
    download(item) {
      axios({
        url: item.path,
        method: 'GET',
        responseType: 'blob'
      }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', item.filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
      })
    }
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
