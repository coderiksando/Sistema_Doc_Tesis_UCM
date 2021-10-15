
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
                        <h3 class="card-title">Formulario de registro de comisión</h3>
                    </div>
                        <div class="card-body">
                            <form role="form">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Título de documento</label>
                                            <div class="col-md-9">
                                                <el-select v-model="fillCrearComision.idTesis"
                                                placeholder="Asignar título de documento"
                                                disabled>
                                                <el-option
                                                    v-for="item in listTesis"
                                                    :key="item.id"
                                                    :label="item.titulo"
                                                    :value="item.id">
                                                </el-option>
                                                </el-select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Tipo de comisión</label>
                                            <div class="col-md-9">
                                                <el-select v-model="fillCrearComision.estadoComision">
                                                    <el-option
                                                    v-for="item in [{nombre: 'Provisoria'},{nombre: 'Definitiva'}]"
                                                    :key="item.nombre"
                                                    :label="item.nombre"
                                                    :value="item.nombre">
                                                    </el-option>
                                                </el-select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Prof. comisión 1 (requerido)</label>
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
                                                <input type="text" placeholder="Nombre profesor externo" class="form-control" v-model="fillCrearComision.NombrePEx" @keyup.enter="setRegistrarComision">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Email prof. externo</label>
                                            <div class="col-md-9">
                                                <input type="text" placeholder="Email profesor externo" class="form-control" v-model="fillCrearComision.EmailPEx" @keyup.enter="setRegistrarComision">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">Institución prof. externo</label>
                                            <div class="col-md-9">
                                                <input type="text" placeholder="Institución profesor externo" class="form-control" v-model="fillCrearComision.InstitucionPEx" @keyup.enter="setRegistrarComision">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-4 offset-4">
                                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarComision" v-loading.fullscreen.lock="fullscreenLoading"
                                >{{globVar.btnSave}}</button>
                                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriterios">{{globVar.btnClear}}</button>
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
import globFunct from '../../../services/globFunct';
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillCrearComision:{
        idTesis: '',
        Profesor1: '',
        Profesor2: '',
        NombrePEx: '',
        EmailPEx: '',
        InstitucionPEx: '',
        estadoComision: 'Provisoria'
      },
      listTesis: [],
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
      mensajeError:[],
      booleanFunctions: {
        getListarProfesores: false,
        getListarTesis: false
      }
    }
  },
  computed: {
  },
  mounted(){
    EventBus.$emit('navegar', 'Crear nueva comisión');
    this.fullscreenLoading = true;
    this.getListarProfesores();
    this.getListarTesis();
  },
  methods:{
    getListarTesis () {
        const url = '/alumno/getTesisById';
        axios.get(url, {
            params: {
                'id' : this.$route.params.id
            }
        }).then (response => {
          this.listTesis = [response.data];
          if (response.data.id_p_co_guia) this.fillCrearComision.Profesor1 = response.data.id_p_co_guia;
          this.fillCrearComision.idTesis = response.data.id;
          this.booleanFunctions.getListarTesis = true;
          if (this.globFunct.booleanElements(this.booleanFunctions)) this.fullscreenLoading = false;
        })
    },
    getListarProfesores(){
        var url = '/alumno/getListarProfesores';
        axios.get(url, {
            params: {
                'allTeacher' : true
            }
        }).then(response => {
            this.listProfesores = response.data;
            this.booleanFunctions.getListarProfesores = true;
            if (this.globFunct.booleanElements(this.booleanFunctions)) this.fullscreenLoading = false;
        })
    },
    limpiarCriterios(){
        this.fillCrearComision.Profesor1 = '';
        this.fillCrearComision.Profesor2 = '';
        this.fillCrearComision.NombrePEx = '';
        this.fillCrearComision.EmailPEx = '';
        this.fillCrearComision.InstitucionPEx = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
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
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarComision(){
      if(this.validarRegistrarComision()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/comisiones/setRegistrarComision';
      axios.post(url, {
        'idTesis'           : this.fillCrearComision.idTesis,
        'Profesor1'         : this.fillCrearComision.Profesor1,
        'Profesor2'         : this.fillCrearComision.Profesor2,
        'NombrePEx'         : this.fillCrearComision.NombrePEx,
        'EmailPEx'          : this.fillCrearComision.EmailPEx,
        'InstitucionPEx'    : this.fillCrearComision.InstitucionPEx,
        'estado'            : this.fillCrearComision.estadoComision
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/comisiones');
        Swal.fire({
            icon: 'success',
            title: 'Comision creada correctamente',
            showConfirmButton: false,
            timer: 3000
        })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Comisión ya existe',
        showConfirmButton: false,
        timer: 3000
      })
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
