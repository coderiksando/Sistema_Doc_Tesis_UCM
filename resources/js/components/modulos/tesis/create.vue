<template>
  <div>
    <tooltip/>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Ingresar formulario de inscripcion de tesis</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/tesis'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
                    <div class="card card-info">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="card-title">Formulario  inscripción de tesis</h3>
                                </div>
                                <div class="col-md-6" style="text-align: right;">
                                    <button class="btn btn-secondary" @click.prevent="mostrarModalAyuda">Ayuda
                                        <i class="fas fa-question-circle" ></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                            <div class="card-body">
                                <form role="form">
                                    <div class="row">
                                      <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Profesor Guia</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillCrearFIT.nIdPg"
                                                    placeholder="Asignar Profesor Guia"
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
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Profesor Co-guía</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillCrearFIT.nIdCoPg"
                                                    placeholder="Asignar Profesor Co-guía"
                                                    clearable
                                                    v-popover:tooltip.bottom="'Este campo es opcional'">
                                                    <el-option
                                                        v-for="item in listProfesores"
                                                        :key="item.id_user"
                                                        :label="item.fullname"
                                                        :value="item.id_user">
                                                    </el-option>
                                                    </el-select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Tipo de trabajo</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillCrearFIT.cTipo"
                                                    placeholder="Seleccione un Tipo de trabajo"
                                                    clearable>
                                                    <el-option
                                                        v-for="item in listTipo"
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
                                                <label class="col-md-3 col-form-label">Vinculacion</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillCrearFIT.nIdVinculacion"
                                                    placeholder="Asignar Vinculacion (opcional)"
                                                    clearable>
                                                    <el-option
                                                        v-for="item in listVinculacion"
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
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="130"
                                                      placeholder="Título de la tesis"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cTitulo">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Contribución</label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="500"
                                                      placeholder="Contribución esperada"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cContribucion">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Objetivo</label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="500"
                                                      placeholder="Objetivo de la tesis"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cObjetivo">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Descripción</label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="500"
                                                      placeholder="Descripción del tema"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cDescripcion">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h3><b v-text="'Tabla de integrantes'"></b></h3>
                                        </div>
                                        <div class="col-md-6">

                                        </div>
                                        <table class ="table table-hover table-head-fixed text-nowrap projects ">
                                            <thead>
                                                <tr>
                                                    <th>Nombre integrante</th>
                                                    <th>Rut</th>
                                                    <th>Correo electrónico</th>
                                                    <th>Acciones </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="(item, index) in fillCrearFIT.cUsers" :key="index">
                                                <td>{{item.nombres + ' ' + item.apellidos}}</td>
                                                <td>{{item.rut}}</td>
                                                <td>{{item.email}}</td>
                                                <td>
                                                    <template v-if="myOwnUser.id_user && item.id_user">
                                                        <template v-if="item.id_user !== myOwnUser.id_user">
                                                            <div class="col-md-3" :key="'boton' + index">
                                                                <div class="text-right mb-2">
                                                                    <button class="btn btn-danger" @click.prevent="eliminarEstudiante(item)">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                        <template v-else>
                                                            <div class="col-md-3" :key="'boton' + index">
                                                                <div class="text-right mb-2">
                                                                    <button class="btn btn-danger" @click.prevent="" disabled>
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
                                        <div class="col-md-6">
                                            <button class="btn btn-info bnt-sm" @click.prevent="mostrarModalBusquedaEstudiante">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-4 offset-4">
                                        <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarTesis" v-loading.fullscreen.lock="fullscreenLoading">
                                            Registrar
                                        </button>
                                        <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">
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

    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content scrollTable">
          <div class="modal-header">
            <h5 class="modal-title">Sistema de tesis UCM</h5>
            <button class="close" @click="abrirModal"></button>
          </div>
          <div class="modal-body">
            <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="abrirModal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" :class="{ show: modalSearchUser }" :style="modalSearchUser ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content scrollTable">
            <div class="modal-header">
                <h5 class="modal-title"><b>Búsqueda de integrante de tesis</b></h5>
                <button class="close" @click="mostrarModalBusquedaEstudiante"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rut</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.rut" @keyup.enter="setBusquedaUsuario">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.email" @keyup.enter="setBusquedaUsuario">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.nombre" @keyup.enter="setBusquedaUsuario">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Apellido</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.apellido" @keyup.enter="setBusquedaUsuario">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <template v-if="listAlumnosBuscados.length">
                                    <div class="row">
                                        <h3><b>Usuarios encontrados:</b></h3>
                                    </div>
                                    <div class="row" v-for="(item, index) in listAlumnosBuscados" :key="index">
                                        <button class="btn btn-primary w-100 mb-2"  @click="setIngresarAlumno(item)">
                                                <p>Nombre: {{item.nombres}} {{item.apellidos}}</p>
                                                <p>Rut: {{item.rut}}</p>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success w-100" @click="setBusquedaUsuario">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" @click="mostrarModalBusquedaEstudiante">Cerrar</button>
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
            <table class ="table table-hover table-head-fixed text-nowrap projects ">
                <thead>
                    <tr>
                        <th>Ícono</th>
                        <th>Nombre</th>
                        <th>Detalle</th>
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
                        <td>Inserta nuevos usuarios para integrar un formulario de ingreso de tesis</td>
                    </tr>
                </tbody>
                </table>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="mostrarModalAyuda">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data(){
    return{
        fillCrearFIT:{
            cTitulo: '',
            nIdPg: '',
            nIdCoPg: '',
            nIdVinculacion: '',
            cTipo: '',
            cObjetivo: '',
            cDescripcion: '',
            cContribucion: '',
            cUsers: []
        },
        listTipo: [
            {value: 'Tesis', label: 'Tesis'},
            {value: 'Memoria', label: 'Memoria'},
            {value: 'Proyecto de titulo', label: 'Proyecto de titulo'}
        ],
        busquedaUsuario: {
            nombre: '',
            apellido: '',
            rut: '',
            email: ''
        },
        listProfesores:[],
        listEscuelas:[],
        listVinculacion:[],
        listAlumnosBuscados: [],
        myOwnUser: {},
        fullscreenLoading: false,
        modalShow: false,
        modalSearchUser: false,
        modalAyuda:false,
        mostrarModal: {
            display: 'block',
            background: '#0000006b'
        },
        ocultarModal: {
            display: 'none',
        },
        thisyear: new Date(),
        error: 0,
        mensajeError:[]
    }
  },
  computed: {
  },
   mounted(){
    this.getListarProfesores();
    this.getListarVinculacion();
    this.getMyOwnUser();
  },
  methods:{
    getListarVinculacion(){
      this.fullscreenLoading = true;
      var url = '/administracion/vinculacion/getListarVinculacionActiva'
      axios.get(url, {
      }).then(response => {
          this.listVinculacion = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarProfesores(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarProfesores'
      axios.get(url, {
      }).then(response => {
          this.listProfesores = response.data;
          this.fullscreenLoading = false;
      })
    },
    getMyOwnUser(){
        this.fullscreenLoading = true;
        var url = '/authenticate/getMyOwnUser';
        this.myOwnUser = {};
        axios.get(url, {
        }).then(response => {
            this.myOwnUser = response.data;
            this.myOwnUser.estadoConfirmado = 'A';
            this.myOwnUser.users__roles.forEach(rol_user => {
                if (rol_user.roles.name === "Alumno") {
                    this.fillCrearFIT.cUsers.push(this.myOwnUser);
                }
            });
            this.fullscreenLoading = false;
        })
    },
    limpiarCriterios(){
      this.fillCrearFIT.cNombre = '';
      this.fillCrearFIT.nIdEscuela = '';
      this.fillCrearFIT.cUsers = [];
      this.getMyOwnUser();
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarRegistrarTesis(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearFIT.cTitulo){
          this.mensajeError.push("El Titulo es un campo obligatorio")
        }
        if(!this.fillCrearFIT.nIdPg){
          this.mensajeError.push("El profesor guia es un campo obligatorio")
        }
        if(this.fillCrearFIT.nIdPg === this.fillCrearFIT.nIdCoPg) {
            this.mensajeError.push("No es posible asignar al mismo profesor guía como coguía")
        }
        if(!this.fillCrearFIT.cTipo){
          this.mensajeError.push("El tipo es un campo obligatorio")
        }
        if(!this.fillCrearFIT.cObjetivo){
          this.mensajeError.push("El objetivo es un campo obligatorio")
        }
        if(!this.fillCrearFIT.cDescripcion){
          this.mensajeError.push("La descripcion es un campo obligatorio")
        }
        if(!this.fillCrearFIT.cContribucion){
          this.mensajeError.push("La contribucion es un campo obligatorio")
        }
        if(!this.fillCrearFIT.nIdVinculacion){
            this.fillCrearFIT.nIdVinculacion = 1;
        }
        if(this.fillCrearFIT.cUsers.length === 0) {
            this.mensajeError.push("No existen alumnos agregados al formulario de tesis")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarTesis(){
      if(this.validarRegistrarTesis()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/alumno/setRegistrarTesis';
      axios.post(url, {
        'data'  : this.fillCrearFIT
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/tesis');
      })
    },
    mostrarModalBusquedaEstudiante(){
        this.modalSearchUser = !this.modalSearchUser;
        this.busquedaUsuario.email = '';
        this.busquedaUsuario.nombre = '';
        this.busquedaUsuario.apellido = '';
        this.busquedaUsuario.rut = '';
        this.listAlumnosBuscados = [];
    },
    setBusquedaUsuario() {
        this.fullscreenLoading = true;
        var url = '/alumno/getUsersAlumnosParametros'
        axios.post(url, {
            'rut'       :   this.busquedaUsuario.rut,
            'email'     :   this.busquedaUsuario.email,
            'nombre'    :   this.busquedaUsuario.nombre,
            'apellido'  :   this.busquedaUsuario.apellido
        }).then(response => {
            this.listAlumnosBuscados = response.data;
            this.fullscreenLoading = false;
        })
    },
    setIngresarAlumno(alumno){
        // seccion de revisión si usuario es incluido por segunda vez
        let errorIngresoUser = false;
        this.fillCrearFIT.cUsers.forEach(user => {
            if (user.id_user === alumno.id_user) {
              errorIngresoUser = true;
            }
        });
        if (!errorIngresoUser) {
            alumno.estadoConfirmado = 'P';
            this.fillCrearFIT.cUsers.push(alumno);
        } else {
            this.mensajeError = [];
            this.mensajeError.push("Usuario seleccionado ya está enlistado en tu FIT.");
            this.abrirModal();
        }
        this.mostrarModalBusquedaEstudiante();
    },
    eliminarEstudiante(estudianteEliminado) {
        let i = 0;
        let eliminado = 0;
        let condicionEliminacion = false;
        this.fillCrearFIT.cUsers.forEach(user => {
            if (user.id_user === estudianteEliminado.id_user) {
                eliminado = i;
                condicionEliminacion = true;
            }
            i++;
        });
        if (condicionEliminacion) {
            this.fillCrearFIT.cUsers.splice(eliminado,1);
        }
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
    mostrarModalAyuda() {
        this.modalAyuda = !this.modalAyuda;
    }
  }// cierre methods
}
</script>
<style>
  .scrollTable{
    overflow: auto !important;
  }
</style>
