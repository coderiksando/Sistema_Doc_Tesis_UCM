<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark font-weight-bold">{{terminoTituloExtendido}}</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i> Regresar
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{terminoTituloExtendido}}</h3>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Profesor Guía</label>
                                            <div class="col-md-9">
                                                <el-input
                                                    type="text"
                                                    :autosize="{ minRows: 2, maxRows: 10}"
                                                    :disabled="true"
                                                    v-model="fillVerFIT.cProfesorguia">
                                                </el-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Profesor Co-guía</label>
                                            <div class="col-md-9">
                                                <el-input
                                                    type="text"
                                                    :autosize="{ minRows: 2, maxRows: 10}"
                                                    :disabled="true"
                                                    v-model="fillVerFIT.cProfesorCoGuia">
                                                </el-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Tipo de trabajo</label>
                                            <div class="col-md-9">
                                                <el-input
                                                    type="text"
                                                    :autosize="{ minRows: 2, maxRows: 10}"
                                                    :disabled="true"
                                                    v-model="fillVerFIT.cTipo">
                                                </el-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Vinculación</label>
                                            <div class="col-md-9">
                                            <el-input
                                                type="text"
                                                :autosize="{ minRows: 2, maxRows: 10}"
                                                :disabled="true"
                                                v-model="fillVerFIT.nIdVinculacion">
                                            </el-input>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Título</label>
                                            <div class="col-md-9">
                                                <el-input
                                                    type="textarea"
                                                    :autosize="{ minRows: 2, maxRows: 10}"
                                                    :disabled="true"
                                                    v-model="fillVerFIT.cTitulo">
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
                                                :autosize="{ minRows: 2, maxRows: 10}"
                                                :disabled="true"
                                                v-model="fillVerFIT.cContribucion">
                                            </el-input>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Objetivo general</label>
                                        <div class="col-md-9">
                                            <el-input
                                                type="textarea"
                                                :autosize="{ minRows: 2, maxRows: 10}"
                                                :disabled="true"
                                                v-model="fillVerFIT.cObjetivoGeneral">
                                            </el-input>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Objetivos específicos</label>
                                        <div class="col-md-9">
                                            <el-input
                                                type="textarea"
                                                :autosize="{ minRows: 2, maxRows: 10}"
                                                :disabled="true"
                                                v-model="fillVerFIT.cObjetivoEspecifico">
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
                                                :autosize="{ minRows: 2, maxRows: 10}"
                                                :disabled="true"
                                                v-model="fillVerFIT.cDescripcion">
                                            </el-input>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h3><b v-text="'Tabla de integrantes'"></b></h3>
                                    </div>
                                    <div class="table table-responsive">
                                    <table class ="table table-hover table-head-fixed text-nowrap projects">
                                        <thead>
                                            <tr>
                                                <th>Nombre integrante</th>
                                                <th>Rut</th>
                                                <th>Correo electrónico</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in fillVerFIT.users" :key="index">
                                            <td>{{item.user.nombres + ' ' + item.user.apellidos}}</td>
                                            <td>{{item.user.rut}}</td>
                                            <td>{{item.user.email}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    <template  v-if="listRolPermisosByUsuario.includes('tesis.aprobar')">
                                        <template v-if="originalResponse.aprobado_pg == 'P' && rolActivo =='Profesor' || originalResponse.aprobado_pg == 'A' && rolActivo !='Profesor'">
                                            <div class="col-md-6 mx-auto">
                                                <button :title="'Aprobar '+terminoTitulo" class="btn btn-flat btn-success btnWidth" @click.prevent="setCambiarEstadoFIT(1, fillVerFIT.nIdTesis)">
                                                    <i class="fas fa-check"></i> Aceptar
                                                </button>
                                                <button :title="'Rechazar '+terminoTitulo" class="btn btn-flat btn-danger btnWidth" @click.prevent="setCambiarEstadoFITRechazo(2, fillVerFIT.nIdTesis)">
                                                    <i class="fas fa-times"></i> Rechazar
                                                </button>
                                            </div>
                                        </template>
                                    </template>
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
            <h5 class="modal-title">Sistema de documentos UCM</h5>
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
  </div>
</template>

<script>
export default {
  data(){
    return{
      fillVerFIT:{
        nIdTesis: this.$attrs.id,
        cTitulo: '',
        nIdPg: '',
        nIdCoPg: '',
        nIdVinculacion: '',
        cTipo: '',
        cObjetivoGeneral: '',
        cObjetivoEspecifico: '',
        cContribucion: '',
        cDescripcion: '',
        users: []
      },
      listTipo: [
        {value: 'Tesis', label: 'Tesis'},
        {value: 'Memoria', label: 'Memoria'}
      ],
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      rolActivo : JSON.parse(localStorage.getItem('rolActivo')),
      listEscuelas:[],
      listVinculacion:[],
      fullscreenLoading: false,
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      mensajeError:[],
      originalResponse: {}
    }
  },
  computed: {
  },
   mounted(){
    this.getTesisById();
    this.getListarVinculacion();
  },
  methods:{
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
      this.fillVerFIT.cNombre = '';
      this.fillVerFIT.nIdEscuela = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
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
          'nIdTesis' : this.fillVerFIT.nIdTesis
        }
      }).then(response => {
          this.getUsuarioVer(response.data);
          this.originalResponse = response.data;
        //   console.log(this.originalResponse);
          this.fullscreenLoading = false;
      })
    },
    getUsuarioVer(data){
        console.log(data);
        this.fillVerFIT.cTitulo = data.titulo;
        this.fillVerFIT.cProfesorguia = data.user__p__guia.nombres + ' ' + data.user__p__guia.apellidos;
        if (data.user__p__coguia) {
            this.fillVerFIT.cProfesorCoGuia = data.user__p__coguia.nombres + ' ' + data.user__p__coguia.apellidos;
        }
        this.fillVerFIT.nIdVinculacion = data.vinculaciones.nombre;
        this.fillVerFIT.cTipo = data.tipo;
        this.fillVerFIT.cObjetivoGeneral = data.objetivo_general;
        this.fillVerFIT.cObjetivoEspecifico = data.objetivo_especifico;
        this.fillVerFIT.cContribucion = data.contribucion;
        this.fillVerFIT.cDescripcion = data.descripcion;
        this.fillVerFIT.users = data.fit__user;
    },
    setCambiarEstadoFIT(op, id){
        Swal.fire({
            title: '¿Está seguro que desea ' + ((op == 1) ? 'aprobar ' : 'rechazar ') + 'el formulario de inscripción?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: ((op == 1) ? 'Si, Aprobar' : 'No, Rechazar'),
            cancelButtonText: 'Cancelar',
        }).then((result) => {
        if (result.value) {
            this.fullscreenLoading = true;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
            'nIdTesis' : id,
            'cEstadoPg'    : (op == 1) ? 'A' : 'R'
            }).then(response => {
                this.fullscreenLoading = false;
                Swal.fire({
                icon: 'success',
                title: 'Se ' + ((op == 1) ? 'aprobó ' : 'rechazó ') +'el formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                });
                this.$router.push('/tesis');
            })
        }
        })
    },
    setCambiarEstadoFITRechazo (op, id) {
        Swal.fire({
            title: 'Escriba el motivo de su rechazo (opcional)',
            icon: 'warning',
            input: 'textarea',
            inputAttributes: {autocapitalize: 'off'},
            showCancelButton: true,
            confirmButtonText: 'Enviar rechazo',
            cancelButtonText: 'Cancelar rechazo',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((response) => {
        if (response.value) {
            this.fullscreenLoading = false;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
                'nIdTesis'  : id,
                'cEstadoPg' : (op == 1) ? 'A' : 'R',
                'motivo'    : response.value
            })
            .then(response => {
                Swal.fire({
                icon: 'success',
                title: 'Se ' + ((op == 1) ? 'aprobó ' : 'rechazó ') +'el formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                });
                this.$router.push('/tesis');
            })
        }
        })
    }
  }// cierre methods
}
</script>

<style>
.el-input.is-disabled .el-input__inner {
    color: black;
}
.el-textarea.is-disabled .el-textarea__inner {
    color: black;
}
</style>
