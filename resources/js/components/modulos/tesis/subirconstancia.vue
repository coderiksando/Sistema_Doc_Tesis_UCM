<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Subir constancia de examen</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i> Regresar
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Constancia de examen</h3>
              </div>
              <div class="card-body">
                <form role="form" id="form-final">
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group row">
                        <label class="col-md-2 offset-md-1 col-form-label">Archivo</label>
                        <div class="col-md-8">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroupFileAddon01">
                              <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError, 'is-valid' : hover}" @change="getFile" @mouseover="hover = true" @mouseleave="hover = false">
                              <label class="custom-file-label" for="input1">{{nombreArchivo ? nombreArchivo : 'Seleccionar archivo'}}</label>
                            </div>
                          </div>
                          <div class="custom-file invalid-feedback no-margin" v-show="formatError">
                                El formato del archivo no es soportado.
                          </div>
                          <div class="custom-file invalid-feedback" v-show="sizeError">
                            El tamaño del archivo no puede superar los {{fileMaxSize}} MB.
                          </div>
                        </div>
                        <div class="col-md-1">
                          <a v-if="lastFile" class="btn btn-warning boton" title="Descargar versión anterior" target="_blank" :href="lastFile.path">
                            <i class="fas fa-file-download"> </i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <div class="container">
                  El tamaño máximo de los archivos es: {{fileMaxSize}} MB.
                </div>
                <div class="container">
                  Los formatos de archivo soportados son:
                  <span v-for="item in fileTypes" :key="item" v-text="item +' '"></span>
                </div>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button :disabled="btnDis" class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarFinalPdf" v-loading.fullscreen.lock="fullscreenLoading"
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
            <h5 class="modal-title">Sistema de documentos UCM</h5>
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
      Archivo: '',
      nombreArchivo: '',
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
      fileMaxSize: 0,
      lastFile: {},
      btnDis: true,
      hover: false
    }
  },
  computed: {

  },
  mounted(){
    this.getParametros();
    this.getDocumento();
  },

  methods:{
    getFile(element){
      this.btnDis = false;
      this.formatError = false;
      this.sizeError = false;
      if (!element) return;
      this.Archivo = element.target.files[0];
      (this.Archivo) ? this.nombreArchivo = this.Archivo.name : this.nombreArchivo = '';
      if (!this.Archivo) return;
      const fileName = this.Archivo.name;
      const fileSize = this.Archivo.size;
      var dots = fileName.split(".")
      var fileType = "." + dots[dots.length-1];
      if (this.fileTypes.join(".").indexOf(fileType) == -1){
        this.formatError = true;
      }
      if (fileSize >= this.fileMaxSize*1000000){
        this.sizeError = true;
      }
    },
    limpiarCriterios(){
      this.Archivo = '';
      this.nombreArchivo = '';
      document.getElementById("form-final").reset();
      this.getFile();
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    setRegistrarFinalPdf(){
      if (this.validarRegistrarFinalPdf()){
          this.modalShow = true;
          return;
      }
      if(!this.Archivo || this.Archivo == undefined){
        this.fullscreenLoading = true;
        this.setGuardarConstancia();
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF(){
      this.fullscreenLoading = true;
      this.form.append('file', this.Archivo);
      this.form.append('tipo', 'constancia_t');
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivoPDF';

      axios.post(url, this.form, config).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/tesis');
        Swal.fire({
        icon: 'success',
        title: 'Documento subido correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al subir documento',
        showConfirmButton: false,
        timer: 1500
      })
      })
    },
    validarRegistrarFinalPdf(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.Archivo){
          this.mensajeError.push("El archivo es un campo obligatorio")
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
    getParametros(){
      var url = '/admin/parametros';
      axios.post(url,{'params': ['AvancesTesisFormato', 'AvancesTesisSize']}).then(response => {
          this.fileTypes = response.data[0];
          this.fileMaxSize = response.data[1][0];
      })
    },
    getDocumento(){
      this.fullscreenLoading = true;
      var url = '/archivo/getPdfFinal';
      axios.get(url,{
        params: {
          'tipo'    : 'constancia_t'
        }
        }).then(response => {
          this.lastFile = response.data;
          this.nombreArchivo = this.lastFile.filename;
          this.fullscreenLoading = false;
      });
    }
  }// cierre methods
}
</script>

<style>

.no-margin{
  height: 0% !important;
}

.custom-file-label{
  white-space: nowrap;
  text-overflow: clip;
  overflow: hidden;
}

</style>
