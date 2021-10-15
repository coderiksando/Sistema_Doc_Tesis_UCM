<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
              <h3 class="card-title">Formulario de registro de notas pendientes</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-2 offset-1 col-form-label">Fecha propuesta</label>
                    <el-date-picker
                        class="col-md-5"
                        v-model="fillCrearNotaP.fecha_propuesta"
                        type="date"
                        size="large"
                        format="dd/MM/yyyy"
                        value-format="yyyy-MM-dd"
                        placeholder="Selecionar fecha de nota pendiente">
                    </el-date-picker>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarNotaP" v-loading.fullscreen.lock="fullscreenLoading">{{globVar.btnSave}}</button>
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
      fillCrearNotaP:{
        fecha_propuesta: ''
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
    EventBus.$emit('navegar', 'Ingresar nota pendiente');
    this.alertaNotaP();
  },
  methods:{
    limpiarCriterios(){
      this.fillCrearNotaP.cNombre = '';
      this.fillCrearNotaP.cSlug = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    validarRegistrarEscuela(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillCrearNotaP.fecha_propuesta){
          this.mensajeError.push("La fecha es un campo obligatorio")
        }
        else{
          var Itsdate = this.fillCrearNotaP.fecha_propuesta;
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
    setRegistrarNotaP(){
      if(this.validarRegistrarEscuela()){
        this.modalShow = true;
        return;
      }
      this.fullscreenLoading = true;
      var url = '/notaspendientes/setRegistrarNotaP'
      axios.post(url, {
        'fecha_propuesta'            : this.fillCrearNotaP.fecha_propuesta
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/notaspendientes');
        Swal.fire({
        icon: 'success',
        title: 'Nota pendiente registrada correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al registrar nota pendiente',
        showConfirmButton: false,
        timer: 1500
      })
      })
    },

    alertaNotaP(){
      Swal.fire({
        icon: 'warning',
        title: 'Importante!',
        text: 'Tenga presente que este formulario solo es para dejar constancia a nivel de facultad, por lo que aun es necesario realizar la solicitud a traves del portal del alumno.',
        confirmButtonText: 'Abrir portal del alumno',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
      }).then((response) => {
        if (response.value) {
          window.open('http://www.portalalumnos.ucm.cl/v2/');
        }
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
