<template>
  <div>

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
                            <h3 class="card-title">Formulario  inscripcion de tesis</h3>
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
                                                <label class="col-md-3 col-form-label">Titulo</label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="130"
                                                      placeholder="Titulo de la tesis"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cTitulo">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Contribucion</label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="500"
                                                      placeholder="Contribucion esperada"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cContribucion">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Objetivo
                                                </label>
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
                                                <label class="col-md-3 col-form-label">Descripcion
                                                </label>
                                                <div class="col-md-9">
                                                    <el-input
                                                      type="textarea"
                                                      :autosize="{ minRows: 2, maxRows: 5}"
                                                      maxlength="500"
                                                      placeholder="Descripcion del tema"
                                                      show-word-limit
                                                      v-model="fillCrearFIT.cDescripcion">
                                                    </el-input>
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
                                        <div class="col-md-6"></div>

                                        <!-- revision de estado de una promesa -->
                                        <div class="col-md-6"></div>
                                            <template v-if="fillCrearFIT.cUsers.length > 0">
                                                <!-- iteración de usuarios dentro de fillcrearfit.cusers -->
                                                <template v-for="(item, index) in fillCrearFIT.cUsers">
                                                    <div class="col-md-12" :key="index">
                                                        <h3><b v-text="'Integrante Nº' + (index+1)"></b></h3>
                                                    </div>
                                                    <div class="col-md-6" :key="'nombre' + index">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Nombre</label>
                                                            <div class="col-md-9">
                                                                <input type="text" readonly class="form-control" v-model="fillCrearFIT.cUsers[index].nombres" @keyup.enter="setRegistrarTesis">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" :key="'rut' + index">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Apellido</label>
                                                            <div class="col-md-9">
                                                                <input type="text" readonly class="form-control" v-model="fillCrearFIT.cUsers[index].apellidos" @keyup.enter="setRegistrarTesis">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" :key="'telefono' + index">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Rut</label>
                                                            <div class="col-md-9">
                                                                <input type="text" readonly class="form-control" v-model="fillCrearFIT.cUsers[index].rut" @keyup.enter="setRegistrarTesis">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" :key="'email' + index">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">email</label>
                                                            <div class="col-md-9">
                                                                <input type="text" readonly class="form-control" v-model="fillCrearFIT.cUsers[index].email" @keyup.enter="setRegistrarTesis">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </template>

                                            <div class="col-md-6">
                                                <button class="btn btn-info bnt-sm" @click.prevent="mostrarModalBusquedaEstudiante">
                                                    Añadir más integrantes +
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
                                <input type="text" class="form-control" v-model="busquedaUsuario.rut">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.email">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Nombre</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.nombre">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Apellido</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="busquedaUsuario.apellido">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9"></div>
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

  </div>
</template>

<script>
export default {
  data(){
    return{
        fillCrearFIT:{
            cTitulo: '',
            nIdPg: '',
            nIdVinculacion: '',
            cTipo: '',
            dFechaUR: '',
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
        myOwnUser: {},
        fullscreenLoading: false,
        modalShow: false,
        modalSearchUser: false,
        mostrarModal: {
            display: 'block',
            background: '#0000006b',
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
            console.log(this.myOwnUser);
            this.myOwnUser.users__roles.forEach(rol_user => {
                if (rol_user.roles.name === "Alumno") {
                    this.fillCrearFIT.cUsers.push(this.myOwnUser);
                }
            });
            this.fullscreenLoading = false;
            console.log(this.fillCrearFIT)
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
      var url = '/alumno/setRegistrarTesis'
      axios.post(url, {
        'cTitulo'            : this.fillCrearFIT.cTitulo,
        'nIdPg'              : this.fillCrearFIT.nIdPg,
        'nIdVinculacion'     : this.fillCrearFIT.nIdVinculacion,
        'cTipo'              : this.fillCrearFIT.cTipo,
        'dFechaUR'           : this.fillCrearFIT.dFechaUR,
        'cObjetivo'          : this.fillCrearFIT.cObjetivo,
        'cDescripcion'       : this.fillCrearFIT.cDescripcion,
        'cContribucion'      : this.fillCrearFIT.cContribucion,
        'cUsers'             : this.fillCrearFIT.cUsers
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
    },
    setBusquedaUsuario() {
        console.log(this.busquedaUsuario);
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
  }// cierre methods
}
</script>
<style>
  .scrollTable{
    overflow: auto !important;
  }
</style>
