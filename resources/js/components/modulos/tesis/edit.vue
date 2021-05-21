<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Editar formulario de inscripcion de tesis</h1>
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
                            <h3 class="card-title">Formulario de edicion  FIT</h3>
                        </div>
                            <div class="card-body">
                                <form role="form">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Profesor Guia</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillEditarFIT.nIdPg"
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
                                                    <el-select v-model="fillEditarFIT.cTipo"
                                                    placeholder="Seleccione un Tipo"
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
                                                      placeholder="Objetivo"
                                                      show-word-limit
                                                      v-model="fillEditarFIT.cTitulo">
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
                                                      placeholder="Contribucion"
                                                      show-word-limit
                                                      v-model="fillEditarFIT.cContribucion">
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
                                                      placeholder="Objetivo"
                                                      show-word-limit
                                                      v-model="fillEditarFIT.cObjetivo">
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
                                                      placeholder="Objetivo"
                                                      show-word-limit
                                                      v-model="fillEditarFIT.cDescripcion">
                                                    </el-input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-3 col-form-label">Vinculacion</label>
                                                <div class="col-md-9">
                                                    <el-select v-model="fillEditarFIT.nIdVinculacion"
                                                    placeholder="Asignar Vinculacion"
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

                                        <!-- revision de estado de una promesa -->
                                        <div class="col-md-6"></div>
                                        <template v-if="fillEditarFIT.cUsers.length">
                                            <template v-for="(item, index) in fillEditarFIT.cUsers">
                                                <div class="col-md-9" :key="index">
                                                    <h3><b v-text="'Integrante Nº' + (index+1)"></b></h3>
                                                </div>
                                                <template v-if="myOwnUser.id_user && item.id_user">
                                                    <template v-if="item.id_user !== myOwnUser.id_user">
                                                        <div class="col-md-3" :key="'boton' + index">
                                                            <div class="text-right mb-2">
                                                                <button class="btn btn-danger" @click.prevent="eliminarEstudiante(item)">
                                                                    <i class="fas fa-trash"></i> Eliminar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </template>
                                                <div class="col-md-6" :key="'nombre' + index">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label">Nombre</label>
                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control" v-model="item.nombres" @keyup.enter="setRegistrarTesis">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" :key="'rut' + index">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label">Apellido</label>
                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control" v-model="item.apellidos" @keyup.enter="setRegistrarTesis">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" :key="'telefono' + index">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label">Rut</label>
                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control" v-model="item.rut" @keyup.enter="setRegistrarTesis">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" :key="'email' + index">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label">email</label>
                                                        <div class="col-md-9">
                                                            <input type="text" readonly class="form-control" v-model="item.email" @keyup.enter="setRegistrarTesis">
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
                                        <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarTesis">Editar</button>
                                        <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">Limpiar</button>
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
        <div class="modal-content">
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

  </div>
</template>

<script>
export default {
  data(){
    return{
        fillEditarFIT:{
            nIdTesis: this.$attrs.id,
            cTitulo: '',
            nIdPg: '',
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
        mostrarModal: {
            display: 'block',
            background: '#0000006b',
        },
        ocultarModal: {
            display: 'none',
        },
        error: 0,
        mensajeError:[]
    }
  },
  computed: {
  },
   mounted(){
    this.getListarProfesores();
    this.getTesisById();
    this.getListarVinculacion();
    this.getMyOwnUser();
  },
  methods:{
    getListarProfesores(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarProfesores'
      axios.get(url, {
      }).then(response => {
          this.listProfesores = response.data;
          this.fullscreenLoading = false;
      });
    },
    getListarVinculacion(){
      this.fullscreenLoading = true;
      var url = '/administracion/vinculacion/getListarVinculacion'
      axios.get(url, {
      }).then(response => {
          //this.inicializarPaginacion();
          this.listVinculacion = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriterios(){
      this.fillEditarFIT.cNombre = '';
      this.fillEditarFIT.nIdEscuela = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarRegistrarTesis(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarFIT.cTitulo){
          this.mensajeError.push("El Titulo es un campo obligatorio")
        }
        if(!this.fillEditarFIT.nIdPg){
          this.mensajeError.push("El profesor guia es un campo obligatorio")
        }
        if(!this.fillEditarFIT.cTipo){
          this.mensajeError.push("El tipo es un campo obligatorio")
        }
        if(!this.fillEditarFIT.cObjetivo){
          this.mensajeError.push("El objetivo es un campo obligatorio")
        }
        if(!this.fillEditarFIT.cContribucion){
          this.mensajeError.push("La contribucion es un campo obligatorio")
        }
        if(this.fillEditarFIT.cUsers.length === 0) {
            this.mensajeError.push("No existen alumnos agregados al formulario de tesis")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setEditarTesis(){
      if(this.validarRegistrarTesis()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/alumno/setEditarTesis'
      axios.post(url, {
        'id'                    : this.fillEditarFIT.nIdTesis,
        'titulo'                : this.fillEditarFIT.cTitulo,
        'id_profesorguia'       : this.fillEditarFIT.nIdPg,
        'id_vinculacion'        : this.fillEditarFIT.nIdVinculacion,
        'tipo'                  : this.fillEditarFIT.cTipo,
        'objetivo'              : this.fillEditarFIT.cObjetivo,
        'descripcion'           : this.fillEditarFIT.cDescripcion,
        'contribucion'          : this.fillEditarFIT.cContribucion,
        'aprobado_pg'           : 'P',
        'users'                 : this.fillEditarFIT.cUsers
      }).then(response => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Editado Correctamente',
          showConfirmButton: false,
          timer: 1500
        })
        this.$router.push('/tesis');
      })
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
    getTesisById(){
        this.fullscreenLoading = true;
        var url = '/alumno/getListarTesisView'
        axios.get(url, {
        params: {
          'nIdTesis' : this.fillEditarFIT.nIdTesis
        }
      }).then(response => {
          this.getUsuarioVer(response.data);
          this.fullscreenLoading = false;
      })
    },
    getUsuarioVer(data){
        this.fillEditarFIT.nIdPg = data.id_p_guia;
        this.fillEditarFIT.cTitulo = data.titulo;
        this.fillEditarFIT.nIdVinculacion = data.id_vinculacion;
        this.fillEditarFIT.cTipo = data.tipo;
        this.fillEditarFIT.cObjetivo = data.objetivo;
        this.fillEditarFIT.cDescripcion = data.descripcion;
        this.fillEditarFIT.cContribucion = data.contribucion;
        data.fit__user.forEach(user => {
            this.fillEditarFIT.cUsers.push(user.user);
        });
    },
    getMyOwnUser(){
        this.fullscreenLoading = true;
        var url = '/authenticate/getMyOwnUser';
        this.myOwnUser = {};
        axios.get(url, {
        }).then(response => {
            this.myOwnUser = response.data;
            this.myOwnUser.estadoConfirmado = 'A';
            // revisión si es profesor u otro derivado y añadir en la lista,
            // para parche de query antigua multiproposito
            this.myOwnUser.users__roles.forEach(user_role => {
                if(user_role.roles.name === 'Profesor' || user_role.roles.name === 'Director' || user_role.roles.name === 'Coordinador'){
                    const profesorGuia = {
                        'fullname' : this.myOwnUser.nombres + ' ' + this.myOwnUser.apellidos,
                        'id_user' : this.myOwnUser.id_user
                    };
                    this.listProfesores.unshift(profesorGuia);
                }
            });
            this.fullscreenLoading = false;
        })
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
        this.fillEditarFIT.cUsers.forEach(user => {
            if (user.id_user === alumno.id_user) {
              errorIngresoUser = true;
            }
        });
        if (!errorIngresoUser) {
            alumno.estadoConfirmado = 'P';
            this.fillEditarFIT.cUsers.push(alumno);
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
        this.fillEditarFIT.cUsers.forEach(user => {
            if (user.id_user === estudianteEliminado.id_user) {
                eliminado = i;
                condicionEliminacion = true;
            }
            i++;
        });
        if (condicionEliminacion) {
            this.fillEditarFIT.cUsers.splice(eliminado,1);
        }
    },
    mostrarModalBusquedaEstudiante(){
        this.modalSearchUser = !this.modalSearchUser;
        this.busquedaUsuario.email = '';
        this.busquedaUsuario.nombre = '';
        this.busquedaUsuario.apellido = '';
        this.busquedaUsuario.rut = '';
        this.listAlumnosBuscados = [];
    },

  }// cierre methods
}
</script>

<style>

</style>
