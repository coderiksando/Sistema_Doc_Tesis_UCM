
<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Formulario de registro de escuelas</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nombre</label>
                    <div class="col-md-9">
                      <input type="text" maxlength="250"  class="form-control" v-model="fillCrearEscuela.cNombre" @keyup.enter="setRegistrarEscuelas">
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group row">
                    <label class="col-md-6 col-form-label">Abreviación</label>
                    <div class="col-md-6">
                      <input type="text" maxlength="250"  class="form-control" v-model="fillCrearEscuela.cNomAbr" @keyup.enter="setRegistrarEscuelas">
                    </div>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Facultad</label>
                    <div class="col-md-9">
                      <el-select filterable v-model="fillCrearEscuela.nIdFacultad"
                      placeholder="Asignar Facultad"
                      >
                      <el-option
                          v-for="item in listFacultades"
                          :key="item.id"
                          :label="item.nombre"
                          :value="item.id">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarEscuelas" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
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
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillCrearEscuela:{
        cNombre: '',
        cNomAbr: '',
        nIdFacultad: 1
      },
      listFacultades: [],
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
  mounted(){
    EventBus.$emit('navegar', 'Crear nueva escuela');
    this.getListarFacultades();
  },
  methods:{
    limpiarCriterios(){
      this.fillCrearEscuela.cNombre = '';
      this.fillCrearEscuela.nIdFacultad = 1;
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },

    validarRegistrarEscuela(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearEscuela.cNombre){
          this.mensajeError.push("El nombre es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarEscuelas(){
      if(this.validarRegistrarEscuela()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/setRegistrarEscuelas'
      axios.post(url, {
        'cNombre'            : this.fillCrearEscuela.cNombre,
        'cNomAbr'            : this.fillCrearEscuela.cNomAbr,
        'nIdFacultad'        : this.fillCrearEscuela.nIdFacultad
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/escuelas');
        Swal.fire({
          icon: 'success',
          title: 'Escuela creada correctamente',
          showConfirmButton: false,
          timer: 2500
        })
      }).catch(response=>{
        this.fullscreenLoading = false;
          Swal.fire({
          icon: 'error',
          title: 'La escuela que intenta registrar ya existe.',
          showConfirmButton: false,
          timer: 2500
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
    getListarFacultades(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarFacultades'
      axios.get(url, {
      }).then(response => {
          this.listFacultades = response.data;
          this.fullscreenLoading = false;
      })
    },

  }// cierre methods
}
</script>

<style>

</style>
