<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark font-weight-bold">Comisiones</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <template v-if="rolActivo == 'Profesor'">
                <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
                    <i class="fas fa-arrow-left"></i> Regresar
                </a>
            </template>
          </div>
        </div>
        <template v-if="rolActivo == 'Profesor'">
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Mis comisiones</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listarComisionesPaginated.length">

                  <table class ="table table-hover table-head-fixed text-nowrap ">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Profesor 1</th>
                        <th>Profesor 2</th>
                        <th>Profesor externo</th>
                        <th>Acciones </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarComisionesPaginated" :key="index">
                        <td>
                            <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                <p>{{fitUser.user.nombres+' '+fitUser.user.apellidos}}</p>
                            </div>
                        </td>
                        <td>
                            <template v-if="item.comisiones">
                                <p>{{item.comisiones.user_p1.nombres+' '+item.comisiones.user_p1.apellidos}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.comisiones">
                                <template v-if="item.comisiones.user_p2">
                                    <p>{{item.comisiones.user_p2.nombres+' '+item.comisiones.user_p2.apellidos}}</p>
                                </template>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.comisiones">
                                <p>{{item.comisiones.p_externo}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.comisiones">
                            <router-link class="btn boton btn-info" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                              <i class="fas fa-pencil-alt"></i>
                            </router-link>
                            </template>
                            <router-link class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                              <i class="fas fa-eye"></i>
                            </router-link>
                            <button :title="'Descargar documento de '+terminoTitulo" class="btn boton btn-warning" @click.prevent="descargarDocumento(item.id)" v-loading.fullscreen.lock="fullscreenLoading">
                              <i class="fas fa-file-download"></i>
                            </button>
                            <template v-if="item.comisiones">
                                <button title="Ingresar revisión" class="btn boton btn-success" @click.prevent="modalInsercionDocumento(item)">
                                <i class="fas fa-file-upload"></i>
                                </button>
                            </template>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                  <div class="callout callout-info">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>
                <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item" v-if="pageNumber > 0">
                    <a href="#" class="page-link" @click.prevent="prevPage">Ant</a>
                    </li>
                    <li class="page-item" v-for="(page, index) in pagesList" :key="index"
                    :class="[page == pageNumber ? 'active' : '']">
                    <a href="#" class=page-link @click.prevent="selectPage(page)"> {{page+1}}</a>
                    </li>
                    <li class="page-item" v-if="pageNumber < pageCount -1">
                    <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                    </li>
                </ul>
                </div>
            </div>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Comisiones donde soy partícipe</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listarComisionesPaginated2.length">
                  <table class ="table table-hover table-head-fixed text-nowrap ">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Profesor guía</th>
                        <th>Profesor 1</th>
                        <th>Profesor 2</th>
                        <th>Profesor Ext.</th>
                        <th>Acciones </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarComisionesPaginated2" :key="index">
                        <td>
                            <div v-for="(fitUser, index2) in item.fit.fit__user" :key="index2">
                                <p>{{fitUser.user.nombres+' '+fitUser.user.apellidos}}</p>
                            </div>
                        </td>
                        <td>
                            <template v-if="item.fit.user__p__guia">
                                <p>{{item.fit.user__p__guia.nombres+' '+item.fit.user__p__guia.apellidos}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.user_p1">
                                <p>{{item.user_p1.nombres+' '+item.user_p1.apellidos}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.user_p2">
                                <p>{{item.user_p2.nombres+' '+item.user_p2.apellidos}}</p>
                            </template>
                        </td>
                        <td><p>{{item.p_externo}}</p></td>
                        <td>
                            <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.fit.id}}">
                              <i class="fas fa-eye"></i>
                            </router-link>
                            <button :title="'Descargar documento de '+terminoTitulo" class="btn boton btn-warning" @click.prevent="descargarDocumento(item.fit.id)" v-loading.fullscreen.lock="fullscreenLoading">
                              <i class="fas fa-file-download"></i>
                            </button>
                            <button title="Ingresar revisión" class="btn boton btn-success" @click.prevent="modalInsercionDocumento(item.fit)">
                              <i class="fas fa-file-upload"></i>
                            </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                  <div class="callout callout-info">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item" v-if="pageNumber2 > 0">
                    <a href="#" class="page-link" @click.prevent="prevPage2">Ant</a>
                    </li>
                    <li class="page-item" v-for="(page, index) in pagesList2" :key="index"
                    :class="[page == pageNumber2 ? 'active' : '']">
                    <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
                    </li>
                    <li class="page-item" v-if="pageNumber2 < pageCount2 -1">
                    <a href="#" class="page-link" @click.prevent="nextPage2">Post</a>
                    </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        </template>
        <template v-if="rolActivo != 'Profesor'">
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Comisiones establecidas</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listarComisionesTotalesPaginated.length">
                  <table class ="table table-hover table-head-fixed text-nowrap ">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Escuela</th>
                        <th>Profesor guía</th>
                        <th>Profesor 1</th>
                        <th>Profesor 2</th>
                        <th>Profesor Ext.</th>
                        <th>Acciones </th>
                        <th>Fecha de creación</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listarComisionesTotalesPaginated" :key="index">
                        <td>
                            <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                <p>{{fitUser.user.nombres+' '+fitUser.user.apellidos}}</p>
                            </div>
                        </td>
                        <td>
                            <p>{{item.escuela.nombre}}</p>
                        </td>
                        <td>
                            <template v-if="item.user__p__guia">
                                <p>{{item.user__p__guia.nombres+' '+item.user__p__guia.apellidos}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.comisiones.user_p1">
                                <p>{{item.comisiones.user_p1.nombres+' '+item.comisiones.user_p1.apellidos}}</p>
                            </template>
                        </td>
                        <td>
                            <template v-if="item.comisiones.user_p2">
                                <p>{{item.comisiones.user_p2.nombres+' '+item.comisiones.user_p2.apellidos}}</p>
                            </template>
                        </td>
                        <td><p>{{item.comisiones.p_externo}}</p></td>
                        <td>
                            <router-link :title="'Ver '+ terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
                              <i class="fas fa-eye"></i>
                            </router-link>
                            <button :title="'Descargar documento de '+terminoTitulo" class="btn boton btn-warning" @click.prevent="descargarDocumento(item.id)" v-loading.fullscreen.lock="fullscreenLoading">
                              <i class="fas fa-file-download"></i>
                            </button>
                            <button title="Ingresar revisión" class="btn boton btn-success" @click.prevent="modalInsercionDocumento(item)">
                              <i class="fas fa-file-upload"></i>
                            </button>
                            <!-- <router-link title="Ver revisiones de comisión" class="btn boton btn-info" :to="'tesis/revisiones'">
                              <i class="fa fa-list-alt"></i>
                            </router-link> -->
                            <router-link title="Editar comisión" class="btn boton btn-danger" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                              <i class="fas fa-pencil-alt"></i>
                            </router-link>
                        </td>
                        <td>
                            <p>{{moment(item.comisiones.updated_at).format("DD-MM-YYYY")}}</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                  <div class="callout callout-info">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item" v-if="totalPageNumber > 0">
                    <a href="#" class="page-link" @click.prevent="totalPrevPage">Ant</a>
                    </li>
                    <li class="page-item" v-for="(page, index) in totalPagesList" :key="index"
                    :class="[page == totalPageNumber ? 'active' : '']">
                    <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
                    </li>
                    <li class="page-item" v-if="totalPageNumber < totalPageCount -1">
                    <a href="#" class="page-link" @click.prevent="totalNextPage">Post</a>
                    </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        </template>
      </div>
    </div>
    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ingreso de documentos de revisión</h5>
                <button class="close" @click="limpiezaInsercionDocumento"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="noPadNoMar col-md-12 form-group row">
                                <label  class="col-md-3 col-form-label">Documento de revisión</label>
                                <div class="col-md-9 container-fluid">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file-upload"></i>
                                        </span>
                                        </div>
                                        <div class="custom-file">
                                        <input type="file" class="custom-file-input"
                                            :class="{
                                                'is-invalid': tesisParams.formatError || tesisParams.sizeError,
                                                'is-valid' : hover
                                            }"
                                            @change="getFileTesis"
                                            @mouseover="hover = true"
                                            @mouseleave="hover = false"
                                        />
                                        <label class="custom-file-label" for="input1">{{
                                            tesisFile
                                            ? tesisFile.name
                                            : "Seleccionar archivo"
                                        }}</label>
                                        </div>
                                    </div>
                                    <div class="custom-file invalid-feedback no-margin" v-show="tesisParams.formatError">
                                        El formato del archivo no es soportado.
                                    </div>
                                    <div class="custom-file invalid-feedback no-margin" v-show="tesisParams.sizeError">
                                        El tamaño del archivo no puede superar los
                                        {{ tesisParams.size }} MB.
                                    </div>
                                    <div class="container">
                                        El tamaño máximo de los archivos es: {{tesisParams.size}} MB.
                                    </div>
                                    <div class="container">
                                        Los formatos de archivo soportados son:
                                    <span v-for="item in tesisParams.types" :key="item" v-text="item +' '"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Comentario</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="fitDocumentoRevision.comentario"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" @click="envioDocumentoRevision">Enviar</button>
                <button class="btn btn-secondary" @click="limpiezaInsercionDocumento">Cerrar</button>
            </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import moment from "moment";
export default {
    props: ['usuario'],
    data(){
    return{
        moment: moment,
        fitDocumentoRevision: {
            fitId: '',
            comentario: '',
            tipo: 'revision',
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
        terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
        listAlumnos:[],
        listPermisos:[],
        listMisComisiones:[],
        listComisiones:[],
        listAllComisiones:[],
        fullscreenLoading: false,
        pageNumber: 0,
        pageNumber2: 0,
        totalPageNumber:0,
        perPage: 5,
        modalShow: false,
        modalOption: 0,
        mostrarModal: {
            display: 'block',
            background: '#0000006b',
        },
        ocultarModal: {
            display: 'none',
        },
        error: 0,
        mensajeError:[],
        tesisFile: '',
        tesisParams: {
            types: [],
            size: 0,
            formatError: false,
            sizeError: false
        },
        tesisForm: new FormData(),
        rolActivo: JSON.parse(localStorage.getItem('rolActivo')),
        hover: false
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listMisComisiones.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarComisionesPaginated(){
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listMisComisiones.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listMisComisiones.length,
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
     pageCount2(){
        //obtener el numero de paginas
        let a = this.listComisiones.length,
            b = this.perPage;
      return Math.ceil(a / b);
    },
    listarComisionesPaginated2(){
        let inicio = this.pageNumber2 * this.perPage,
        fin = inicio + this.perPage;
        return this.listComisiones.slice(inicio, fin);
    },
    pagesList2(){
      let a = this.listComisiones.length,
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
    totalPageCount(){
        //obtener el numero de paginas
        let a = this.listAllComisiones.length,
            b = this.perPage;
        return Math.ceil(a / b);
    },
    listarComisionesTotalesPaginated() {
        let inicio = this.totalPageNumber * this.perPage,
        fin = inicio + this.perPage;
        return this.listAllComisiones.slice(inicio, fin);
    },
    totalPagesList(){
        let a = this.listAllComisiones.length,
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
  },
  mounted(){
    this.comisionesByRol();
    this.getParametros();
  },
  methods:{
    comisionesByRol() {
        if (this.rolActivo == 'Profesor') {
            this.getListarMisComisiones();
            this.getListarComisiones();
        } else {
            this.getListarTodasComisiones();
        }
    },
    getParametros() {
        var url = "/admin/parametros";
        axios
        .post(url, { params: ['AvancesTesisSize', 'AvancesTesisFormato'] })
        .then((response) => {
          this.tesisParams.size  = response.data[0][0];
          this.tesisParams.types   = response.data[1];
        });
    },
    getListarAlumnosByprofesor(){
      this.fullscreenLoading = true;
      var url = '/avances/getListarAlumnosByprofesor'
      axios.get(url, {
      }).then(response => {
          //this.inicializarPaginacion();
          this.listAlumnos = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarMisComisiones(){
      this.fullscreenLoading = true;
      var url = '/comisiones/getListarMisComisiones'
      axios.get(url, {

      }).then(response => {
          this.inicializarPaginacion();
          this.listMisComisiones = response.data;
        //   console.log('guia',this.listMisComisiones)
          this.fullscreenLoading = false;
      })
    },
    getListarComisiones(){
      this.fullscreenLoading = true;
      var url = '/comisiones/getListarComisiones'
      axios.get(url, {

      }).then(response => {
          this.inicializarPaginacion2();
          this.listComisiones = response.data;
        //   console.log('pertenezco',this.listComisiones)
          this.fullscreenLoading = false;
      })
    },
    getListarTodasComisiones() {
      this.fullscreenLoading = true;
      var url = '/comisiones/getListarTodasComisiones'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacionTotal();
          this.listAllComisiones = response.data;
          this.fullscreenLoading = false;
      })
    },
    limpiarBandejaUsuarios(){
      this.listComisiones = [];
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
    totalNextPage(){
      this.pageNumber2++;
    },
    totalPrevPage(){
      this.pageNumber2--;
    },
    totalSelectPage(page){
      this.pageNumber2 = page;
    },
    inicializarPaginacionTotal(){
      this.pageNumber2 = 0;
    },
    descargarDocumento (id) {
        var url = '/comisiones/pathDocumentoComision';
        this.fullscreenLoading = true;
        axios.get(url, {
            params: {
            'id' :id
            }
        }).then(response => {
            this.fullscreenLoading = false;
            location.href=response.data.path;
        });
    },
    modalInsercionDocumento(fit) {
        // console.log(fit);
        this.modalShow = !this.modalShow;
        this.fitDocumentoRevision.fitId = fit.id;
    },
    limpiezaInsercionDocumento() {
        this.fitDocumentoRevision.id = null;
        this.fitDocumentoRevision.comentario = null;
        this.tesisForm = new FormData();
        this.modalShow = !this.modalShow;
    },
    envioDocumentoRevision() {
        this.fullscreenLoading = true;
        const url = '/comisiones/setRegistrarDocumentoComision';
        this.tesisForm.append("file", this.tesisFile);
        this.tesisForm.append("id_fit", this.fitDocumentoRevision.fitId);
        this.tesisForm.append("tipo", this.fitDocumentoRevision.tipo);
        this.tesisForm.append("comentario", this.fitDocumentoRevision.comentario);
        const config = { headers: { "Content-Type": "multipart/form-data" } };
        axios.post(url, this.tesisForm, config)
        .then(response => {
            this.fullscreenLoading = false;
            Swal.fire({
                icon: "success",
                title: "Documento ingresado correctamente",
                showConfirmButton: false,
                timer: 2000,
            });
            this.limpiezaInsercionDocumento();
        }).catch((response) => {
            // console.log(response)
            this.fullscreenLoading = false;
            Swal.fire({
                icon: "error",
                title: "Error al ingresar documento",
                showConfirmButton: false,
                timer: 2000,
            });
        });
    },
    getFileTesis (element) {
      this.tesisParams.formatError = false;
      this.tesisParams.sizeError = false;
      this.tesisFile = element.target.files[0];
      if (!this.tesisFile) return;
      const fileName = this.tesisFile.name;
      const fileSize = this.tesisFile.size;
      var dots = fileName.split(".");
      var fileType = "." + dots[dots.length - 1];
      if (this.tesisParams.types.join(".").indexOf(fileType) == -1) {
        this.tesisParams.formatError = true;
      }
      if (fileSize >= this.tesisParams.size * 1000000) {
        this.tesisParams.sizeError = true;
      }
    }
  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
</style>
