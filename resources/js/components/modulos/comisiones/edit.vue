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
              <h3 class="card-title">Formulario edición de comisión</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Tipo de comisión</label>
                    <div class="col-md-9">
                        <el-select v-model="fillEditarComision.estadoComision">
                            <el-option
                            v-for="item in [{nombre: 'Provisoria'},{nombre: 'Definitiva'}]"
                            :key="item.nombre"
                            :label="item.nombre"
                            :value="item.nombre">
                            </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. comisión 1 (requerido)</label>
                    <div class="col-md-9">
                      <el-select v-model="fillEditarComision.Profesor1"
                      placeholder="Asignar Profesor para comision"
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
                      <el-select v-model="fillEditarComision.Profesor2"
                      placeholder="Asignar Profesor para comision"
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
                      <input type="text" placeholder="Nombre profesor externo" class="form-control" v-model="fillEditarComision.NombrePEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Email prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Email profesor externo" class="form-control" v-model="fillEditarComision.EmailPEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Institución prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Institucion profesor externo" class="form-control" v-model="fillEditarComision.InstitucionPEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarComision" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="getComision">Restaurar</button>
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
            <button class="btn btn-secondary" @click="abrirModal">{{globVar.btnClose}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillEditarComision:{
        IdComision: this.$attrs.id,
        Profesor1: '',
        Profesor2: '',
        NombrePEx: '',
        EmailPEx: '',
        InstitucionPEx: '',
        estadoComision: ''
      },
      listAlumnos:[],
      listProfesores:[],
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
      mensajeError:[]
    }
  },
  computed: {
  },
  mounted(){
    EventBus.$emit('navegar', 'Editar comisión');
      this.getComision();
      this.getListarProfesores();
      this.getListarAlumnosByprofesor();
  },
  methods:{
    getComision(){
        this.fullscreenLoading = true;
        var url = '/comisiones/getComision'
        axios.get(url, {
        params: {
          'id' : this.fillEditarComision.IdComision
        }
      }).then(response => {
        this.fillEditarComision.Profesor1         = response.data.user_p1.id_user;
        if (response.data.user_p2)
        this.fillEditarComision.Profesor2         = response.data.user_p2.id_user;
        this.fillEditarComision.NombrePEx         = response.data.p_externo;
        this.fillEditarComision.EmailPEx          = response.data.correo_p_externo;
        this.fillEditarComision.InstitucionPEx    = response.data.institucion_p_externo;
        this.fillEditarComision.estadoComision    = response.data.estado;
        this.fullscreenLoading = false;
      })
    },
    getListarAlumnosByprofesor(){
      this.fullscreenLoading = true;
      var url = '/avances/getListarAlumnosByprofesor'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacion();
          this.listAlumnos = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarProfesores(){
      this.fullscreenLoading = true;
      var url = '/alumno/getListarProfesores'
      axios.get(url, {
      }).then(response => {
          //this.inicializarPaginacion();
          this.listProfesores = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarCriterios(){
      this.fillEditarComision.Comentario = '';
      this.fillEditarComision.Acuerdo = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarEditarComision(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarComision.Profesor1){
          this.mensajeError.push("El Profesor nº1 es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setEditarComision(){
      if(this.validarEditarComision()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/comisiones/setEditarComision'
      axios.post(url, {
        'IdComision'        : this.fillEditarComision.IdComision,
        'Profesor1'         : this.fillEditarComision.Profesor1,
        'Profesor2'         : this.fillEditarComision.Profesor2,
        'NombrePEx'         : this.fillEditarComision.NombrePEx,
        'EmailPEx'          : this.fillEditarComision.EmailPEx,
        'InstitucionPEx'    : this.fillEditarComision.InstitucionPEx,
        'estado'            : this.fillEditarComision.estadoComision
      }).then(response => {
        this.fullscreenLoading = false;
        Swal.fire({
            icon: "success",
            title: "La comisión ha sido editada correctamente.",
            showConfirmButton: false,
            timer: 3000,
        });
        this.$router.push('/comisiones');
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


  }// cierre methods
}
</script>

<style>

</style>
