<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
      <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Edición de Acta de reunión</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                  <div class="col-md-12">
                    <div id="app">
                        <ckeditor v-model="fillEditarBitacora.Acuerdo"></ckeditor>
                    </div>
                  </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
                <div class="col-md-4 offset-4">
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarBitacora" v-loading.fullscreen.lock="fullscreenLoading"
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
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillEditarBitacora:{
        nIdBitacora: this.$attrs.id,
        Acuerdo: ''
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
      mensajeError:[]
    }
  },
  computed: {
  },
  mounted(){
      this.getListarBitacorasByAlumno();
      EventBus.$emit('navegar', 'Editar Acta de reunión');
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarBitacora.Acuerdo = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarEditarBitacora(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarBitacora.Acuerdo){
          this.mensajeError.push("El acuerdo es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    getListarBitacorasByAlumno(){
      this.fullscreenLoading = true;
      var url = '/bitacoras/getListarBitacorasByAlumno'
      axios.get(url, {
        params: {
          'nIdBitacora' : this.fillEditarBitacora.nIdBitacora,
        }
      }).then(response => {
          //this.inicializarPaginacion();
          console.log(response.data);
          this.fillEditarBitacora.Acuerdo = response.data.acuerdo;
          this.fullscreenLoading = false;
      })
    },
    setEditarBitacora(){
      if(this.validarEditarBitacora()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/bitacoras/setEditarBitacora'
      axios.post(url, {
        'nIdBitacora'        :this.fillEditarBitacora.nIdBitacora,
        'acuerdo'            :this.fillEditarBitacora.Acuerdo
      }).then(response => {
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'success',
        title: 'Acta de reunión editada correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al editar Acta de reunión',
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
  }// cierre methods
}
</script>

<style>

</style>
