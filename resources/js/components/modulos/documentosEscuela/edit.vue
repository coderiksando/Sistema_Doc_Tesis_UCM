<template>
  <div class="card" v-loading.fullscreen.lock="fullscreenLoading">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Formulario de edición de Información de Escuela</h3>
          </div>
          <div class="card-body">
            <form role="form" id="form-info">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-2 offset-1 col-form-label">Nombre</label>
                    <div class="col-md-8">
                        <textarea maxlength="255" class="form-control text-justify" rows="3" v-model="fillEditarInfo.cDescripcion"></textarea>
                    </div>
                  </div>
                  <template v-if="!linkType">
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
                            <label class="custom-file-label" for="input1">{{fillEditarInfo.oArchivo ? fillEditarInfo.oArchivo.name : 'Seleccionar archivo'}}</label>
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
                  </template>
                  <template v-else>
                    <div class="form-group row">
                      <label class="col-md-2 offset-1 col-form-label">URL</label>
                      <div class="col-md-8">
                          <input type="text" class="form-control" v-model="fillEditarInfo.cPath">
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </form>
            <template v-if="!linkType">
              <div class="container offset-1">
                El tamaño máximo de los archivos es: {{fileMaxSize}} MB.
              </div>
              <div class="container offset-1">
                Los formatos de archivo soportados son:
                <span v-for="item in fileTypes" :key="item" v-text="item +' '"></span>
              </div>
            </template>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarInfo">{{globVar.btnSave}}</button>
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
      fillEditarInfo:{
        nIdDoc: this.$attrs.id,
        cDescripcion: '',
        oArchivo: ''
      },
      form: new FormData,
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
      formatError : false,
      sizeError : false,
      hover: false,
      fileTypes: ['.pdf', '.docx'],
      fileMaxSize: 20,
      linkType: 1,
      fullscreenLoading: false,
    }
  },
  mounted(){
      EventBus.$emit('navegar', 'Editar Información de Escuela');
      this.getDocumento();
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarInfo.cDescripcion = '';
      this.fillEditarInfo.oArchivo = '';
      this.fillEditarInfo.cPath = '';
      document.getElementById("form-info").reset();
      this.getFile();
    },
    getDocumento(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getDocumentosEscuela'
      axios.get(url, {
      params: {
        'nIdDoc' : this.fillEditarInfo.nIdDoc
      }
      }).then(response => {
          this.fillEditarInfo.cDescripcion = response.data.descripcion;
          this.fillEditarInfo.cPath = response.data.path;
          this.linkType = response.data.link;
          this.fullscreenLoading = false;
      })
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getFile(element){
      this.formatError = false
      this.sizeError = false
      if (!element) return;
      this.fillEditarInfo.oArchivo = element.target.files[0];
      if (!this.fillEditarInfo.oArchivo) return;
      const fileName = this.fillEditarInfo.oArchivo.name;
      const fileSize = this.fillEditarInfo.oArchivo.size;
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
    setEditarInfo(){
      if (this.validarRegistrarInfo()){
          this.modalShow = true;
          return;
      }
      if (this.linkType) {
        this.setGuardarLink();
      }else{
        this.setGuardarInfo();
      }
    },
    setGuardarLink(){
      var url = '/administracion/escuelas/setEditarLinkEscuela';
      axios.post(url,{
        'id'          : this.fillEditarInfo.nIdDoc,
        'descripcion' : this.fillEditarInfo.cDescripcion,
        'path'        : this.fillEditarInfo.cPath
      }).then(response => {
          Swal.fire({
          icon: 'success',
          title: 'Editado Correctamente',
          showConfirmButton: false,
          timer: 1500
        }).then(x => {
          this.$router.push('/documentosEscuela');
        });
      })
    },
    setGuardarInfo(){
      var url = '/archivo/setEditarArchivoEscuela';
      this.form.append('file', this.fillEditarInfo.oArchivo);
      this.form.append('id', this.fillEditarInfo.nIdDoc);
      this.form.append('descripcion', this.fillEditarInfo.cDescripcion);
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      axios.post(url, this.form, config).then(response => {
          Swal.fire({
          icon: 'success',
          title: 'Editado Correctamente',
          showConfirmButton: false,
          timer: 1500
        }).then(x => {
          this.$router.push('/documentosEscuela');
        });
      })
    },
    validarRegistrarInfo(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillEditarInfo.cDescripcion){
          this.mensajeError.push("La descripción es un campo obligatorio")
        }
        if(this.sizeError){
          this.mensajeError.push("El archivo es demasiado pesado")
        }
        if (this.linkType && !this.fillEditarInfo.cPath) {
          this.mensajeError.push("La URL es un campo obligatorio")
        }

        if(this.formatError){
          this.mensajeError.push("Los formatos permitidos son:" +this.fileTypes);
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },

  }// cierre methods
}
</script>

<style>

</style>
