
<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Formulario registro de nota de alumno</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nota final documento</label>
                    <div class="col-md-5">
                      <template>
                          <el-input-number v-bind:disabled="aprobar" v-model="fillSubirNota.Nota" size="large" v-bind:min="notaMinima" :precision="1" :step="0.1" :max="7"></el-input-number>
                      </template>
                    </div>
                    <label class="col-md-2 col-form-label">Aprobar sin nota</label>
                    <div class="col-md-2">
                      <label class="switch">
                        <input v-model="aprobar" type="checkbox" @change="interruptorNota">
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarNota" v-loading.fullscreen.lock="fullscreenLoading"
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
            <h5 class="modal-title">Sistema de documento UCM</h5>
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
      fillSubirNota:{
        IdTesis: this.$attrs.id,
        Nota: '',
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
      mensajeError:[],
      aprobar: false,
      notaMinima: 1
    }
  },
  computed: {
  },
   mounted(){
    EventBus.$emit('navegar', 'Ingresar Nota');
    this.alertaNota();
    this.getTesisById();
  },
  methods:{
    limpiarCriterios(){
      this.fillSubirNota.Nota = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarRegistrarNota(){
      this.error = 0;
      this.mensajeError = [];
        if(this.fillSubirNota.Nota == null){
          this.mensajeError.push("La nota es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarNota(){
      if(this.validarRegistrarNota()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/secretaria/setRegistrarNota'
      axios.post(url, {
        'id_tesis'        : this.fillSubirNota.IdTesis,
        'nota'            : this.fillSubirNota.Nota
      }).then(response => {
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'success',
          title: 'Nota subida correctamente',
          showConfirmButton: true,
          timer: 1500
        }).then(x=>{
          this.$router.push('/actadefensa');
        });
        }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'error',
          title: 'Error al subir nota',
          showConfirmButton: true,
          timer: 1500
        });
      })
    },
    alertaNota(){
      Swal.fire({
        icon: 'warning',
        title: 'Importante!',
        text: 'De acuerdo a la nota ingresada el sistema cambiará automáticamente el estado del documento a "aprobado" o "reprobado" según corresponda',
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
    interruptorNota(){
      if (this.aprobar) {
        this.notaMinima = 0;
        this.fillSubirNota.Nota = 0;
      }else{
        this.notaMinima = 1;
        this.fillSubirNota.Nota = 1;
      }
    },
    getTesisById() {
      this.fullscreenLoading = true;
      var url = "/alumno/getTesisById";
      axios.get(url, {
        params: {
          'id' : this.fillSubirNota.IdTesis
        }
      }).then((response) => {
          let fit = response.data;
          this.fillSubirNota.Nota = fit.nota;
          if (fit.nota == 0 && fit.estado == 'A') {
            this.aprobar = true;
            this.notaMinima = 0;
            this.fillSubirNota.Nota = 0;
          }
          this.fullscreenLoading = false;
        });
    },

  }// cierre methods
}
</script>

<style>

</style>
