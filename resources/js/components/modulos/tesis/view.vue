<template>
  <div class="card">
    <div class="card-header">
      <div class="card-tools">
        <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
          <i class="fas fa-arrow-left"></i> {{globVar.btnBack}}
        </a>
      </div>
    </div>
    <div class="card-body" v-loading="fullscreenLoading">
      <div class="container-fluid">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">{{terminoTituloExtendido}}</h3>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">Prof. Guía</label>
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
                                        <label class="col-md-3 col-form-label">Prof. Co-guía</label>
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
                                    <template v-if="originalResponse.aprobado_pg == 'P' && rolActivo =='Profesor' || (originalResponse.aprobado_pg == 'A' || originalResponse.aprobado_pg == 'P') && rolActivo !='Profesor'">
                                        <div class="col-md-6 mx-auto">
                                            <button :title="'Aprobar '+terminoTitulo" class="btn btn-flat btn-success btnWidth" @click.prevent="acceptHandler">
                                                <i class="fas fa-check"></i> Aceptar
                                            </button>
                                            <button :title="'Rechazar '+terminoTitulo" class="btn btn-flat btn-danger btnWidth" @click.prevent="mostrarModalRechazo=true">
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

    <template v-if="mostrarModalRechazo">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="dismissModal">
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
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="dismissModal">Cancelar</button>
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
                    <button class="close" @click="abrirModal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" @click="abrirModal">{{globVar.btnClose}}</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;">
              <div class="swal2-icon-content">!</div>
            </div>
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Para aprobar el formulario ingrese su comisión sugerida</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content text-left">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
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

  </div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
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
      fillCrearComision:{
        idTesis: this.$attrs.id,
        Profesor1: '',
        Profesor2: '',
        NombrePEx: '',
        EmailPEx: '',
        InstitucionPEx: ''
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
      listProfesores: [],
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
      originalResponse: {},
      mostrarModalRechazo: false,
      mostrarModalApruebo: false,
      motivo : '',
      validEmail: true
    }
  },
  computed: {
  },
   mounted(){
    EventBus.$emit('navegar', 'Formulario de ingreso de documentos (FID)');
    this.getListarMiTesis();
    this.getListarProfesores();
  },
  methods:{
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getTesisById(){
        var url = '/alumno/getListarTesisView'
        axios.get(url, {
        params: {
          'nIdTesis' : this.fillVerFIT.nIdTesis
        }
      }).then(response => {
          this.getUsuarioVer(response.data);
          this.originalResponse = response.data;
          this.motivo = response.data.motivo_pg
          this.fullscreenLoading = false;
      })
    },
    getUsuarioVer(data){
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
    acceptHandler(){
      if (this.rolActivo == 'Profesor' && !this.originalResponse.comisiones) {
        this.mostrarModalApruebo = true;
      }else{
        this.aprobarFIT();
      }

    },
    setCambiarEstadoFIT(){
      if(this.validarRegistrarComision()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/comisiones/setRegistrarComision';
      console.log(this.fillCrearComision);
      axios.post(url, {
        'idTesis'           : this.fillCrearComision.idTesis,
        'Profesor1'         : this.fillCrearComision.Profesor1,
        'Profesor2'         : this.fillCrearComision.Profesor2,
        'NombrePEx'         : this.fillCrearComision.NombrePEx,
        'EmailPEx'          : this.fillCrearComision.EmailPEx,
        'InstitucionPEx'    : this.fillCrearComision.InstitucionPEx
      }).then(response => {
          var url = '/alumno/setCambiarEstadoFIT'
          axios.post(url, {
          'nIdTesis' : this.fillVerFIT.nIdTesis,
          'cEstadoPg'    : 'A'
          }).then(response => {
              this.fullscreenLoading = false;
              Swal.fire({
              icon: 'success',
              title: 'Se aprobó el formulario de inscripción',
              showConfirmButton: false,
              timer: 1500
              });
              this.$router.push('/tesis');
          })
      })
    },
    setCambiarEstadoFITRechazo () {
        this.mostrarModalRechazo = false;
        var url = '/alumno/setCambiarEstadoFIT'
        axios.post(url, {
            'nIdTesis'  : this.fillVerFIT.nIdTesis,
            'cEstadoPg' : 'R',
            'motivo'    : this.motivo
        }).then(response => {
            Swal.fire({
            icon: 'success',
            title: 'Se rechazó el formulario de inscripción',
            showConfirmButton: false,
            timer: 1500
            });
            this.$router.push('/tesis');
        })
    },
    aprobarFIT(){
       Swal.fire({
        title: '¿Está seguro que desea aprobar el formulario de inscripción?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Aprobar',
        cancelButtonText: 'Cancelar',
        }).then((result) => {
        if (result.value) {
            this.fullscreenLoading = true;
            var url = '/alumno/setCambiarEstadoFIT'
            axios.post(url, {
            'nIdTesis' : this.fillVerFIT.nIdTesis,
            'cEstadoPg'    : 'A'
            }).then(response => {
                this.fullscreenLoading = false;
                Swal.fire({
                icon: 'success',
                title: 'Se aprobó el formulario de inscripción',
                showConfirmButton: false,
                timer: 1500
                })
                this.$router.push('/tesis');
            })
        }
        })
    },

    dismissModal(){
      this.mostrarModalRechazo = false;
    },
    getListarMiTesis(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarMiTesis'
      axios.get(url, {
      }).then(response => {
          this.listMiTesis = response.data;
          if (this.listMiTesis.includes(parseInt(this.$attrs.id))) {
            this.getTesisById();
          }else{
            Swal.fire({
              icon: 'warning',
              title: 'No tienes permiso para ver este formulario.',
              showConfirmButton: true,
              timer: 2500
            })
            this.$router.push('/tesis');
          }
      })
    },
    getListarProfesores(){
        var url = '/alumno/getListarProfesores';
        axios.get(url, {
        }).then(response => {
            this.listProfesores = response.data;
        })
    },
    validarRegistrarComision(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearComision.idTesis){
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
    validarEmail() {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.validEmail = re.test(this.fillCrearComision.EmailPEx);
    },
    validarProfesorExterno(){
      if (!this.fillCrearComision.NombrePEx) {
        this.fillCrearComision.EmailPEx = '';
        this.fillCrearComision.InstitucionPEx = '';
      }
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
