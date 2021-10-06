<template>
  <div>
    <tooltip />
    <div class="content-header" v-loading.fullscreen.lock="fullscreenLoading">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark font-weight-bold">
              <b>Editar {{terminoTituloExtendido}}</b>
            </h1>
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
                  <div class="col-md-6">
                    <h3 class="card-title">Formulario de edición de {{terminoTitulo}}</h3>
                  </div>
                  <div class="col-md-6" style="text-align: right">
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
                            v-model="fillEditarFIT.nIdPg"
                            placeholder="Asignar Profesor Guía"
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
                            v-model="fillEditarFIT.nIdCoPg"
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
                            v-model="fillEditarFIT.cTipo"
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
                            v-model="fillEditarFIT.nIdVinculacion"
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
                            placeholder="Título"
                            show-word-limit
                            v-model="fillEditarFIT.cTitulo"
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
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Contribución"
                            show-word-limit
                            v-model="fillEditarFIT.cContribucion"
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
                            placeholder="Objetivo"
                            show-word-limit
                            v-model="fillEditarFIT.cObjetivoGeneral"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Objetivos específico</label
                        >
                        <div class="col-md-9">
                          <el-input
                            type="textarea"
                            :autosize="{ minRows: 2, maxRows: 10 }"
                            maxlength="7000"
                            placeholder="Objetivos específicos del documento"
                            show-word-limit
                            v-model="fillEditarFIT.cObjetivoEspecifico"
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
                            placeholder="Descripción"
                            show-word-limit
                            v-model="fillEditarFIT.cDescripcion"
                          >
                          </el-input>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <h3>
                        <b>{{
                          "Tabla de integrantes (máximo " +
                          maxStudentNumber +
                          " alumnos)"
                        }}</b>
                      </h3>
                    </div>
                    <div class="table table-responsive">
                    <table
                      class="
                        table table-hover table-head-fixed
                        text-nowrap
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
                          v-for="(item, index) in fillEditarFIT.cUsers"
                          :key="index"
                        >
                          <td>{{ item.nombres + " " + item.apellidos }}</td>
                          <td>{{ item.rut }}</td>
                          <td>{{ item.email }}</td>
                          <td>
                            <template v-if="myOwnUser.id_user && item.id_user">
                              <template
                                v-if="item.id_user !== myOwnUser.id_user"
                              >
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
                              </template>
                              <template v-else>
                                <div class="col-md-3" :key="'boton' + index">
                                  <div class="text-right mb-2">
                                    <button
                                      class="btn btn-danger"
                                      @click.prevent=""
                                      disabled
                                    >
                                      <i class="fas fa-trash"></i>
                                    </button>
                                  </div>
                                </div>
                              </template>
                            </template>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    </div>

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
                      @click.prevent="setEditarTesis"
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
                      <table
                        class="
                          table table-hover table-head-fixed
                          t-fixed
                          projects
                        "
                      >
                        <thead>
                          <tr>
                            <th class="col-md-4">Nombre integrante</th>
                            <th class="col-md-2">Rut</th>
                            <th class="col-md-4">Correo electrónico</th>
                            <th class="col-md-2">Acción</th>
                          </tr>
                        </thead>
                        <tbody>
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
  </div>
</template>

<script>
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
export default {
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillEditarFIT: {
        nIdTesis: this.$attrs.id,
        cTitulo: "",
        nIdPg: "",
        nIdCoPg: "",
        nIdVinculacion: "",
        cTipo: "",
        cObjetivo: "",
        cObjetivoGeneral: "",
        cObjetivoEspecifico: "",
        cDescripcion: "",
        cContribucion: "",
        cUsers: [],
      },
      listTipo: [
        { value: "Tesis", label: "Tesis" },
        { value: "Memoria", label: "Memoria" },
        { value: "Proyecto de titulo", label: "Proyecto de titulo" },
      ],
      busquedaUsuario: {
        nombre: "",
        apellido: "",
        rut: "",
        email: "",
      },
      listProfesores: [],
      listEscuelas: [],
      listVinculacion: [],
      listAlumnosBuscados: [],
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      myOwnUser: {},
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
      maxStudentNumber: 2,
      booleanFunctionOnMounted: {
        getListarProfesores: false,
        getMyOwnUser: false,
        getTesisById: false,
        getListarVinculacion: false,
        getParametros: false
      },
    };
  },
  computed: {},
  mounted() {
    EventBus.$emit('navegar', 'Editar FID');
    this.getListarProfesores();
    this.getMyOwnUser();
    this.getTesisById();
    this.getListarVinculacion();
    this.getParametros();
  },
  methods: {
    getListarProfesores() {
      this.fullscreenLoading = true;
      var url = "/alumno/getListarProfesores";
      axios.get(url, {}).then((response) => {
        this.listProfesores = response.data;
        this.listProfesores = _.orderBy(this.listProfesores, "fullname", "asc");
        this.booleanFunctionOnMounted.getListarProfesores = true;
        if (this.globFunct.booleanElements(this.booleanFunctionOnMounted)) this.fullscreenLoading = false;
      });
    },
    getListarVinculacion() {
      this.fullscreenLoading = true;
      var url = "/administracion/vinculacion/getListarVinculacionActiva";
      axios.get(url, {}).then((response) => {
        //this.inicializarPaginacion();
        this.listVinculacion = response.data;
        this.listVinculacion = _.orderBy(this.listVinculacion, "nombre", "asc");
        this.booleanFunctionOnMounted.getListarVinculacion = true;
        if (this.globFunct.booleanElements(this.booleanFunctionOnMounted)) this.fullscreenLoading = false;
      });
    },
    limpiarCriterios() {
      this.fillEditarFIT.cNombre = "";
      this.fillEditarFIT.nIdEscuela = "";
    },
    abrirModal() {
      this.modalShow = !this.modalShow;
    },
    validarRegistrarTesis() {
      this.error = 0;
      this.mensajeError = [];
      if (!this.fillEditarFIT.cTitulo) {
        this.mensajeError.push("El Titulo es un campo obligatorio");
      }
      if (!this.fillEditarFIT.nIdPg) {
        this.mensajeError.push("El profesor guia es un campo obligatorio");
      }
      if (this.fillEditarFIT.nIdPg === this.fillEditarFIT.nIdCoPg) {
        this.mensajeError.push(
          "No es posible asignar al mismo profesor guía como coguía"
        );
      }
      if (!this.fillEditarFIT.cTipo) {
        this.mensajeError.push("El tipo es un campo obligatorio");
      }
      if (!this.fillEditarFIT.cObjetivoGeneral) {
        this.mensajeError.push("El objetivo general es un campo obligatorio");
      }
      if (!this.fillEditarFIT.cObjetivoEspecifico) {
        this.mensajeError.push(
          "Los objetivos especificos es un campo obligatorio"
        );
      }
      if (!this.fillEditarFIT.cContribucion) {
        this.mensajeError.push("La contribucion es un campo obligatorio");
      }
      if (!this.fillEditarFIT.nIdVinculacion) {
        this.fillEditarFIT.nIdVinculacion = 1;
      }
      if (this.fillEditarFIT.cUsers.length === 0) {
        this.mensajeError.push(
          "No existen alumnos agregados al formulario de documento"
        );
      }
      if (this.fillEditarFIT.cUsers.length > this.maxStudentNumber) {
        this.mensajeError.push("Límite de estudiantes ingresado excedido");
      }
      if (this.mensajeError.length) {
        this.error = 1;
      }
      return this.error;
    },
    setEditarTesis() {
      if (this.validarRegistrarTesis()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = "/alumno/setEditarTesis";
      axios
        .post(url, {
          id: this.fillEditarFIT.nIdTesis,
          titulo: this.fillEditarFIT.cTitulo,
          id_profesorguia: this.fillEditarFIT.nIdPg,
          id_profesor_coguia: this.fillEditarFIT.nIdCoPg,
          id_vinculacion: this.fillEditarFIT.nIdVinculacion,
          tipo: this.fillEditarFIT.cTipo,
          objetivoGeneral: this.fillEditarFIT.cObjetivoGeneral,
          objetivoEspecifico: this.fillEditarFIT.cObjetivoEspecifico,
          descripcion: this.fillEditarFIT.cDescripcion,
          contribucion: this.fillEditarFIT.cContribucion,
          aprobado_pg: "P",
          users: this.fillEditarFIT.cUsers,
        })
        .then((response) => {
          this.fullscreenLoading = false;
            Swal.fire({
                icon: 'success',
                title: 'Se ha editado el '+ this.terminoTitulo +' exitosamente.',
                showConfirmButton: false,
                timer: 1500
            });
          this.$router.push("/tesis");
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
    getTesisById() {
      this.fullscreenLoading = true;
      var url = "/alumno/getListarTesisView";
      axios
        .get(url, {
          params: {
            nIdTesis: this.fillEditarFIT.nIdTesis,
          },
        })
        .then((response) => {
          this.getUsuarioVer(response.data);
        this.booleanFunctionOnMounted.getTesisById = true;
        if (this.globFunct.booleanElements(this.booleanFunctionOnMounted)) this.fullscreenLoading = false;
        });
    },
    getUsuarioVer(data) {
      this.fillEditarFIT.nIdPg = data.id_p_guia;
      this.fillEditarFIT.nIdCoPg = data.id_p_co_guia;
      this.fillEditarFIT.cTitulo = data.titulo;
      this.fillEditarFIT.nIdVinculacion = data.id_vinculacion;
      this.fillEditarFIT.cTipo = data.tipo;
      this.fillEditarFIT.cObjetivoGeneral = data.objetivo_general;
      this.fillEditarFIT.cObjetivoEspecifico = data.objetivo_especifico;
      this.fillEditarFIT.cDescripcion = data.descripcion;
      this.fillEditarFIT.cContribucion = data.contribucion;
      data.fit__user.forEach((user) => {
        this.fillEditarFIT.cUsers.push(user.user);
      });
    },
    getMyOwnUser() {
      this.fullscreenLoading = true;
      var url = "/authenticate/getMyOwnUser";
      this.myOwnUser = {};
      axios.get(url, {}).then((response) => {
        this.myOwnUser = response.data;
        this.myOwnUser.estadoConfirmado = "A";
        // revisión si es profesor u otro derivado y añadir en la lista,
        // para parche de query antigua multiproposito
        let boolRolCoincidente = false;
        this.myOwnUser.users__roles.forEach((user_role) => {
          if (
            user_role.roles.name === "Profesor" ||
            user_role.roles.name === "Director" ||
            user_role.roles.name === "Coordinador"
          ) boolRolCoincidente = true;
        });
        if (boolRolCoincidente) {
            const profesorGuia = {
              fullname: this.myOwnUser.nombres + " " + this.myOwnUser.apellidos,
              id_user: this.myOwnUser.id_user,
            };
            this.listProfesores.unshift(profesorGuia);
            this.listProfesores = _.orderBy(
              this.listProfesores,
              "fullname",
              "asc"
            );
        }
        this.booleanFunctionOnMounted.getMyOwnUser = true;
        if (this.globFunct.booleanElements(this.booleanFunctionOnMounted)) this.fullscreenLoading = false;
      });
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
    setIngresarAlumno(alumno) {
      // seccion de revisión si usuario es incluido por segunda vez
      let errorIngresoUser = false;
      this.fillEditarFIT.cUsers.forEach((user) => {
        if (user.id_user === alumno.id_user) {
          errorIngresoUser = true;
        }
      });
      if (!errorIngresoUser) {
        alumno.estadoConfirmado = "P";
        this.fillEditarFIT.cUsers.push(alumno);
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
      this.fillEditarFIT.cUsers.forEach((user) => {
        if (user.id_user === estudianteEliminado.id_user) {
          eliminado = i;
          condicionEliminacion = true;
        }
        i++;
      });
      if (condicionEliminacion) {
        this.fillEditarFIT.cUsers.splice(eliminado, 1);
      }
    },
    mostrarModalBusquedaEstudiante() {
      this.modalSearchUser = !this.modalSearchUser;
      this.busquedaUsuario.email = "";
      this.busquedaUsuario.nombre = "";
      this.busquedaUsuario.apellido = "";
      this.busquedaUsuario.rut = "";
      this.listAlumnosBuscados = [];
    },
    mostrarModalAyuda() {
      this.modalAyuda = !this.modalAyuda;
    },
    getParametros() {
      var url = "/admin/parametros";
      axios.post(url, { params: ["MaxStudentNumber"] }).then((response) => {
        this.maxStudentNumber = parseInt(response.data[0][0]);
        this.booleanFunctionOnMounted.getParametros = true;
        if (this.globFunct.booleanElements(this.booleanFunctionOnMounted)) this.fullscreenLoading = false;
      });
    },
  }, // cierre methods
};
</script>

<style>
.el-textarea__inner {
    text-align: justify;
}
</style>
