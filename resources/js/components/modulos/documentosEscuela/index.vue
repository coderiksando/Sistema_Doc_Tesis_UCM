<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.crear')">
          <div id="accordion">
            <div class="card-header btn d-block" data-toggle="collapse" data-target="#collapseBody" aria-expanded="false" aria-controls="collapseBody" style="color: white;" id="headingtable">
              <h3 class="card-title">Ingresar Documento</h3>
            </div>
            <div class="collapse show" id="collapseBody" aria-labelledby="headingtable" data-parent="#accordion">
              <div class="card-body">
                <form role="form" id="form-documento">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Descripción</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" v-model="fillIngresarDocumento.cDescripcion">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Escuela</label>
                        <div class="col-md-9">
                            <el-select v-model="fillIngresarDocumento.nIdEscuela"
                            placeholder="Seleccione una escuela"
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
                    <template v-if="linkType">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-md-3 col-form-label">URL</label>
                          <div class="col-md-9">
                              <input type="text" class="form-control" v-model="fillIngresarDocumento.cPath">
                          </div>
                        </div>
                      </div>
                    </template>
                    <template v-else>
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
                      </div>
                    </div>
                    </template>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Link</label>
                        <div class="col-md-9">
                            <label class="switch">
                                <input v-model="linkType" type="checkbox">
                                <span class="slider round"></span>
                            </label>
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
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="submit"  v-loading.fullscreen.lock="fullscreenLoading"
                      >{{globVar.btnSave}}</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
                  </div>
                </div>
            </div>
            </div>
          </div>
        </div>

      <div class="card card-info" v-if="listarDocumentosGeneralesPaginated.length">
        <div class="card-header flex p-1" >
            <h3 class="card-title ml-3" v-text="'Información general'"></h3>
        </div>
        <div class="card-body table table-responsive"  v-loading="tableLoading">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <th class="col-md-10">Descripción</th>
                  <th class="col-md-10">Acciones</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarDocumentosGeneralesPaginated" :key="index">
                    <td>
                      <p v-text="item.descripcion"></p>
                    </td>
                    <td >
                      <a v-if="!item.link" title="Descargar archivo" class="btn boton btn-warning" target="_blank" :href="item.path"><i class="fas fa-file-download"> </i></a>
                      <a v-if="item.link" title="Abrir link" class="btn boton btn-primary" target="_blank" :href="item.path"><i class="fa fa-link"> </i></a>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item" v-if="pageNumber2 > 0">
                    <a href="#" class="page-link" @click.prevent="prevPage2">Ant</a>
                  </li>
                  <li class="page-item" v-for="(page, index) in pagesList2" :key="index"
                    :class="[page == pageNumber2 ? 'active' : '']"
                    :style="(page < pageNumber2 - 2 || page > pageNumber2 + 2) ? 'display: none' : ''">
                    <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
                  </li>
                  <li class="page-item" v-if="pageNumber2 < pageCount2 -1">
                    <a href="#" class="page-link" @click.prevent="nextPage2">Post</a>
                  </li>
                </ul>
              </div>
          </div>
      </div>


        <div class="card card-info">
          <div class="card-header flex p-1">
            <div class="col-md-11">
              <div class="form-group row m-0">
                <h3 class="card-title" 
                  :class="(listRolPermisosByUsuario.includes('escuelas.documentos.general')) ? 'col-md-3' : 'col-md-6'"
                  v-text="(listRolPermisosByUsuario.includes('escuelas.documentos.general')) ? 'Información de escuela:' : 'Información de escuela: '+miEscuela.nombre">
                </h3>
                <div class="col-md-4" v-if="listRolPermisosByUsuario.includes('escuelas.documentos.general')">
                    <el-select v-model="escuelaDocumentos"
                    placeholder="Seleccione una escuela"
                    filterable
                    clearable
                    @change="getListarDocumentos"
                    >
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
          <div class="card-body table table-responsive"  v-loading="tableLoading">
            <template v-if="listarDocumentosPaginated.length">
              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <th class="col-md-10">Descripción</th>
                  <th class="col-md-10">Acciones</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarDocumentosPaginated" :key="index">
                    <td>
                      <p v-text="item.descripcion"></p>
                    </td>
                    <td >
                      <a v-if="!item.link" title="Descargar archivo" class="btn boton btn-warning" target="_blank" :href="item.path"><i class="fas fa-file-download"> </i></a>
                      <a v-if="item.link" title="Abrir link" class="btn boton btn-primary" target="_blank" :href="item.path"><i class="fa fa-link"> </i></a>
                      <template v-if="(listRolPermisosByUsuario.includes('escuelas.documentos.general')) || (listRolPermisosByUsuario.includes('escuelas.documentos.crear') && item.id_escuela != 0)">
                        <router-link title="Editar documento" class="btn btn-info boton" :to="{name:'escuelas.documentos.editar', params:{id: item.id}}">
                          <i class="fas fa-pencil-alt"></i>
                        </router-link>
                        <button class="btn boton btn-danger" @click.prevent="eliminarDocumento(item.id)">
                          <i title="Eliminar documento" class="fas fa-trash-alt"></i>
                        </button>
                      </template>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item" v-if="pageNumber > 0">
                    <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                  </li>
                  <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                    :class="[page == pageNumber ? 'active' : '']"
                    :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
                    <a href="#" class=page-link @click.prevent="selectPage(page)"> {{page+1}}</a>
                  </li>
                  <li class="page-item" v-if="pageNumber < pageCount -1">
                    <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                  </li>
                </ul>
              </div>
            </template>
            <template v-else>
              <div class="callout callout-info">
                <h5> No se han encontrado resultados...</h5>
              </div>
            </template>
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
      fillIngresarDocumento:{
        cDescripcion: '',
        oArchivo: '',
        nIdEscuela: '',
        cPath: ''
      },
      escuelaDocumentos: '',
      fullscreenLoading: false,
      tableLoading: false,
      listEscuelas:[],
      listDocumentos: [],
      listDocumentosGeneral: [],
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      pageNumber2: 0,
      perPage: 5,
      nombreArchivo: '',
      fileTypes: ['.pdf', '.docx'],
      formatError : false,
      sizeError : false,
      fileMaxSize: 20,
      hover: false,
      form : new FormData,
      mensajeError: [],
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      miEscuela: {
        nombre: ''
      },
      linkType: 0
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listDocumentos.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    pageCount2(){
      //obtener el numero de paginas
      let a = this.listDocumentosGeneral.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarDocumentosPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listDocumentos.slice(inicio, fin);
    },
    listarDocumentosGeneralesPaginated(){
      //
      let inicio = this.pageNumber2 * this.perPage,
        fin = inicio + this.perPage;
      return this.listDocumentosGeneral.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listDocumentos.length,
          b = this.perPage;
      let pageCount = Math.ceil(a / b);
      let count = 0,
        pagesArray = [];
      while (count < pageCount){
        pagesArray.push(count);
        count++;
      }
      return pagesArray;
    },
    pagesList2(){
      let a = this.listDocumentosGeneral.length,
          b = this.perPage;
      let pageCount = Math.ceil(a / b);
      let count = 0,
        pagesArray = [];
      while (count < pageCount){
        pagesArray.push(count);
        count++;
      }
      return pagesArray;
    }
  },
  mounted(){
    EventBus.$emit('navegar', 'Información de escuela');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarEscuelas();
      this.getEscuela();
    },
    submit(){
      if (this.linkType) {
        this.setGuardarLink();
      }else{
        this.setGuardarArchivo();
      }
    },
    getFile(element){
      this.formatError = false
      this.sizeError = false
      if (!element) return;
      this.fillIngresarDocumento.oArchivo = element.target.files[0];
      (this.fillIngresarDocumento.oArchivo) ? this.nombreArchivo = this.fillIngresarDocumento.oArchivo.name : this.nombreArchivo = '';
      if (!this.fillIngresarDocumento.oArchivo) return;
      const fileName = this.fillIngresarDocumento.oArchivo.name;
      const fileSize = this.fillIngresarDocumento.oArchivo.size;
      var dots = fileName.split(".")
      var fileType = "." + dots[dots.length-1];
      if (this.fileTypes.join(".").indexOf(fileType) == -1){
        this.formatError = true;
      }
      if (fileSize >= this.fileMaxSize*1000000){
        this.sizeError = true;
      }
    },
    limpiarCriteriosBsq(){
      this.fillIngresarDocumento.cDescripcion = '';
      this.fillIngresarDocumento.oArchivo = '';
      this.fillIngresarDocumento.cPath = '';
      //this.linkType = 0;
      this.nombreArchivo = '';
      //document.getElementById("form-documento").reset();
      if (this.listRolPermisosByUsuario.includes('escuelas.documentos.general')) {
        this.fillIngresarDocumento.nIdEscuela = 0;
      }else{
        this.fillIngresarDocumento.nIdEscuela = -1;
      }
      this.getFile();
    },
    limpiarBandejaUsuarios(){
      this.listDocumentos = [];
    },
    getListarDocumentos(){
      this.tableLoading = true;
      var url = '/administracion/escuelas/getListarDocumentosEscuela'
      axios.get(url, {
        params: {
          'nIdEscuela' : this.escuelaDocumentos
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listDocumentos = response.data;
          this.tableLoading = false;
          if (!this.listRolPermisosByUsuario.includes('escuelas.documentos.general')){
            this.listDocumentosGeneral = this.listDocumentos.filter(doc => doc.id_escuela == 0);
            this.listDocumentos = this.listDocumentos.filter(doc => doc.id_escuela != 0);
          }
          console.log(this.listDocumentos);
          console.log(this.listDocumentosGeneral);
      })
    },
    getEscuela(){
      var url = '/administracion/escuelas/getEscuela'
      axios.get(url, {}).then(response => {
          this.miEscuela = response.data;
      })
    },
    nextPage(){
      this.pageNumber++;
    },
    prevPage(){
      this.pageNumber--;
    },
    selectPage(page){
      this.pageNumber = page;
    },
    inicializarPaginacion(){
      this.pageNumber = 0;
    },
    nextPage2(){
      this.pageNumber2++;
    },
    prevPage2(){
      this.pageNumber2--;
    },
    selectPage2(page){
      this.pageNumber2 = page;
    },
    inicializarPaginacion2(){
      this.pageNumber2 = 0;
    },
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {

      }).then(response => {
          this.listEscuelas = response.data;
          this.listEscuelas.unshift({'id':0, 'nombre': 'General'});
          if (this.listRolPermisosByUsuario.includes('escuelas.documentos.general')) {
            this.fillIngresarDocumento.nIdEscuela = this.escuelaDocumentos = 0;
          }else{
            this.fillIngresarDocumento.nIdEscuela = this.escuelaDocumentos = -1;
          }
          this.fullscreenLoading = false;
          this.getListarDocumentos();
      })
    },
    setGuardarArchivo(){
      if (this.validarRegistrarDocumento()){
          this.modalShow = true;
          return;
      }
      this.fullscreenLoading = true;
      this.form.append('file', this.fillIngresarDocumento.oArchivo);
      this.form.append('descripcion', this.fillIngresarDocumento.cDescripcion);
      this.form.append('id_escuela', this.fillIngresarDocumento.nIdEscuela);
      const config = { headers: {'Content-Type': 'multipart/form-data'}}
      var url = '/archivo/setRegistrarArchivoEscuela'
      axios.post(url, this.form, config).then(response =>{
        this.fullscreenLoading = false;
        this.limpiarCriteriosBsq();
        this.getListarDocumentos();
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
    validarRegistrarDocumento(){
      this.error = 0;
      this.mensajeError = [];

        if(!this.fillIngresarDocumento.cDescripcion){
          this.mensajeError.push("La descripción es un campo obligatorio")
        }
        if(!this.fillIngresarDocumento.oArchivo && !this.linkType){
          this.mensajeError.push("El archivo es un campo obligatorio")
        }
        if(!this.fillIngresarDocumento.cPath && this.linkType){
          this.mensajeError.push("La URL es un campo obligatorio")
        }
        if(this.fillIngresarDocumento.nIdEscuela === ''){
          this.mensajeError.push("La escuela es un campo obligatorio")
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
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    eliminarDocumento(id) {
      Swal.fire({
        title: '¿Está seguro de que desea eliminar ésta información? (Esta acción es irreversible)',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText:'Si, eliminar'
      }).then((result) => {
        if (result.isConfirmed) {
          this.fullscreenLoading = true;
          var url = '/archivo/setEliminarDocumentoEscuela';
          axios.post(url, {
              'id' : id
          }).then(response =>{
            this.fullscreenLoading = false;
            this.getListarDocumentos();
            Swal.fire({
              icon: 'success',
              title: ' Eliminado correctamente',
              showConfirmButton: false,
              timer: 1500
            })
          }).catch(response=>{
            this.fullscreenLoading = false;
            Swal.fire({
              icon: 'error',
              title: 'Error al eliminar',
              showConfirmButton: false,
              timer: 1500
            })
          })
        }
      })
    },
    setGuardarLink(){
      if (this.validarRegistrarDocumento()){
          this.modalShow = true;
          return;
      }
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/setRegistrarLinkEscuela';
      axios.post(url, {
          'id_escuela'  : this.fillIngresarDocumento.nIdEscuela,
          'descripcion' : this.fillIngresarDocumento.cDescripcion,
          'path'        : this.fillIngresarDocumento.cPath
      }).then(response =>{
        this.fullscreenLoading = false;
        this.limpiarCriteriosBsq();
        this.getListarDocumentos();
        Swal.fire({
          icon: 'success',
          title: 'Link guardado correctamente',
          showConfirmButton: false,
          timer: 1500
        })
      }).catch(response=>{
        this.fullscreenLoading = false;
        Swal.fire({
          icon: 'error',
          title: 'Error al crear link',
          showConfirmButton: false,
          timer: 1500
        })
      })
    }
  }//cierre de methods
}
</script>

<style>

</style>
