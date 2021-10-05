<template>
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i> {{globVar.btnBack}}
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-10">
                    <h3 class="card-title">
                      Formulario de registro rápido de documentos finalizados
                    </h3>
                  </div>
                  <div class="col-md-2" style="text-align: right">
                    <button
                      class="btn btn-secondary"
                      @click.prevent="mostrarModalAyuda"
                    >
                      Ayuda
                      <i class="fas fa-question-circle"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Prof. Guía</label
                        >
                        <div class="col-md-9">
                          <el-select
                            v-model="fillCrearFIT.nIdPg"
                            placeholder="Asignar Profesor Guia"
                            filterable
                            clearable
                          >
                            <el-option
                              v-for="item in listProfesores"
                              :key="item.id_user"
                              :label="item.fullname"
                              :value="item.id_user"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Prof. Co-guía</label
                        >
                        <div class="col-md-9">
                          <el-select
                            v-model="fillCrearFIT.nIdCoPg"
                            placeholder="Asignar Profesor Co-guía"
                            filterable
                            clearable
                            v-popover:tooltip.bottom="'Este campo es opcional'"
                          >
                            <el-option
                              v-for="item in listProfesores"
                              :key="item.id_user"
                              :label="item.fullname"
                              :value="item.id_user"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Tipo de trabajo</label
                        >
                        <div class="col-md-9">
                          <el-select
                            v-model="fillCrearFIT.cTipo"
                            placeholder="Seleccione un Tipo"
                            filterable
                            clearable
                          >
                            <el-option
                              v-for="item in listTipo"
                              :key="item.value"
                              :label="item.label"
                              :value="item.value"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Vinculación</label
                        >
                        <div class="col-md-9">
                          <el-select
                            v-model="fillCrearFIT.nIdVinculacion"
                            placeholder="Asignar Vinculación"
                            filterable
                            clearable
                          >
                            <el-option
                              v-for="item in listVinculacion"
                              :key="item.id"
                              :label="item.nombre"
                              :value="item.id"
                              v-popover:tooltip.left="item.descripcion"
                            >
                            </el-option>
                          </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Título</label>
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Título del documento"
                            show-word-limit
                            v-model="fillCrearFIT.cTitulo"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Escuela de pertenencia</label
                        >
                        <div class="col-md-9">
                          <el-select
                            v-model="fillCrearFIT.nIdEscuela"
                            placeholder="Asignar escuela"
                            filterable
                            clearable
                          >
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
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Contribución</label
                        >
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Contribución esperada"
                            show-word-limit
                            v-model="fillCrearFIT.cContribucion"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Objetivo general
                        </label>
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Objetivo general"
                            show-word-limit
                            v-model="fillCrearFIT.cObjetivoGeneral"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Objetivos específicos</label
                        >
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Objetivos específicos del documento"
                            show-word-limit
                            v-model="fillCrearFIT.cObjetivoEspecifico"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Descripción
                        </label>
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Descripción del tema"
                            show-word-limit
                            v-model="fillCrearFIT.cDescripcion"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                        <template> Nota final de documento </template>
                        <!-- <template v-if="!fillCrearFIT.fidFinalizada"> Estado final </template> -->
                        </label>
                        <div class="col-md-9">
                          <div class="input-group">
                            <el-input-number
                                v-if="fillCrearFIT.fidFinalizada"
                                style="width: 75%;"
                                v-model="fillCrearFIT.Nota"
                                size="large"
                                :min="1"
                                :precision="1"
                                :step="0.1"
                                :max="7"
                            ></el-input-number>
                            <input style="width: 40px; height: 40px; margin-left: auto; margin-right: 0;" type="checkbox" v-model="fillCrearFIT.fidFinalizada">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Fecha</label>
                        <div class="col-md-9">
                          <el-date-picker
                            v-model="fillCrearFIT.dFecha"
                            placeholder="Seleccionar fecha"
                            format="dd/MM/yyyy"
                            value-format="yyyy-MM-dd">
                          </el-date-picker>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Comisión evaluadora</label>
                        <div class="col-md-2">
                            <button class="btn btn-primary" @click.prevent="mostrarModalComision">
                                <i class="fas fa-users"></i>
                            </button>
                        </div>
                        <div v-if="fillCrearFIT.aComision.length > 0" class="col-md-7">
                            <div v-if="fillCrearFIT.aComision[0]" class="noPadNoMar"><b>P1: </b>{{fillCrearFIT.aComision[0].fullname}}</div>
                            <div v-if="fillCrearFIT.aComision[1]" class="noPadNoMar"><b>P2: </b>{{fillCrearFIT.aComision[1].fullname}}</div>
                            <div v-if="fillCrearFIT.oProfExterno.fullname" class="noPadNoMar"><b>Pe: </b>{{fillCrearFIT.oProfExterno.fullname}}</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-5 col-form-label">
                        <template> Documento privado </template>
                        <!-- <template v-if="!fillCrearFIT.fidFinalizada"> Estado final </template> -->
                        </label>
                        <div class="col-md-7">
                          <div class="input-group">
                            <input style="width: 40px; height: 40px; margin-left: auto; margin-right: 0;" type="checkbox" v-model="fillCrearFIT.privado">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="noPadNoMar col-md-12 form-group row">
                        <label v-if="fillCrearFIT.fidFinalizada" class="noPadNoMar col-md-12 col-form-label">Documento final (opcional)</label>
                        <label v-if="!fillCrearFIT.fidFinalizada" class="noPadNoMar col-md-12 col-form-label">Último documento (opcional)</label>
                        <div class="noPadNoMar container-fluid">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span
                                class="input-group-text"
                                id="inputGroupFileAddon01"
                              >
                                <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input
                                type="file"
                                class="custom-file-input"
                                id="input1"
                                :class="{
                                  'is-invalid': tesisParams.formatError || tesisParams.sizeError,
                                  'is-valid' : hover1
                                }"
                                @change="getFileRut"
                                @mouseover="hover1 = true"
                                @mouseleave="hover1 = false"
                              />
                              <label class="custom-file-label" for="input1">{{
                                tesisFile
                                  ? tesisFile.name
                                  : "Seleccionar archivo"
                              }}</label>
                            </div>
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="tesisParams.formatError"
                          >
                            El formato del archivo no es soportado.
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="tesisParams.sizeError"
                          >
                            El tamaño del archivo no puede superar los
                            {{ tesisParams.size }} MB.
                          </div>
                          <div class="container">
                            El tamaño máximo de los archivos es: {{tesisParams.size}} MB.
                        </div>
                        <div class="container">
                            Los formatos de archivo soportados son:
                        <span v-for="item in tesisParams.types" :key="item" v-text="item +' '"></span>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="noPadNoMar col-md-12 form-group row">
                        <label class="noPadNoMar col-md-12 col-form-label">Acta de defensa de documento (opcional)</label>
                        <div class="noPadNoMar container-fluid">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span
                                class="input-group-text"
                              >
                                <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input
                                type="file"
                                class="custom-file-input"
                                :class="{
                                  'is-invalid': actaParams.formatError || actaParams.sizeError,
                                  'is-valid' : hover2
                                }"
                                @change="getFileActa"
                                @mouseover="hover2 = true"
                                @mouseleave="hover2 = false"
                              />
                              <label class="custom-file-label" for="input1">{{
                                actaFile
                                  ? actaFile.name
                                  : "Seleccionar archivo"
                              }}</label>
                            </div>
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="actaParams.formatError"
                          >
                            El formato del archivo no es soportado.
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="actaParams.sizeError"
                          >
                            El tamaño del archivo no puede superar los
                            {{ actaParams.size }} MB.
                          </div>
                          <div class="container">
                            El tamaño máximo de los archivos es: {{actaParams.size}} MB.
                        </div>
                        <div class="container">
                            Los formatos de archivo soportados son:
                        <span v-for="item in actaParams.types" :key="item" v-text="item +' '"></span>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="noPadNoMar col-md-12 form-group row">
                        <label  class="noPadNoMar col-md-12 col-form-label">Constancia de examen (opcional)</label>
                        <div class="noPadNoMar container-fluid">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span
                                class="input-group-text"
                              >
                                <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input
                                type="file"
                                class="custom-file-input"
                                :class="{
                                  'is-invalid': constParams.formatError || constParams.sizeError,
                                  'is-valid'  : hover3
                                }"
                                @change="getFileConst"
                                @mouseover="hover3 = true"
                                @mouseleave="hover3 = false"
                              />
                              <label class="custom-file-label" for="input1">{{
                                constFile
                                  ? constFile.name
                                  : "Seleccionar archivo"
                              }}</label>
                            </div>
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="constParams.formatError"
                          >
                            El formato del archivo no es soportado.
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="constParams.sizeError"
                          >
                            El tamaño del archivo no puede superar los
                            {{ constParams.size }} MB.
                          </div>
                          <div class="container">
                            El tamaño máximo de los archivos es: {{constParams.size}} MB.
                        </div>
                        <div class="container">
                            Los formatos de archivo soportados son:
                        <span v-for="item in constParams.types" :key="item" v-text="item +' '"></span>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <h3>
                        <b>{{ "Tabla de integrantes" }}</b>
                      </h3>
                    </div>
                    <table
                      class="
                        table table-hover table-head-fixed
                        t-fixed
                        projects
                      "
                    >
                      <thead>
                        <tr>
                          <th>Nombre integrante</th>
                          <th>Rut</th>
                          <th>Correo electrónico</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="(item, index) in fillCrearFIT.cUsers"
                          :key="index"
                        >
                          <td>{{ item.nombres + " " + item.apellidos }}</td>
                          <td>{{ item.rut }}</td>
                          <td>{{ item.email }}</td>
                          <td>
                            <div class="col-md-3" :key="'boton' + index">
                              <div class="text-right mb-2">
                                <button
                                  class="btn btn-danger"
                                  @click.prevent="eliminarEstudiante(item)"
                                >
                                  <i class="fas fa-trash"></i>
                                </button>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="col-md-6">
                      <button
                        class="btn btn-info bnt-sm"
                        @click.prevent="mostrarModalBusquedaEstudiante"
                      >
                        <i class="fas fa-user-plus"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button
                      class="btn btn-flat btn-info btnWidth"
                      @click.prevent="setRegistrarTesisfinalizada"
                      v-loading.fullscreen.lock="fullscreenLoading"
                    >
                      {{globVar.btnSave}}
                    </button>
                    <button
                      class="btn btn-flat btn-default btnWidth"
                      @click.prevent="limpiarCriterios"
                    >
                      {{globVar.btnClear}}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    <div
      class="modal fade"
      :class="{ show: modalShow }"
      :style="modalShow ? mostrarModal : ocultarModal"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Sistema de documentos UCM</h5>
            <button class="close" @click="abrirModal"></button>
          </div>
          <div class="modal-body">
            <div
              class="callout callout-danger"
              style="padding: 5px"
              v-for="(item, index) in mensajeError"
              :key="index"
              v-text="item"
            ></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="abrirModal">
              {{globVar.btnClose}}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      :class="{ show: modalSearchUser }"
      :style="modalSearchUser ? mostrarModal : ocultarModal"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content scrollTable">
          <div class="modal-header">
            <h5 class="modal-title"><b>Búsqueda de integrante de documento</b></h5>
            <button
              class="close"
              @click="mostrarModalBusquedaEstudiante"
            ></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Rut</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="busquedaUsuario.rut"
                      @keyup.enter="setBusquedaUsuario"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Email</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="busquedaUsuario.email"
                      @keyup.enter="setBusquedaUsuario"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Nombre</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="busquedaUsuario.nombre"
                      @keyup.enter="setBusquedaUsuario"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-md-3 col-form-label">Apellido</label>
                  <div class="col-md-9">
                    <input
                      type="text"
                      class="form-control"
                      v-model="busquedaUsuario.apellido"
                      @keyup.enter="setBusquedaUsuario"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <button
                  class="btn btn-success w-100"
                  @click="setBusquedaUsuario"
                >
                  {{globVar.btnSearch}}
                </button>
              </div>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <template>
                      <div class="row">
                        <h3><b>Lista de usuarios encontrados:</b></h3>
                      </div>
                      <table class=" table table-hover table-head-fixed t-fixed projects" >
                        <thead>
                          <tr>
                            <th class="col-md-4">Nombre de integrante</th>
                            <th class="col-md-2">Rut</th>
                            <th class="col-md-4">Correo electrónico</th>
                            <th class="col-md-2">Acción</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td style="vertical-align: super !important;">
                              <div class="row">
                                <div
                                  style="padding: 0px; margin: 0px"
                                  class="col-md-6"
                                >
                                  <el-input
                                    type="text"
                                    maxlength="250"
                                    placeholder="Nombre"
                                    v-model="newUser.nombres"
                                    required
                                    @blur="validarIngresoUsuarioNombre($event.target.value)"
                                  ></el-input>
                                </div>
                                <div
                                  style="padding: 0px; margin: 0px"
                                  class="col-md-6"
                                >
                                  <el-input
                                    type="text"
                                    maxlength="250"
                                    placeholder="Apellido"
                                    v-model="newUser.apellidos"
                                    required
                                    @blur="validarIngresoUsuarioNombre($event.target.value)"
                                  ></el-input>
                                </div>
                                <div
                                  style="padding: 0px; margin: 0px"
                                  class="col-md-12"
                                >
                                <p class="invalid-feedback" v-show="addUserErrorMessage.nombre === 1">Nombre inválido</p>
                                </div>
                              </div>
                            </td>
                            <td style="vertical-align: super !important;">
                              <el-input
                                type="text"
                                maxlength="250"
                                placeholder="11111111-1"
                                v-model="newUser.rut"
                                @blur="validarIngresoUsuarioRut($event.target.value)"
                                required
                              ></el-input>
                              <p class="invalid-feedback" v-show="addUserErrorMessage.rut === 1">Rut inválido</p>
                            </td>
                            <td style="vertical-align: super !important;">
                              <el-input
                                v-model="newUser.email"
                                @blur="validarIngresoUsuarioEmail($event.target.value)"
                                type="email"
                                maxlength="250"
                                placeholder="ejemplo@alu.ucm.cl"
                              ></el-input>
                              <p class="invalid-feedback" v-show="addUserErrorMessage.email === 1">Dirección de email inválida</p>
                            </td>
                            <td style="vertical-align: super !important;">
                              <button
                                class="btn btn-info bnt-sm"
                                @click.prevent="setIngresarNuevoAlumno"
                                :disabled=addUserErrorMessage.boton
                              >
                                <i class="fas fa-plus-square"></i>
                              </button>
                            </td>
                          </tr>
                          <tr
                            v-for="(item, index) in listAlumnosBuscados"
                            :key="index"
                          >
                            <td>{{ item.nombres + " " + item.apellidos }}</td>
                            <td>{{ item.rut }}</td>
                            <td>{{ item.email }}</td>
                            <td>
                              <div :key="'boton' + index">
                                <div style="padding: 0px; margin: 0px">
                                  <button
                                    class="btn btn-info bnt-sm"
                                    @click.prevent="setIngresarAlumno(item)"
                                  >
                                    <i class="fas fa-plus-square"></i>
                                  </button>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </template>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              class="btn btn-secondary"
              @click="mostrarModalBusquedaEstudiante"
            >
              {{globVar.btnClose}}
            </button>
          </div>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      :class="{ show: modalAyuda }"
      :style="modalAyuda ? mostrarModal : ocultarModal"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ventana de ayuda de íconos</h5>
            <button class="close" @click="mostrarModalAyuda"></button>
          </div>
          <div class="modal-body">
            <table
              class="table table-hover table-head-fixed text-nowrap projects"
            >
              <thead>
                <tr>
                  <th class="col-md-1">Ícono</th>
                  <th class="col-md-2">Nombre</th>
                  <th class="col-md-9">Detalles</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><i class="fas fa-arrow-left"></i></td>
                  <td>{{globVar.btnBack}}</td>
                  <td>Vuelve a la sección anterior de la página</td>
                </tr>
                <tr>
                  <td><i class="fas fa-trash"></i></td>
                  <td>Borrar</td>
                  <td>Elimina los datos insertados en la sección</td>
                </tr>
                <tr>
                  <td><i class="fas fa-user-plus"></i></td>
                  <td>Agregar</td>
                  <td>
                    Inserta nuevos usuarios para integrar un formulario de
                    ingreso de documento
                  </td>
                </tr>
                <tr>
                  <td><i class="fas fa-plus-square"></i></td>
                  <td>Insertar</td>
                  <td>
                    Ingresa un estudiante partícipe ya existente o nuevo al {{terminoTitulo}}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">
              {{globVar.btnClose}}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalComision }"
      :style="modalComision ? mostrarModal : ocultarModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Selección de comisión evaluadora</h5>
            <button class="close" @click="mostrarModalComision"></button>
          </div>
          <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">Escuela</label>
                    <div class="col-md-9">
                        <multiselect
                            v-model="profesorByEscuelaBuscada"
                            label='nombre'
                            track-by="id"
                            :options="listEscuelas"
                            :searchable="true"
                            :close-on-select="true"
                            :show-labels="false"
                            placeholder="Selecciona escuela de profesor"
                            selectLabel="Seleccionar"
                            selectedLabel="Seleccionado"
                            deselectLabel="Presiona enter para remover"
                            @close="getListarProfesoresByEscuela">
                            <span slot="noOptions">No existen escuelas.</span>
                            <span slot="noResult">No existen escuelas.</span>
                        </multiselect>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. revisor</label>
                    <div class="col-md-9">
                        <multiselect
                            v-model="fillCrearFIT.aComision"
                            :options="listProfesoresBuscado"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :preserve-search="true"
                            placeholder="Seleccionar profesor"
                            label="fullname"
                            track-by="id_user"
                            :preselect-first="false"
                            :max="2"
                            selectLabel="Seleccionar"
                            selectedLabel="Seleccionado"
                            deselectLabel="Presiona enter para remover">
                            <span slot="maxElements">Límite de profesores internos alcanzado.</span>
                            <span slot="noOptions">No existen profesores en escuela.</span>
                            <span slot="noResult">El nombre no coincide.</span>
                        </multiselect>
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                    <label class="col-md-3 col-form-label">Profesor(es) externo(s)</label>
                    <div class="col-md-9">
                        <el-input
                        type="textarea"
                        :autosize="{ minRows: 1, maxRows: 5 }"
                        placeholder="Nombre de profesor"
                        v-model="fillCrearFIT.oProfExterno.fullname">
                        </el-input>
                        <el-input
                        type="textarea"
                        :autosize="{ minRows: 1, maxRows: 5 }"
                        placeholder="Correo de profesor"
                        v-model="fillCrearFIT.oProfExterno.correo">
                        </el-input>
                        <el-input
                        type="textarea"
                        :autosize="{ minRows: 1, maxRows: 5 }"
                        placeholder="Institución de profesor"
                        v-model="fillCrearFIT.oProfExterno.institucion">
                        </el-input>
                    </div>
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalComision">
              {{globVar.btnClose}}
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import globVar from '../../../services/globVar';
export default {
  components: { Multiselect },
  data(){
    return{
      globVar: new globVar(),
      fillCrearFIT: {
        Nota: "",
        cTitulo: "",
        oArchivo: "",
        nIdPg: "",
        nIdCoPg: "",
        nIdEscuela: '',
        nIdVinculacion: "",
        cTipo: "",
        cObjetivoGeneral: "",
        cObjetivoEspecifico: "",
        cDescripcion: "",
        cContribucion: "",
        cUsers: [],
        aComision: [],
        oProfExterno: {
            fullname: '',
            correo: '',
            institucion: ''
        },
        fidFinalizada: true,
        privado: false,
        dFecha: ""
      },
      tesisForm: new FormData(),
      actaForm: new FormData(),
      constForm: new FormData(),
      listTipo: [
        { value: "Tesis", label: "Tesis" },
        { value: "Memoria", label: "Memoria" },
        { value: "Proyecto de titulo", label: "Proyecto de titulo" },
      ],
      addUserErrorMessage: {
        boton: true,
        nombre: 2,
        email: 2,
        rut: 2
      },
      busquedaUsuario: {
        nombre: "",
        apellido: "",
        rut: "",
        email: "",
      },
      newUser: {
        nombres: "",
        apellidos: "",
        rut: "",
        email: "",
      },
      listProfesores: [],
      listEscuelas: [],
      listVinculacion: [],
      listAllUser: [],
      listAlumnosBuscados: [],
      listComision: [{'s':'a'}],
      fullscreenLoading: false,
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      modalShow: false,
      modalSearchUser: false,
      modalAyuda: false,
      modalComision: false,
      mostrarModal: {
        display: "block",
        background: "#0000006b",
      },
      ocultarModal: {
        display: "none",
      },
      error: 0,
      mensajeError: [],
      tesisParams: {
          types: [],
          size: 0,
          formatError: false,
          sizeError: false
      },
      actaParams: {
          types: [],
          size: 0,
          formatError: false,
          sizeError: false
      },
      constParams: {
          types: [],
          size: 0,
          formatError: false,
          sizeError: false
      },
      tesisFile: '',
      actaFile: '',
      constFile: '',
      profesorByEscuelaBuscada: '',
      listProfesoresBuscado: [],
      hover1: false,
      hover2: false,
      hover3: false,
    };
  },
  computed: {},
  mounted() {
    EventBus.$emit('navegar', 'Ingresar Documento finalizado');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  methods: {
    init(){
      this.getListarProfesores();
      this.getListarVinculacion();
      this.getListarAlumnos();
      this.getListarEscuelas();
      this.getParametros();
    },
    getListarEscuelas(){
        this.fullscreenLoading = true;
        var url = '/administracion/escuelas/getListarEscuelas'
        axios.get(url, {
        }).then(response => {
            this.listEscuelasOriginal = response.data;
            this.listEscuelas = response.data;
            this.fullscreenLoading = false;
        })
    },
    getParametros() {
      var url = "/admin/parametros";
      axios
        .post(url, { params: ['AvancesTesisSize', 'AvancesTesisFormato', 'ActaSize', 'ActaFormato', 'ConstanciaSize', 'ConstanciaFormato'] })
        .then((response) => {
          this.tesisParams.size  = response.data[0][0];
          this.tesisParams.types   = response.data[1];
          this.actaParams.size = response.data[2][0];
          this.actaParams.types  = response.data[3];
          this.constParams.size = response.data[4][0];
          this.constParams.types  = response.data[5];
        });
    },
    getFileRut(element) {
      this.tesisParams.formatError = false;
      this.tesisParams.sizeError = false;
      this.tesisFile = element.target.files[0];
      if (!this.tesisFile) return;
      const fileName = this.tesisFile.name;
      const fileSize = this.tesisFile.size;
      var dots = fileName.split(".");
      var fileType = "." + dots[dots.length - 1];
      if (this.tesisParams.types.join(".").indexOf(fileType) == -1) {
        this.tesisParams.formatError = true;
      }
      if (fileSize >= this.tesisParams.size * 1000000) {
        this.tesisParams.sizeError = true;
      }
    },
    getFileActa (element) {
        this.actaParams.formatError = false;
        this.actaParams.sizeError = false;
        this.actaFile = element.target.files[0];
        if (!this.actaFile) return;
        const fileName = this.actaFile.name;
        const fileSize = this.actaFile.size;
        var dots = fileName.split(".");
        var fileType = "." + dots[dots.length - 1];
        if (this.actaParams.types.join(".").indexOf(fileType) == -1) {
            this.actaParams.formatError = true;
        }
        if (fileSize >= this.actaParams.size * 1000000) {
            this.actaParams.sizeError = true;
        }
    },
    getFileConst (element) {
        this.constParams.formatError = false;
        this.constParams.sizeError = false;
        this.constFile = element.target.files[0];
        if (!this.constFile) return;
        const fileName = this.constFile.name;
        const fileSize = this.constFile.size;
        var dots = fileName.split(".");
        var fileType = "." + dots[dots.length - 1];
        if (this.constParams.types.join(".").indexOf(fileType) == -1) {
            this.constParams.formatError = true;
        }
        if (fileSize >= this.constParams.size * 1000000) {
            this.constParams.sizeError = true;
        }
    },
    getListarAlumnos() {
      this.fullscreenLoading = true;
      var url = "/alumno/getAllUserRoll";
      axios.get(url, {}).then((response) => {
        this.inicializarPaginacion();
        this.listAllUser = response.data;
        this.fullscreenLoading = false;
      });
    },
    getListarVinculacion() {
      this.fullscreenLoading = true;
      var url = "/administracion/vinculacion/getListarVinculacion";
      axios.get(url, {}).then((response) => {
        this.listVinculacion = response.data;
        this.listVinculacion = _.orderBy(this.listVinculacion, "nombre", "asc");
        this.fullscreenLoading = false;
      });
    },
    getListarProfesores() {
      this.fullscreenLoading = true;
      var url = "/alumno/getListarProfesores";
      axios.get(url, {}).then((response) => {
        this.listProfesores = response.data;
        this.listProfesores = _.orderBy(this.listProfesores, "fullname", "asc");
        this.listProfesoresBuscado = this.listProfesores;
        this.fullscreenLoading = false;
      });
    },
    limpiarCriterios() {
      this.fillCrearFIT.cNombre = "";
      this.fillCrearFIT.nIdEscuela = "";
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    validarRegistrarTesis() {
      this.error = 0;
      this.mensajeError = [];
      if (!this.fillCrearFIT.cTitulo) {
        this.mensajeError.push("El Titulo es un campo obligatorio");
      }
      if (!this.fillCrearFIT.nIdPg) {
        this.mensajeError.push("El profesor guia es un campo obligatorio");
      }
      if (!this.fillCrearFIT.cTipo) {
        this.mensajeError.push("El tipo es un campo obligatorio");
      }
      if (!this.fillCrearFIT.Nota) {
        this.mensajeError.push("La Nota es un campo obligatorio");
      }
      if (!this.fillCrearFIT.cObjetivoGeneral) {
        this.mensajeError.push("El objetivo general es un campo obligatorio");
      }
      if (!this.fillCrearFIT.cObjetivoEspecifico) {
        this.mensajeError.push(
          "Los objetivos especificos es un campo obligatorio"
        );
      }
      if (this.tesisParams.sizeError) {
        this.mensajeError.push("El archivo de documento es demasiado pesado");
      }
      if (this.tesisParams.formatError) {
        this.mensajeError.push("Los formatos de los documentos permitidos son:" + this.tesisParams.types);
      }
      if (this.actaParams.sizeError) {
        this.mensajeError.push("El archivo de acta es demasiado pesado");
      }
      if (this.actaParams.formatError) {
        this.mensajeError.push("Los formatos de acta permitidos son:" + this.tesisParams.types);
      }
      if (this.constParams.sizeError) {
        this.mensajeError.push("El archivo de constancia es demasiado pesado");
      }
      if (this.constParams.formatError) {
        this.mensajeError.push("Los formatos de constancia permitidos son:" + this.tesisParams.types);
      }
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    },
    setRegistrarTesisfinalizada() {
      if (this.validarRegistrarTesis()) {
        this.modalShow = true;
        return;
      }
        this.setGuardarTesisfinalizada();
    },
    setGuardarTesisfinalizada() {
      this.fullscreenLoading = true;
      var url = "/archivo/setRegistrarTesisfinalizada";
      this.tesisForm.append("file", this.tesisFile);
      this.actaForm.append("file", this.actaFile);
      this.constForm.append("file", this.constFile);
      this.corroboracionDatoOpcional();
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      axios.post(url, this.fillCrearFIT)
        .then((response) => {
            this.tesisForm.append("id_fit", response.data);
            this.tesisForm.append("type", 'final_t');
            this.actaForm.append("id_fit", response.data);
            this.actaForm.append("type", 'acta');
            this.constForm.append("id_fit", response.data);
            this.constForm.append("type", 'constancia_t');
            let ingreso = {
                tesis: false,
                acta: false,
                const: false
            };
            // tesis
            if (this.tesisFile && this.tesisFile != undefined) {
                axios.post(url, this.tesisForm, config)
                .then((response) => {
                    ingreso.tesis = true;
                    this.terminoIngreso (ingreso);
                })
                .catch((response) => {
                this.fullscreenLoading = false;
                    Swal.fire({
                        icon: "error",
                        title: "Error al ingresar documento",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            } else {
                ingreso.tesis = true;
                this.terminoIngreso (ingreso);
            }
            if (this.actaFile && this.actaFile != undefined) {
                // acta
                axios.post(url, this.actaForm, config)
                .then((response) => {
                    ingreso.acta = true;
                    this.terminoIngreso (ingreso);
                })
                .catch((response) => {
                this.fullscreenLoading = false;
                    Swal.fire({
                        icon: "error",
                        title: "Error al ingresar documento",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            } else {
                ingreso.acta = true;
                this.terminoIngreso (ingreso);
            }
            if (this.constFile && this.constFile != undefined) {
                // constancia
                axios.post(url, this.constForm, config)
                .then((response) => {
                    ingreso.const = true;
                    this.terminoIngreso (ingreso);
                })
                .catch((response) => {
                this.fullscreenLoading = false;
                    Swal.fire({
                        icon: "error",
                        title: "Error al ingresar documento",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            } else {
                ingreso.const = true;
                this.terminoIngreso (ingreso);
            }
        })
        .catch((response) => {
          this.fullscreenLoading = false;
          Swal.fire({
            icon: "error",
            title: "Error al ingresar documento",
            showConfirmButton: false,
            timer: 3000,
          });
        });
    },
    nextPage() {
      this.pageNumber++;
    },
    prevPage() {
      this.pageNumber--;
    },
    selectPage(page) {
      this.pageNumber = page;
    },
    inicializarPaginacion() {
      this.pageNumber = 0;
    },
    setBusquedaUsuario() {
      this.fullscreenLoading = true;
      var url = "/alumno/getUsersAlumnosParametros";
      axios
        .post(url, {
          rut: this.busquedaUsuario.rut,
          email: this.busquedaUsuario.email,
          nombre: this.busquedaUsuario.nombre,
          apellido: this.busquedaUsuario.apellido,
        })
        .then((response) => {
          this.listAlumnosBuscados = response.data;
          this.fullscreenLoading = false;
        });
    },
    mostrarModalBusquedaEstudiante() {
      this.modalSearchUser = !this.modalSearchUser;
      this.busquedaUsuario.email = "";
      this.busquedaUsuario.nombre = "";
      this.busquedaUsuario.apellido = "";
      this.busquedaUsuario.rut = "";
      this.listAlumnosBuscados = [];
    },
    setIngresarNuevoAlumno() {
        let errorIngresoUser = false;
        let errorRegisterUser = false;
        this.newUser.nombres = this.newUser.nombres.charAt(0).toUpperCase() + this.newUser.nombres.slice(1);
        this.newUser.apellidos = this.newUser.apellidos.charAt(0).toUpperCase() + this.newUser.apellidos.slice(1);
        this.listAllUser.forEach((user) => {
          if (user.rut === this.newUser.rut || user.email === this.newUser.email) {
            errorIngresoUser = true;
            this.newUser = user;
          }
        });
        this.fillCrearFIT.cUsers.forEach((user) => {
          if (user.rut === this.newUser.rut || user.email === this.newUser.email) {
            errorRegisterUser = true;
          }
        });
        this.mensajeError = [];
        if (!errorIngresoUser && !errorRegisterUser) {
            this.fillCrearFIT.cUsers.push(Vue.util.extend({}, this.newUser));
        }
        else {
            if (!errorRegisterUser) {
                let alumno = true;
                // verificacion si es alumno
                this.newUser.users__roles.forEach(user_rol => {
                    if (user_rol.roles.name != 'Alumno') {
                        alumno = false;
                    }
                });
                if (alumno) {
                    this.mensajeError.push(
                        "El usuario que intenta crear ya existe, fue insertado el usuario anteriormente creado."
                    );
                    this.fillCrearFIT.cUsers.push(Vue.util.extend({}, this.newUser));
                } else {
                    this.mensajeError.push(
                        "El usuario que intenta crear ya existe, pero es un usuario no válido como integrante."
                    );
                    this.newUser = {};
                }
            } else {
                this.mensajeError.push(
                    "Usuario seleccionado ya está enlistado en tu "+this.terminoTitulo+"."
                );
            }
            this.abrirModal();
        }
        this.mostrarModalBusquedaEstudiante();
    },
    setIngresarAlumno(alumno) {
    // seccion de revisión si usuario es incluido por segunda vez
        let errorIngresoUser = false;
        this.fillCrearFIT.cUsers.forEach((user) => {
            if (user.rut === alumno.rut || user.email === alumno.email) {
                errorIngresoUser = true;
            }
        });
        if (!errorIngresoUser) {
            alumno.estadoConfirmado = "P";
            this.fillCrearFIT.cUsers.push(alumno);
        } else {
            this.mensajeError = [];
            this.mensajeError.push(
            "Usuario seleccionado ya está enlistado en tu "+this.terminoTitulo+"."
            );
            this.abrirModal();
        }
        this.mostrarModalBusquedaEstudiante();
    },
    eliminarEstudiante(estudianteEliminado) {
        let i = 0;
        let eliminado = 0;
        let condicionEliminacion = false;
        this.fillCrearFIT.cUsers.forEach((user) => {
            if (user.rut === estudianteEliminado.rut) {
            eliminado = i;
            condicionEliminacion = true;
            }
            i++;
        });
        if (condicionEliminacion) {
            this.fillCrearFIT.cUsers.splice(eliminado, 1);
        }
    },
    mostrarModalAyuda() {
      this.modalAyuda = !this.modalAyuda;
    },
    mostrarModalComision() {
      this.modalComision = !this.modalComision;
    },
    validarIngresoUsuarioNombre(){
        if (this.newUser.nombres === '' || this.newUser.apellidos === '') {
            this.addUserErrorMessage.nombre = 1;
        } else {
            this.addUserErrorMessage.nombre = 0;
        }
        this.validarErrores();
    },
    validarIngresoUsuarioRut(e){
        if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test( e )) {
                this.addUserErrorMessage.rut = true;
        }
        var tmp 	= e.split('-');
        var digv	= tmp[1];
        var rut 	= tmp[0];
        if ( digv == 'K' ) digv = 'k' ;
        if (this.digitoVerificador(rut) == digv) {
            this.addUserErrorMessage.rut = 0;
        } else {
            this.addUserErrorMessage.rut = 1;
        }
        this.validarErrores();
    },
    digitoVerificador(T){
		var M=0,S=1;
		for(;T;T=Math.floor(T/10))
			S=(S+T%10*(9-M++%6))%11;
		return S?S-1:'k';
	},
    validarIngresoUsuarioEmail(e){
        const reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
        // validacion de email javascript
        if (reg.test(e)) {
            this.addUserErrorMessage.email = 0;
        } else {
            this.addUserErrorMessage.email = 1;
        }
        this.validarErrores();
    },
    validarErrores() {
        if (this.addUserErrorMessage.nombre !== 0 || this.addUserErrorMessage.rut !== 0 ||
            this.addUserErrorMessage.email !== 0) {

            this.addUserErrorMessage.boton = true;
        } else {
            this.addUserErrorMessage.boton = false;
        }
    },
    terminoIngreso(ingreso) {
        if (ingreso.tesis && ingreso.acta && ingreso.const) {
            this.fullscreenLoading = false;
            this.$router.push({ name: "dashboard.index" });
            Swal.fire({
                icon: "success",
                title: "Documentos ingresados correctamente",
                showConfirmButton: false,
                timer: 3000,
            });
        }
    },
    corroboracionDatoOpcional () {
        if (!this.fillCrearFIT.nIdEscuela) {
            this.fillCrearFIT.nIdEscuela = this.listProfesores.find(profesor => profesor.id_user == this.fillCrearFIT.nIdPg).id_escuela;
        }
        if (!this.fillCrearFIT.nIdVinculacion) {
            this.fillCrearFIT.nIdVinculacion = 1;
        }
    },
    getListarProfesoresByEscuela() {
        this.fullscreenLoading = true;
        let id = null;
        if (this.profesorByEscuelaBuscada) {
          id = this.profesorByEscuelaBuscada.id;
        }
        var url = '/reportes/getListarProfesorByEscuela';
        axios.get(url, {
          params: {
            'nIdEscuela' : id
          }
        }).then(response => {
            this.listProfesoresBuscado = response.data;
            this.fullscreenLoading = false;
        })
    }
  }, // cierre methods
};
</script>
<style>
.modal.show .modal-dialog {
    height: 90%;
}
.scrollTable {
    max-height: 100% !important;
}
.noPadNoMar {
    padding-right: 0px;
    padding-left: 0px;
    margin-right: 0px;
    margin-left: 0px;
}
.el-textarea__inner {
    text-align: justify;
}
</style>
