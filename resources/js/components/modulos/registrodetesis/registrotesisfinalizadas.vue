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
                <h3 class="card-title">
                  Formulario registro rapido de documentos finalizados
                </h3>
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
                          >Objetivos específico</label
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
                              :precision="2"
                              :step="0.1"
                              :max="7"
                            ></el-input-number>
                          </template>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label"
                          >Archivo asociado</label
                        >
                        <div class="col-md-9">
                          <input
                            type="file"
                            accept="application/pdf"
                            class="form-control"
                            @change="getFile"
                          />
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
                            <th class="col-md-4">Nombre integrante</th>
                            <th class="col-md-2">Rut</th>
                            <th class="col-md-4">Correo electrónico</th>
                            <th class="col-md-2">Acción</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
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
                                  ></el-input>
                                </div>
                              </div>
                            </td>
                            <td>
                              <el-input
                                type="text"
                                maxlength="250"
                                placeholder="11111111-1"
                                v-model="newUser.rut"
                              ></el-input>
                            </td>
                            <td>
                              <el-input
                                type="text"
                                maxlength="250"
                                placeholder="ejemplo@alu.ucm.cl"
                                v-model="newUser.email"
                              ></el-input>
                            </td>
                            <td>
                              <button
                                class="btn btn-info bnt-sm"
                                @click.prevent="setIngresarNuevoAlumno"
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
                      <!-- <div class="row" v-for="(item, index) in listAlumnosBuscados" :key="index">
                                        <button class="btn btn-primary w-100 mb-2"  @click="setIngresarAlumno(item)">
                                                <p>Nombre: {{item.nombres}} {{item.apellidos}}</p>
                                                <p>Rut: {{item.rut}}</p>
                                        </button>
                                    </div> -->
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
      form: new FormData(),
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
      newUser: {
        nombres: "",
        apellidos: "",
        rut: "",
        email: "",
      },
      listProfesores: [],
      listEscuelas: [],
      listVinculacion: [],
      listAlumnos: [],
      listAlumnosBuscados: [],
      fullscreenLoading: false,
      modalShow: false,
      modalSearchUser: false,
      mostrarModal: {
        display: "block",
        background: "#0000006b",
      },
      ocultarModal: {
        display: "none",
      },
      error: 0,
      mensajeError: [],
    };
  },
  computed: {},
  mounted() {
    this.getListarProfesores();
    this.getListarVinculacion();
    this.getListarAlumnos();
  },
  methods: {
    getFile(e) {
      this.fillCrearFIT.oArchivo = e.target.files[0];
    },
    onChange() {
      // console.log("wena");
      //console.log("lista alumnos", this.listAlumnos);
      //console.log("id estudiante", this.fillCrearFIT.nIdEst);
      // console.log("incluye fullname", Object.keys(this.listAlumnos[0]).includes('fullname'));
      // console.log("incluye alumno8", Object.values(this.listAlumnos[0]).includes('alumno8 alumno8'));
      // console.log("incluye emilio", Object.values(this.listAlumnos[0]).includes('emilio'));
      // console.log("Object.values(this.listAlumnos[0])", Object.values(this.listAlumnos[0]));
      // console.log("Object.values(this.listAlumnos)", Object.values(this.listAlumnos));
      // //console.log("incluye2", this.listAlumnos[0].includes('fullname'));
      // //console.log("incluye333", this.listAlumnos[0].includes('emilio'));
      // console.log("incluye fullname", this.listAlumnos[0].fullname.includes('fullname'));
      // console.log("imprime[0] fullname", this.listAlumnos[0].fullname);
      // console.log("incluye emilio", this.listAlumnos[0].fullname.includes('emilio'));
      // console.log("------------------------------------");

      const found = this.listAlumnos.some(
        (el) => el.id_user === this.fillCrearFIT.nIdEst
      );
      //console.log("hallado",found)
      if (found) {
        let position = this.listAlumnos.findIndex(
          (x) => x.id_user == this.fillCrearFIT.nIdEst
        );
        //console.log("full name position", this.listAlumnos[position].fullname);
        this.fillCrearFIT.cNombreI1 = this.listAlumnos[position].fullname;
        //console.log("cnombreI1", this.fillCrearFIT.cNombreI1);
      }
    },
    getListarAlumnos() {
      this.fullscreenLoading = true;
      var url = "/alumno/getListarEstudiantes";
      axios.get(url, {}).then((response) => {
        this.inicializarPaginacion();
        this.listAlumnos = response.data;
        this.fullscreenLoading = false;
      });
    },
    getListarVinculacion() {
      this.fullscreenLoading = true;
      var url = "/administracion/vinculacion/getListarVinculacion";
      axios.get(url, {}).then((response) => {
        this.listVinculacion = response.data;
        this.listVinculacion = _.orderBy(this.listVinculacion, 'nombre', 'asc');
        this.fullscreenLoading = false;
      });
    },
    getListarProfesores() {
      this.fullscreenLoading = true;
      var url = "/alumno/getListarProfesores";
      axios.get(url, {}).then((response) => {
        this.listProfesores = response.data;
        this.listProfesores = _.orderBy(this.listProfesores, 'fullname', 'asc');
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
        if (!this.fillCrearFIT.oArchivo) {
            this.mensajeError.push("El archivo de tesis un campo obligatorio");
        }
        if(!this.fillCrearFIT.cObjetivoGeneral){
            this.mensajeError.push("El objetivo general es un campo obligatorio")
        }
        if(!this.fillCrearFIT.cObjetivoEspecifico){
            this.mensajeError.push("Los objetivos especificos es un campo obligatorio")
        }
        if (this.mensajeError.length) {
            this.error = 1;
        }
        return this.error;
    },
    setRegistrarTesisfinalizada() {
        console.log(this.fillCrearFIT);
      if (this.validarRegistrarTesis()) {
        this.modalShow = true;
        return;
      }
      if (
        !this.fillCrearFIT.oArchivo ||
        this.fillCrearFIT.oArchivo == undefined
      ) {
        this.fullscreenLoading = true;
        this.setGuardarTesisfinalizada();
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF() {
      this.form.append("file", this.fillCrearFIT.oArchivo);
      const config = { headers: { "Content-Type": "multipart/form-data" } };
      var url = "/archivo/setRegistrarArchivoPDF";
      axios.post(url, this.form, config).then((response) => {
        var nIdFile = response.data.id;
        this.setGuardarTesisfinalizada(nIdFile);
      });
    },
    setGuardarTesisfinalizada(nIdFile) {
      if (this.validarRegistrarTesis()) {
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = "/alumno/setRegistrarTesisfinalizada";
      axios
        .post(url, {
          cTitulo: this.fillCrearFIT.cTitulo,
          Nota: this.fillCrearFIT.Nota,
          nIdPg: this.fillCrearFIT.nIdPg,
          nIdEst: this.fillCrearFIT.nIdEst,
          nIdFile: nIdFile,
          nIdVinculacion: this.fillCrearFIT.nIdVinculacion,
          cTipo: this.fillCrearFIT.cTipo,
          dFechaUR: this.fillCrearFIT.dFechaUR,
          cObjetivo: this.fillCrearFIT.cObjetivo,
          cDescripcion: this.fillCrearFIT.cDescripcion,
          cContribucion: this.fillCrearFIT.cContribucion,
          cNombreI1: this.fillCrearFIT.cNombreI1,
          cRutI1: this.fillCrearFIT.cRutI1,
          cTelefonoI1: this.fillCrearFIT.cTelefonoI1,
          cIngresoI1: this.fillCrearFIT.cIngresoI1,
          cEmailI1: this.fillCrearFIT.cEmailI1,
          cNombreI2: this.fillCrearFIT.cNombreI2,
          cRutI2: this.fillCrearFIT.cRutI2,
          cEmailI2: this.fillCrearFIT.cEmailI2,
          cIngresoI2: this.fillCrearFIT.cIngresoI2,
          cTelefonoI2: this.fillCrearFIT.cTelefonoI2,
        })
        .then((response) => {
          this.fullscreenLoading = false;
          this.$router.push("/");
          Swal.fire({
            icon: "success",
            title: "Documento ingresado correctamente",
            showConfirmButton: false,
            timer: 1500,
          });
        })
        .catch((response) => {
          this.fullscreenLoading = false;
          Swal.fire({
            icon: "error",
            title: "Error al ingresar documento",
            showConfirmButton: false,
            timer: 1500,
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
      this.newUser.nombres = this.newUser.nombres.charAt(0).toUpperCase() + this.newUser.nombres.slice(1);
      this.newUser.apellidos = this.newUser.apellidos.charAt(0).toUpperCase() + this.newUser.apellidos.slice(1);
      this.fillCrearFIT.cUsers.forEach((user) => {
        if (user.rut === this.newUser.rut || user.email === this.newUser.email) {
          errorIngresoUser = true;
        }
      });
      if (!errorIngresoUser) {
        this.fillCrearFIT.cUsers.push(Vue.util.extend({}, this.newUser));
        this.newUser.nombres = '';
        this.newUser.apellidos = '';
        this.newUser.rut = '';
        this.newUser.email = '';
      } else {
        this.mensajeError = [];
        this.mensajeError.push(
          "Usuario seleccionado ya está enlistado en tu FIT."
        );
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
  }, // cierre methods
};
</script>
<style>
</style>
