<template>
  <div>

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Subir PDF final de tesis</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/avances'">
              <i class="fas fa-arrow-left"></i> Regresar
            </router-link>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">PDF final de tesis</h3>
              </div>
              <div class="card-body">
                <form role="form" id="form-final">
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group row">
                        <label class="col-md-2 offset-md-1 col-form-label">Archivo</label>
                        <div class="col-md-9">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroupFileAddon01">
                              <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError}" @change="getFile">
                              <label class="custom-file-label" for="input1">{{fillCrearFinalPdf.oArchivo ? fillCrearFinalPdf.oArchivo.name : 'Seleccionar archivo'}}</label>
                            </div>
                          </div>
                          <div class="custom-file invalid-feedback no-margin" v-show="formatError">
                                El formato del archivo no es soportado.
                          </div>
                          <div class="custom-file invalid-feedback" v-show="sizeError">
                            El tamaño del archivo no puede superar los {{fileMaxSize}} MB.
                          </div>
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
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarFinalPdf" v-loading.fullscreen.lock="fullscreenLoading"
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
      fillCrearFinalPdf:{
        cDescripcion: '',
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
    this.getDocumento();
  },
  
  methods:{
    getFile(element){
      this.formatError = false
      this.sizeError = false
      this.fillCrearFinalPdf.oArchivo = element.target.files[0];
      if (!this.fillCrearFinalPdf.oArchivo) return;
      const fileName = this.fillCrearFinalPdf.oArchivo.name;
      const fileSize = this.fillCrearFinalPdf.oArchivo.size;
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
      this.fillCrearFinalPdf.cDescripcion = '';
      this.fillCrearFinalPdf.oArchivo = '';
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
      if(!this.fillCrearFinalPdf.oArchivo || this.fillCrearFinalPdf.oArchivo == undefined){
        this.fullscreenLoading = true;
        this.setGuardarAvance();
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF(){
      this.fullscreenLoading = true;
      this.form.append('file', this.fillCrearFinalPdf.oArchivo);
      this.form.append('tipo', 'final_t');
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivoPDF';

      axios.post(url, this.form, config).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/avances');
        Swal.fire({
        icon: 'success',
        title: 'Documento final subido correctamente',
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

        
        if(!this.fillCrearFinalPdf.oArchivo){
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
    getParametros(){
      var url = '/admin/parametros';
      axios.post(url,{'params': ['AvancesTesisFormato', 'AvancesTesisSize']}).then(response => {
          this.fileTypes = response.data[0];
          this.fileMaxSize = response.data[1][0];
      })
    },
    getDocumento(){
      var url = '/archivo/getPdfFinal';
      axios.get(url,{}).then(response => {
          console.log(response.data);
          this.fillCrearFinalPdf.oArchivo = response.data;
          this.fillCrearFinalPdf.oArchivo.name = response.data.filename;
      });
    }
  }// cierre methods
}
</script>

<style>

.no-margin{
  height: 0% !important;
}

</style>
