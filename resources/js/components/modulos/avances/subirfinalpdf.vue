<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">PDF final de documento</h3>
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
                          <label class="custom-file-label" for="input1">{{fileName}}</label>
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
                      <a v-if="lastFile" class="btn btn-warning boton" :download="lastFile.filename" title="Descargar versión anterior" target="_blank" :href="lastFile.path">
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
            <div class="text-center my-4">
              <b class="text-justify">Autorización de Publicación de Versión Electrónica de Tesis.</b>
            </div>
            <form role="form" id="form-autorizacion">
              <div class="row">
                <div class="col-md-10 offset-md-1 mb-2">
                  <div class="form-check">
                    <input v-model="formOption" class="form-check-input" name="autorizacion" type="radio" value='0' id="autorizacion1">
                    <label class="form-check-label text-justify" for="autorizacion1">
                      Autorizo al Sistema de Gestión y Administración de Documentos de la Universidad Católica del Maule a publicar la versión
                      electrónica de esta tesis en su Repositorio Académico, para ser consultada por la comunidad
                      UCM.
                    </label>
                  </div>
                </div>
                <div class="col-md-10 offset-md-1 mb-2">
                  <div class="form-check">
                    <input v-model="formOption" class="form-check-input" name="autorizacion" type="radio" value='1' id="autorizacion2">
                    <label class="form-check-label text-justify" for="autorizacion2">
                      Autorizo al Sistema de Gestión y Administración de Documentos de la Universidad Católica del Maule a publicar la versión
                      electrónica de esta tesis en su Repositorio Académico, para ser consultada por la comunidad
                      UCM, previo embargo de <input v-model="fillCrearFinalPdf.nMeses" :disabled="formOption != 1" type="number" class="text-center" style="width: 55px"> meses.
                    </label>
                  </div>
                </div>
                <div class="col-md-10 offset-md-1 mb-2">
                  <div class="form-check">
                    <input v-model="formOption" class="form-check-input" name="autorizacion" type="radio" value='2' id="autorizacion3">
                    <label class="form-check-label" for="autorizacion3">
                      No autorizo. (Justifique)
                    </label>
                  </div>
                </div>
                <div v-if="formOption == 2" class="col-md-10 offset-md-1 mb-2">
                  <div class="form-group row">
                    <label class="col-md-12 col-form-label">Motivo</label>
                    <div class="col-md-12">
                        <textarea v-model="fillCrearFinalPdf.cMotivo" maxlength="250" rows="3" type="textarea"  class="form-control text-justify"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarFinalPdf" v-loading.fullscreen.lock="fullscreenLoading"
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
      fillCrearFinalPdf:{
        oArchivo: '',
        nMeses: '',
        cMotivo: ''
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
      fileMaxSize: 0,
      lastFile: {},
      hover: false,
      edicion: false,
      formOption : 0,
      fileName: 'Seleccionar archivo'
    }
  },
  computed: {

  },
  mounted(){
    EventBus.$emit('navegar', 'Subir Documento final');
    this.getParametros();
    this.getDocumento();
  },

  methods:{
    getFile(element){
      this.formatError = false;
      this.sizeError = false;
      if (!element) return;
      this.fillCrearFinalPdf.oArchivo = element.target.files[0];
      if (!this.fillCrearFinalPdf.oArchivo) return;
      const fileName = this.fillCrearFinalPdf.oArchivo.name;
      this.fileName = this.fillCrearFinalPdf.oArchivo.name;
      const fileSize = this.fillCrearFinalPdf.oArchivo.size;
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
      this.fillCrearFinalPdf.oArchivo = '';
      this.fileName = 'Seleccionar archivo'
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
      this.setRegistrarArchivoPDF();
    },
    setRegistrarArchivoPDF(){
      this.fullscreenLoading = true;
      this.form.append('file', this.fillCrearFinalPdf.oArchivo);
      this.form.append('tipo', 'final_t');
      this.form.append('formOption', this.formOption);
      this.form.append('meses', this.fillCrearFinalPdf.nMeses);
      this.form.append('motivo', this.fillCrearFinalPdf.cMotivo);
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

        if(!this.fillCrearFinalPdf.oArchivo && !this.edicion){
          this.mensajeError.push("El archivo es un campo obligatorio")
        }

        if(this.sizeError){
          this.mensajeError.push("El archivo es demasiado pesado")
        }

        if(this.formatError){
          this.mensajeError.push("Los formatos permitidos son:" +this.fileTypes);
        }
        if(this.formOption == 1 && !this.fillCrearFinalPdf.nMeses){
          this.mensajeError.push("La cantidad de meses es un campo obligatorio");
        }
        if(this.formOption == 2 && !this.fillCrearFinalPdf.cMotivo){
          this.mensajeError.push("El motivo es un campo obligatorio");
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
      var url = '/archivo/getArchivo';
      axios.get(url,{
        params: {
          'tipo'    : 'final_t'
        }
        }).then(response => {
          this.lastFile = response.data;
          if (this.lastFile) {
            this.edicion = true;
            this.fileName = this.lastFile.filename;
          }
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
.input-focus{
  border-color: #80bdff !important;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}
</style>
