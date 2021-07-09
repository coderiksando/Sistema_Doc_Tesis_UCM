<template>
  <div>
    <tooltip />
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark">Registro de documentos finalizados</h1>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/dashboard'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
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
                          >Profesor Guía</label
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
                          >Profesor Co-guía</label
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
                            :autosize="{ minRows: 2, maxRows: 5 }"
                            maxlength="140"
                            placeholder="Título de la tesis"
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
                          >Contribución</label
                        >
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 5 }"
                            maxlength="2000"
                            placeholder="Contribucion esperada"
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
                            maxlength="2000"
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
                            maxlength="2000"
                            placeholder="Objetivos específicos de la tesis"
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
                            :autosize="{ minRows: 2, maxRows: 5 }"
                            maxlength="2000"
                            placeholder="Descripcion del tema"
                            show-word-limit
                            v-model="fillCrearFIT.cDescripcion"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Nota Final Tesis</label
                        >
                        <div class="col-md-9">
                          <template>
                            <el-input-number
                              v-model="fillCrearFIT.Nota"
                              size="large"
                              :min="1"
                              :precision="1"
                              :step="0.1"
                              :max="7"
                            ></el-input-number>
                          </template>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="noPadNoMar col-md-12 form-group row">
                        <label class="noPadNoMar col-md-12 col-form-label">Tesis final</label>
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
                                  'is-invalid': rutParams.formatError || rutParams.sizeError,
                                }"
                                @change="getFileRut"
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
                            v-show="rutParams.formatError"
                          >
                            El formato del archivo no es soportado.
                          </div>
                          <div
                            class="custom-file invalid-feedback no-margin"
                            v-show="rutParams.sizeError"
                          >
                            El tamaño del archivo no puede superar los
                            {{ rutParams.size }} MB.
                          </div>
                          <div class="container">
                            El tamaño máximo de los archivos es: {{rutParams.size}} MB.
                        </div>
                        <div class="container">
                            Los formatos de archivo soportados son:
                        <span v-for="item in rutParams.types" :key="item" v-text="item +' '"></span>
                        </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="noPadNoMar col-md-12 form-group row">
                        <label class="noPadNoMar col-md-12 col-form-label">Acta de defensa de tesis (opcional)</label>
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
                                }"
                                @change="getFileActa"
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
                                }"
                                @change="getFileConst"
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
                      Registrar
                    </button>
                    <button
                      class="btn btn-flat btn-default btnWidth"
                      @click.prevent="limpiarCriterios"
                    >
                      Limpiar
                    </button>
                  </div>
                </div>
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
            <h5 class="modal-title">Sistema de tesis UCM</h5>
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
              Cerrar
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
            <h5 class="modal-title"><b>Búsqueda de integrante de tesis</b></h5>
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
                  Buscar
                </button>
              </div>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-body">
                    <template>
                      <div class="row">
                        <h3><b>Lista de usuarios encontrados:</b></h3>
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
              Cerrar
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
                  <td>Regresar</td>
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
                    ingreso de tesis
                  </td>
                </tr>
                <tr>
                  <td><i class="fas fa-plus-square"></i></td>
                  <td>Insertar</td>
                  <td>
                    Ingresa un estudiante partícipe ya existente o nuevo al FIT
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fillCrearFIT: {
        Nota: "",
        cTitulo: "",
        oArchivo: "",
        nIdPg: "",
        nIdCoPg: "",
        nIdVinculacion: "",
        cTipo: "",
        cObjetivoGeneral: "",
        cObjetivoEspecifico: "",
        cDescripcion: "",
        cContribucion: "",
        cUsers: [],
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
      fullscreenLoading: false,
      modalShow: false,
      modalSearchUser: false,
      modalAyuda: false,
      mostrarModal: {
        display: "block",
        background: "#0000006b",
      },
      ocultarModal: {
        display: "none",
      },
      error: 0,
      mensajeError: [],
      rutParams: {
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
    };
  },
  computed: {},
  mounted() {
    this.getListarProfesores();
    this.getListarVinculacion();
    this.getListarAlumnos();
    this.getParametros();
  },
  methods: {
    getParametros() {
      var url = "/admin/parametros";
      axios
        .post(url, { params: ['AvancesTesisSize', 'AvancesTesisFormato', 'ActaSize', 'ActaFormato', 'ConstanciaSize', 'ConstanciaFormato'] })
        .then((response) => {
          this.rutParams.size  = response.data[0][0];
          this.rutParams.types   = response.data[1];
          this.actaParams.size = response.data[2][0];
          this.actaParams.types  = response.data[3];
          this.constParams.size = response.data[4][0];
          this.constParams.types  = response.data[5];
        });
    },
    getFileRut(element) {
      this.rutParams.formatError = false;
      this.rutParams.sizeError = false;
      this.tesisFile = element.target.files[0];
      if (!this.tesisFile) return;
      const fileName = this.tesisFile.name;
      const fileSize = this.tesisFile.size;
      var dots = fileName.split(".");
      var fileType = "." + dots[dots.length - 1];
      if (this.rutParams.types.join(".").indexOf(fileType) == -1) {
        this.rutParams.formatError = true;
      }
      if (fileSize >= this.rutParams.size * 1000000) {
        this.rutParams.sizeError = true;
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
      if (!this.tesisFile) {
        this.mensajeError.push("El archivo de tesis un campo obligatorio");
      }
      if (!this.fillCrearFIT.cObjetivoGeneral) {
        this.mensajeError.push("El objetivo general es un campo obligatorio");
      }
      if (!this.fillCrearFIT.cObjetivoEspecifico) {
        this.mensajeError.push(
          "Los objetivos especificos es un campo obligatorio"
        );
      }
      if (this.rutParams.sizeError) {
        this.mensajeError.push("El archivo es demasiado pesado");
      }
      if (this.rutParams.formatError) {
        this.mensajeError.push("Los formatos permitidos son:" + this.rutParams.types);
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
      if (
        !this.tesisFile ||
        this.tesisFile == undefined
      ) {
        this.validarRegistrarTesis();
        this.modalShow = true;
        return;
      } else {
        this.setGuardarTesisfinalizada();
      }
    },
    setGuardarTesisfinalizada() {
      this.fullscreenLoading = true;
      var url = "/archivo/setRegistrarTesisfinalizada";
      this.tesisForm.append("file", this.tesisFile);
      this.actaForm.append("file", this.actaFile);
      this.constForm.append("file", this.constFile);
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      axios.post(url, this.fillCrearFIT)
        .then((response) => {
            this.tesisForm.append("id_fit", response.data);
            this.tesisForm.append("type", 'final_t');
            this.actaForm.append("id_fit", response.data);
            this.actaForm.append("type", 'acta');
            this.constForm.append("id_fit", response.data);
            this.constForm.append("type", 'constancia_t');
            // tesis
            axios.post(url, this.tesisForm, config)
            .then((response) => {
            this.fullscreenLoading = false;
                // acta
                axios.post(url, this.actaForm, config)
                .then((response) => {
                    this.fullscreenLoading = false;
                    // constancia
                    axios.post(url, this.constForm, config)
                    .then((response) => {
                        this.fullscreenLoading = false;
                        this.$router.push({ name: "dashboard.index" });
                        Swal.fire({
                            icon: "success",
                            title: "Documentos ingresados correctamente",
                            showConfirmButton: false,
                            timer: 3000,
                        });
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
            console.log(this.newUser)
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
                    "Usuario seleccionado ya está enlistado en tu FIT."
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
            "Usuario seleccionado ya está enlistado en tu FIT."
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
</style>
