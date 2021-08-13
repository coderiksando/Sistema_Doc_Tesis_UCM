<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Editar avance</h1>
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
                <h3 class="card-title">Formulario de edición de avance</h3>
              </div>
              <div class="card-body">
                <form role="form" id="form-avance2">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Descripción</label>
                        <div class="col-md-9">
                            <input type="text" maxlength="60" class="form-control" v-model="fillEditarAvance.cDescripcion" @keyup.enter="setEditarAvance">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Archivo</label>
                        <div class="col-md-9">
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="inputGroupFileAddon01">
                              <i class="fas fa-file-upload"></i>
                              </span>
                            </div>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError, 'is-valid' : hover}" @change="getFile" @mouseover="hover = true" @mouseleave="hover = false">
                              <label class="custom-file-label" for="input1">{{fillEditarAvance.oArchivo ? fillEditarAvance.oArchivo.name : 'Seleccionar archivo'}}</label>
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
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="setEditarAvance">Editar</button>
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
      fillEditarAvance:{
        nIdAvance: this.$attrs.id,
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
      fileTypes: [],
      formatError : false,
      sizeError : false,
      fileMaxSize: 0,
      hover: false
    }
  },
  mounted(){
      this.getAvance();
      this.getParametros();
  },
  methods:{
    limpiarCriterios(){
      this.fillEditarAvance.cDescripcion = '';
      this.fillEditarAvance.oArchivo = '';
      document.getElementById("form-avance2").reset();
      this.getFile();
    },
    getAvance(){
        var url = '/avances/getSeleccionarAvance'
        axios.get(url, {
        params: {
          'nIdAvance' : this.fillEditarAvance.nIdAvance
        }
      }).then(response => {
          this.fillEditarAvance.cDescripcion = response.data.descripcion;
      })
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getFile(element){
      this.formatError = false
      this.sizeError = false
      if (!element) return;
      this.fillEditarAvance.oArchivo = element.target.files[0];
      if (!this.fillEditarAvance.oArchivo) return;
      const fileName = this.fillEditarAvance.oArchivo.name;
      const fileSize = this.fillEditarAvance.oArchivo.size;
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
    setEditarAvance(){
      if (this.validarRegistrarAvance()){
          this.modalShow = true;
          return;
      }
      if(!this.fillEditarAvance.oArchivo || this.fillEditarAvance.oArchivo == undefined){
        this.fullscreenLoading = true;
        this.setGuardarAvance(0);
      } else {
        this.setRegistrarArchivoPDF();
      }
    },
    setRegistrarArchivoPDF(){
      this.fullscreenLoading = true;
      this.form.append('file', this.fillEditarAvance.oArchivo);
      this.form.append('tipo', 'avance_t');
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivoPDF'
      axios.post(url, this.form, config).then(response =>{
        console.log(response)
        var nIdFile = response.data.id;
        this.setGuardarAvance(nIdFile);
      })
    },
    setGuardarAvance(nIdFile){
      var url = '/avances/setEditarAvance'
      axios.post(url, {
        'id'             : this.fillEditarAvance.nIdAvance,
        'descripcion'    : this.fillEditarAvance.cDescripcion,
        'id_archivo'     : nIdFile,
      }).then(response => {
          Swal.fire({
          icon: 'success',
          title: 'Editado Correctamente',
          showConfirmButton: false,
          timer: 1500
        })
      })
    },
    validarRegistrarAvance(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillEditarAvance.cDescripcion){
          this.mensajeError.push("La descripcion es un campo obligatorio")
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
    }


  }// cierre methods
}
</script>

<style>

</style>
