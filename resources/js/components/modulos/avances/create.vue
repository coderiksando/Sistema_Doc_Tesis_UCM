<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Formulario de registro de avance</h3>
          </div>
          <div class="card-body">
            <form role="form" id="form-avance1">
              <div class="row">
                <!-- <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Descripción</label>
                    <div class="col-md-9">
                        <input type="text" maxlength="60" class="form-control" v-model="fillCrearAvances.cDescripcion" @keyup.enter="setRegistrarAvance">
                    </div>
                  </div>
                </div> -->
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-2 offset-1 col-form-label">Descripción</label>
                    <div class="col-md-8">
                        <textarea maxlength="255" class="form-control text-justify" rows="3" v-model="fillCrearAvances.cDescripcion"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-2 offset-1 col-form-label">Archivo</label>
                    <div class="col-md-8">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroupFileAddon01">
                          <i class="fas fa-file-upload"></i>
                          </span>
                        </div>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError, 'is-valid' : hover}" @change="getFile" @mouseover="hover = true" @mouseleave="hover = false">
                          <label class="custom-file-label" for="input1">{{fillCrearAvances.oArchivo ? fillCrearAvances.oArchivo.name : 'Seleccionar archivo'}}</label>
                        </div>
                      </div>
                      <div class="custom-file invalid-feedback no-margin" v-show="formatError">
                        El formato del archivo no es soportado.
                      </div>
                      <div class="custom-file invalid-feedback no-margin" v-show="sizeError">
                        El tamaño del archivo no puede superar los {{fileMaxSize}} MB.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            <div class="container offset-1">
              El tamaño máximo de los archivos es: {{fileMaxSize}} MB.
            </div>
            <div class="container offset-1">
              Los formatos de archivo soportados son:
              <span v-for="item in fileTypes" :key="item" v-text="item +' '"></span>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setRegistrarAvance" v-loading.fullscreen.lock="fullscreenLoading"
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
      fillCrearAvances:{
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
      fileMaxSize: 0,
      hover: false
    }
  },
  computed: {

  },
  mounted(){
    EventBus.$emit('navegar', 'Crear Avance de documento');
    this.getParametros();
  },

  methods:{
    getFile(element){
      this.formatError = false
      this.sizeError = false
      if (!element) return;
      this.fillCrearAvances.oArchivo = element.target.files[0];
      if (!this.fillCrearAvances.oArchivo) return;
      const fileName = this.fillCrearAvances.oArchivo.name;
      const fileSize = this.fillCrearAvances.oArchivo.size;
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
      this.fillCrearAvances.cDescripcion = '';
      this.fillCrearAvances.oArchivo = '';
      document.getElementById("form-avance1").reset();
      this.getFile();
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    setRegistrarAvance(){
      if (this.validarRegistrarAvance()){
          this.modalShow = true;
          return;
      }
      if(!this.fillCrearAvances.oArchivo || this.fillCrearAvances.oArchivo == undefined){
        this.fullscreenLoading = true;
        this.setGuardarAvance();
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF(){
      this.fullscreenLoading = true;
      this.form.append('file', this.fillCrearAvances.oArchivo);
      this.form.append('tipo', 'avance_t');
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivoPDF'
      axios.post(url, this.form, config).then(response =>{
        console.log(response)
        var nIdFile = response.data.id;
        this.setGuardarAvance(nIdFile);
      })
    },
    validarRegistrarAvance(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillCrearAvances.cDescripcion){
          this.mensajeError.push("La descripcion es un campo obligatorio")
        }
        if(!this.fillCrearAvances.oArchivo){
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
    setGuardarAvance(nIdFile){
      var url = '/avances/setRegistrarAvance'
      axios.post(url, {
        'descripcion'     : this.fillCrearAvances.cDescripcion,
        'id_archivo'      : nIdFile
      }).then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/avances');
        Swal.fire({
        icon: 'success',
        title: 'Avance subido correctamente',
        showConfirmButton: false,
        timer: 1500
      })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
        icon: 'error',
        title: 'Error al subir avance',
        showConfirmButton: false,
        timer: 1500
      })
      })

    },
    getParametros(){
      var url = '/admin/parametros';
      axios.post(url,{'params': ['AvancesTesisFormato', 'AvancesTesisSize']}).then(response => {
          this.fileTypes = response.data[0];
          this.fileMaxSize = response.data[1][0];
      })
    }
  }// cierre methods
}
</script>

<style>
.custom-file-input ~ .custom-file-label::after {
    content: "Buscar";
}
</style>
