<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Importar usuarios</h3>
            </div>
            <div class="card-body">
              <form role="form" id="form-import1">
                <div class="row">
                  <div class="col-md-6">
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
                            <input type="file" class="custom-file-input" id="input1" :class="{ 'is-invalid' : formatError || sizeError, 'is-valid' : hover}" @change="getFile" @mouseover="hover = true" @mouseleave="hover = false">
                            <label class="custom-file-label" for="input1">{{fillImportUsers.oArchivo ? fillImportUsers.oArchivo.name : 'Seleccionar archivo'}}</label>
                          </div>
                        </div>
                        <div class="custom-file invalid-feedback no-margin" v-show="formatError">
                          El formato del archivo no es soportado.
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                      <label class="col-md-2 offset-md-1 col-form-label">Escuela</label>
                      <div class="col-md-9">
                          <el-select filterable v-model="fillImportUsers.cEscuela"
                          placeholder="Asignar escuela"
                          clearable>
                          <el-option
                              v-for="item in listEscuelas"
                              :key="item.id"
                              :label="item.nombre"
                              :value="item.id">
                          </el-option>
                          </el-select>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="container">
                Los formatos de archivo soportados son:
                <span v-for="item in fileTypes" :key="item" v-text="item +' '"></span>
              </div>
              <div class="card card-info">
                <a class="btn btn-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Ejemplo archivo
                  </a>
                <div class="collapse" id="collapseExample">
                  <div class="card-body">
                    <div class="card-body table table-responsive">
                        <table class ="table table-bordered">
                          <thead>
                            <tr>
                              <th class="celda-gris"> </th>
                              <th class="celda-gris">A</th>
                              <th class="celda-gris">B</th>
                              <th class="celda-gris">C</th>
                              <th class="celda-gris">D</th>
                              <th class="celda-gris">E</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="celda-gris">1</td>
                              <td>email</td>
                              <td>fono</td>
                              <td>rut</td>
                              <td>nombre</td>
                              <td>apellido</td>
                            </tr>
                            <tr>
                              <td class="celda-gris">2</td>
                              <td>correo@ejemplo.com</td>
                              <td>+5697654321</td>
                              <td>11.111.111-1</td>
                              <td>Alexis Miguel</td>
                              <td>Fernandez Peña</td>
                            </tr>
                          </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <div class="row">
                <div class="col-md-4 offset-4">
                  <button class="btn btn-flat btn-info btnWidth" @click.prevent="enviar" v-loading.fullscreen.lock="fullscreenLoading"
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
      fillImportUsers:{
        oArchivo: '',
        cEscuela: ''
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
      fileTypes: ['.xlsx'],
      formatError : false,
      sizeError : false,
      fileMaxSize: 0,
      listEscuelas: [],
      hover: false
    }
  },
  computed: {

  },
  mounted(){
    EventBus.$emit('navegar', 'Importar usuarios');
    //this.getParametros();
    this.getListarEscuelas();
  },

  methods:{
    getFile(element){
      this.formatError = false
      if (!element) return;
      this.fillImportUsers.oArchivo = element.target.files[0];
      if (!this.fillImportUsers.oArchivo) return;
      const fileName = this.fillImportUsers.oArchivo.name;
      var dots = fileName.split(".")
      var fileType = "." + dots[dots.length-1];
      if (this.fileTypes.join(".").indexOf(fileType) == -1){
        this.formatError = true;
        console.log(this.fileTypes.join(".").indexOf(fileType));
      }
    },
    limpiarCriterios(){
      this.fillImportUsers.oArchivo = '';
      this.fillImportUsers.cEscuela = '';
      document.getElementById("form-import1").reset();
      this.getFile();
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    enviar(){
      if (this.validarImportUsers()){
          this.modalShow = true;
          return;
      }
        this.importUsers();
    },
    validarImportUsers(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillImportUsers.oArchivo){
          this.mensajeError.push("El archivo es un campo obligatorio")
        }
        if(!this.fillImportUsers.cEscuela){
          this.mensajeError.push("La escuela es un campo obligatorio")
        }
        if(this.formatError){
          this.mensajeError.push("Los formatos permitidos son:" +this.fileTypes);
        }

        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    importUsers(){
      this.fullscreenLoading = true;
      this.form.append('file', this.fillImportUsers.oArchivo);
      this.form.append('escuela', this.fillImportUsers.cEscuela);
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/administracion/usuario/setImportUsers'
      axios.post(url, this.form, config)
      .then(response => {
        this.fullscreenLoading = false;
        this.$router.push('/usuarios');
        Swal.fire({
          icon: 'success',
          title: 'Importado correctamente',
          text:  (response.data) ? response.data+' filas omitidas.' : '',
          showConfirmButton: true,
        })
      }).catch(response=>{
          this.fullscreenLoading = false;
          Swal.fire({
          icon: 'error',
          title: 'Error al importar',
          showConfirmButton: true,
        })
      })
    },
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url).then(response => {
          this.listEscuelas = response.data;
          this.fullscreenLoading = false;
      })
    },
    // getParametros(){
    //   var url = '/admin/parametros';
    //   axios.post(url,{'params': ['ImportUsersFormato']}).then(response => {
    //       this.fileTypes = response.data[0];
    //   })
    // }
  }// cierre methods
}
</script>

<style>
.custom-file-input ~ .custom-file-label::after {
    content: "Buscar";
}

.card-green .card-header{
  background-color: rgba(255, 255, 255, 0.781) !important;
}

.celda-gris{
  background-color: rgb(165, 165, 165);
  text-align: center;
}
</style>
