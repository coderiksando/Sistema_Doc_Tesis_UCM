<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Acta de defensa de tesis</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/actadefensa'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Subir acta de defensa</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-7">
                      <div class="form-group row">
                        <label class="col-md-6 col-form-label">Acta de defensa firmada</label>
                        <div class="col-md-6">
                            <input type="file" accept="application/pdf" class="form-control" @change="getFile">
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setSubirActa" v-loading.fullscreen.lock="fullscreenLoading"
                      >Registrar</button>
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
  </div>
</template>

<script>
export default {
  data(){
    return{
      fillSubirActa:{
        IdTesis: this.$attrs.id,
        oArchivo: '',
        
      },
      form : new FormData,
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

  },
  
  methods:{
    getFile(e){
      this.fillSubirActa.oArchivo = e.target.files[0];
    },
    limpiarCriterios(){
      this.fillSubirActa.cDescripcion = '';
      this.fillSubirActa.oArchivo = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    setSubirActa(){
      if (this.validarRegistrarActaDefensa()){
          this.modalShow = true;
          return;
      }
      if(!this.fillSubirActa.oArchivo || this.fillSubirActa.oArchivo == undefined){
        this.fullscreenLoading = true;
        this.setRegistrarActa();
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF(){
      this.form.append('file', this.fillSubirActa.oArchivo)
      this.form.append('id_tesis', this.fillSubirActa.IdTesis)
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/secretaria/setSubirActa'
      axios.post(url, this.form, config).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/actadefensa');
        Swal.fire({
        icon: 'success',
        title: 'Acta subida correctamente',
        showConfirmButton: true
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al subir acta',
        showConfirmButton: true
      })
      })
    },
    validarRegistrarActaDefensa(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillSubirActa.oArchivo){
          this.mensajeError.push("el archivo es un campo obligatorio")
        }
      
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    setRegistrarActa(){
      var url = '/secretaria/setSubirActa'
      axios.post(url, {
        'id_tesis'     : this.fillSubirActa.IdTesis,
      })
    },
  }// cierre methods
}
</script>

<style>

</style>
