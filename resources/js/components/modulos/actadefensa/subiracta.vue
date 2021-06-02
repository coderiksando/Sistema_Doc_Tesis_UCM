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
                <form role="form" id="form-acta1">
                  <div class="row">
                    <div class="col-md-9">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupFileAddon01">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16" data-darkreader-inline-fill="" style="--darkreader-inline-fill:currentColor;">
                          <path d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707V11.5z"></path>
                          <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"></path>
                          </svg>
                          </span>
                        </div>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError}" @change="getFile" lang="es">
                          <label class="custom-file-label" for="input1">{{fillSubirActa.oArchivo ? fillSubirActa.oArchivo.name : 'Seleccionar archivo'}}</label>
                        </div>
                      </div>
                      <div class="custom-file invalid-feedback" v-show="formatError">
                        El formato del archivo no es soportado.
                      </div>
                      <div class="custom-file invalid-feedback" v-show="sizeError">
                        El tamaño del archivo no puede superar los {{fileMaxSize}} MB.
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
      mensajeError:[],
      fileTypes: [],
      formatError : false,
      sizeError : false,
      fileMaxSize: 0
    }
  },
  computed: {
    
  },
  mounted(){
    this.getParametros();
  },
  
  methods:{
    getFile(element){
      this.formatError = false
      this.sizeError = false
      this.fillSubirActa.oArchivo = element.target.files[0];
      if (!this.fillSubirActa.oArchivo) return;
      //console.log(this.fillCrearAvances.oArchivo);
      const fileName = this.fillSubirActa.oArchivo.name;
      const fileSize = this.fillSubirActa.oArchivo.size;
      var dots = fileName.split(".")
      var fileType = "." + dots[dots.length-1];
      if (this.fileTypes.join(".").indexOf(fileType) == -1){
        this.formatError = true;
        console.log(this.fileTypes.join(".").indexOf(fileType));
      }
      if (fileSize >= this.fileMaxSize*1000000){
        this.sizeError = true;
        console.log(this.sizetError);
      }
    },
    limpiarCriterios(){
      this.fillSubirActa.cDescripcion = '';
      this.fillSubirActa.oArchivo = '';
      document.getElementById("form-acta1").reset();
      this.getFile();
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

        if(this.sizeError){
          this.mensajeError.push("El archivo es demasiado pesado")
        }

        if(this.formatError){
          this.mensajeError.push("Los formatos permitidos son:" +this.fileTypes);
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
    getParametros(){
      var url = '/admin/parametros';
      axios.post(url,{'params': ['ActaFormato', 'ActaSize']}).then(response => {
          this.fileTypes = response.data[0];
          this.fileMaxSize = response.data[1];
      })
    }
  }// cierre methods
}
</script>

<style>

</style>
