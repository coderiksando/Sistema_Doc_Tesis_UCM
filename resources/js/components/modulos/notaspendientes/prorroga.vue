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
              <h3 class="card-title">Formulario de ingreso de fecha de prorroga</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-2 offset-1 col-form-label">Fecha de prorroga</label>
                    <el-date-picker
                        class="col-md-5"
                        v-model="fillEditarNotaP.fecha_prorroga"
                        type="date"
                        size="large"
                        value-format="yyyy-MM-dd"
                        placeholder="Selecionar fecha de prorroga">
                    </el-date-picker>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setIngresarProrroga" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
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
      fillEditarNotaP:{
        nIdNotaP: this.$attrs.id,
        fecha_propuesta: '',
        fecha_prorroga: ''
      },
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
      thisyear: new Date(),
      mensajeError:[]

    }
  },
  computed: {
  },
  mounted(){
      this.getListarNotasPendientes();
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarNotaP.fecha_propuesta = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarEditarNotaP(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarNotaP.fecha_propuesta){
          this.mensajeError.push("La fecha es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    validarIngresarProrroga(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarNotaP.fecha_prorroga){
          this.mensajeError.push("La fecha es un campo obligatorio")
        }
        else{
          var Itsdate = this.fillEditarNotaP.fecha_prorroga;
          this.thisyear.setHours(0,0,0,0);
          if(Date.parse(this.thisyear) > Date.parse(Itsdate)){
            this.mensajeError.push("Ingrese una fecha correcta")
          }
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setIngresarProrroga(){
      if(this.validarEditarNotaP()){
        this.modalShow = true;
        return;
      }
      if(this.validarIngresarProrroga()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/notaspendientes/setIngresarProrroga'
      axios.post(url, {
        'nIdNotaP'                  :this.fillEditarNotaP.nIdNotaP,
        'fecha_prorroga'           : this.fillEditarNotaP.fecha_prorroga

      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/notaspendientes');
        Swal.fire({
        icon: 'success',
        title: 'Prorroga ingresada Correctamente',
        showConfirmButton: false,
        timer: 1500
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
    getListarNotasPendientes(){
      this.fullscreenLoading = true;
      var url = '/notaspendientes/getListarNotasPendientes'
      axios.get(url, {
        params: {
          'nIdNotaP' : this.fillEditarNotaP.nIdNotaP,
        }
      }).then(response => {
          //this.inicializarPaginacion();
          this.fillEditarNotaP.fecha_propuesta = response.data[0].fecha_propuesta;
          this.fullscreenLoading = false;
      })
    },

  }// cierre methods
}
</script>

<style>

</style>
