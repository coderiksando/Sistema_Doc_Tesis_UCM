<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Subir acta de defensa</h3>
          </div>
          <div class="card-body">
            <form role="form" id="form-acta1">
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
                      <a v-if="lastFile" class="btn btn-primary boton" title="Descargar versión anterior" target="_blank" :href="lastFile.path">
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
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setSubirActa" v-loading.fullscreen.lock="fullscreenLoading"
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
      fillSubirActa:{
        IdTesis: this.$attrs.id,
        oArchivo: '',

      },
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
      hover: false,
      lastFile: ''
    }
  },
  computed: {

  },
  mounted(){
    EventBus.$emit('navegar', 'Subir Acta de defensa');
    this.getParametros();
    this.getDocumento();
  },

  methods:{
    getFile(element){
      this.formatError = false
      this.sizeError = false
      if (!element) return;
      this.fillSubirActa.oArchivo = element.target.files[0];
      (this.fillSubirActa.oArchivo) ? this.nombreArchivo = this.fillSubirActa.oArchivo.name : this.nombreArchivo = '';
      if (!this.fillSubirActa.oArchivo) return;
      const fileName = this.fillSubirActa.oArchivo.name;
      const fileSize = this.fillSubirActa.oArchivo.size;
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
      this.fillSubirActa.cDescripcion = '';
      this.fillSubirActa.oArchivo = '';
      this.nombreArchivo = '';
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
      this.fullscreenLoading = true;
      if(!this.fillSubirActa.oArchivo || this.fillSubirActa.oArchivo == undefined){
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
        Swal.fire({
        icon: 'success',
        title: 'Acta subida correctamente',
        showConfirmButton: true,
        timer: 1500
      }).then(x=>{
        this.$router.push('/actadefensa');
      });
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al subir acta',
        showConfirmButton: true,
        timer: 1500
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
          this.fileMaxSize = response.data[1][0];
      })
    },
    getDocumento(){
      this.fullscreenLoading = true;
      var url = '/archivo/getArchivo';
      axios.get(url,{
        params: {
          'tipo'    : 'acta',
          'fit'     : this.fillSubirActa.IdTesis
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

</style>
