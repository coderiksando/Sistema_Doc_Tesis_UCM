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

        <template v-if="rolActivo == 'Profesor'">
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Comisiones lideradas como Prof. Guía</h3>
              </div>
              <div id="accordion">
                <template v-if="listarComisionesPaginated.length">
                    <template v-for="(item, index) in listarComisionesPaginated">
                        <div class="card" :key="'guia'+index">
                            <div class="card-body" :id="'heading'+index">
                            <h3 class="mb-0">
                                <button class="btn btn-link col-md-12 noPadNoMar d-flex" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="false" :aria-controls="'collapse'+index">
                                    <div title="Sección expandible" class="col-md-1"><a class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i></a></div>
                                    <div title="Sección expandible" class="col-md-8 noPadNoMar"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + item.titulo.slice(0, 40)}}</p></div>
                                    <div class="col-md-3 noPadNoMar">
                                        <template v-if="!item.comisiones">
                                            <router-link title="Crear comisión" class="btn boton btn-info" :to="{name:'comisiones.crear', params:{id: item.id}}">
                                                <i class="fas fa-plus-circle"></i>
                                            </router-link>
                                        </template>
                                        <template v-if="item.comisiones">
                                            <router-link title="Editar comisión" class="btn boton btn-info" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </router-link>
                                        </template>
                                        <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.id}}">
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
                                    </div>
                                </button>
                            </h3>
                            </div>

                            <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordion">
                            <div class="card-footer">
                                <dl class="row">
                                    <dt class="col-md-4">Alumnos integrantes:</dt>
                                    <template v-if="item.fit__user">
                                        <dd class="col-md-8">
                                            <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                                {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                            </div>
                                        </dd>
                                    </template>
                                    <dt class="col-md-4">Título extendido:</dt>
                                    <dd class="col-md-8">{{item.titulo}}</dd>
                                    <dt class="col-md-4">Descripción:</dt>
                                    <dd class="col-md-8">{{item.descripcion}}</dd>
                                    <template v-if="item.user__p__coguia">
                                        <dt class="col-md-4">Prof. Co-guía:</dt>
                                        <dd class="col-md-8">{{item.user__p__coguia.nombres.split(' ')[0] + ' ' + item.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                    </template>
                                    <dt class="col-md-4">Comisión evaluadora:</dt>
                                    <dd class="col-md-8">
                                        <dl v-if="item.comisiones" class="row">
                                            <template v-if="item.comisiones.user_p1">
                                                <dt class="col-md-4">1° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.comisiones.user_p1.nombres.split(' ')[0]+' '+item.comisiones.user_p1.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template v-if="item.comisiones.user_p2">
                                                <dt class="col-md-4">2° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.comisiones.user_p2.nombres.split(' ')[0]+' '+item.comisiones.user_p2.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template>
                                                <dt class="col-md-4">Prof. Externo:</dt>
                                                <dd class="col-md-8">{{item.comisiones.p_externo}}</dd>
                                            </template>
                                        </dl>
                                    </dd>
                                    <dt class="col-md-4">Tipo de documento:</dt>
                                    <dd class="col-md-8">{{item.tipo}}</dd>
                                </dl>
                            </div>
                            </div>
                        </div>
                    </template>
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
                </template>
                <template v-else>
                  <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>

            </div>
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Comisiones donde soy partícipe</h3>
              </div>

              <div id="accordion2">
                <template v-if="listarComisionesPaginated2.length">
                    <template v-for="(item, index) in listarComisionesPaginated2">
                        <div class="card" :key="'participe'+index">
                            <div class="card-body" :id="'headingParticipe'+index">
                            <h3 class="mb-0">
                                <button class="btn btn-link col-md-12 noPadNoMar d-flex" data-toggle="collapse" :data-target="'#collapseParticipe'+index" aria-expanded="false" :aria-controls="'collapseParticipe'+index">
                                    <div title="Sección expandible" class="col-md-1"><a class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i></a></div>
                                    <div title="Sección expandible" class="col-md-8 noPadNoMar"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globalFunctions.capitalizeFirstLetter(item.fit.titulo.slice(0, 40))}}</p></div>
                                    <div class="col-md-3 noPadNoMar">
                                        <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary" :to="{name:'tesis.ver', params:{id: item.fit.id}}">
                                            <i class="fas fa-eye"></i>
                                        </router-link>
                                        <button :title="'Descargar documento de '+terminoTitulo" class="btn boton btn-warning" @click.prevent="descargarDocumento(item.fit.id)" v-loading.fullscreen.lock="fullscreenLoading">
                                            <i class="fas fa-file-download"></i>
                                        </button>
                                        <template v-if="item">
                                            <button title="Ingresar revisión" class="btn boton btn-success" @click.prevent="modalInsercionDocumento(item.fit)">
                                                <i class="fas fa-file-upload"></i>
                                            </button>
                                        </template>
                                    </div>
                                </button>
                            </h3>
                            </div>

                            <div :id="'collapseParticipe'+index" class="collapse" :aria-labelledby="'headingParticipe'+index" data-parent="#accordion2">
                            <div class="card-footer">
                                <dl class="row">
                                    <dt class="col-md-4">Alumnos integrantes:</dt>
                                    <template v-if="item.fit.fit__user">
                                        <dd class="col-md-8">
                                            <div v-for="(fitUser, index2) in item.fit.fit__user" :key="index2">
                                                {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                            </div>
                                        </dd>
                                    </template>
                                    <dt class="col-md-4">Título extendido:</dt>
                                    <dd class="col-md-8">{{globalFunctions.capitalizeFirstLetter(item.fit.titulo)}}</dd>
                                    <dt class="col-md-4">Descripción:</dt>
                                    <dd class="col-md-8">{{item.fit.descripcion}}</dd>
                                    <template v-if="item.fit.user__p__guia">
                                        <dt class="col-md-4">Prof. Guía:</dt>
                                        <dd class="col-md-8">{{item.fit.user__p__guia.nombres.split(' ')[0] + ' ' + item.fit.user__p__guia.apellidos.split(' ')[0]}}</dd>
                                    </template>
                                    <template v-if="item.fit.user__p__coguia">
                                        <dt class="col-md-4">Prof. Co-guía:</dt>
                                        <dd class="col-md-8">{{item.fit.user__p__coguia.nombres.split(' ')[0] + ' ' + item.fit.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                    </template>
                                    <dt class="col-md-4">Comisión evaluadora:</dt>
                                    <dd class="col-md-8">
                                        <dl v-if="item" class="row">
                                            <template v-if="item.user_p1">
                                                <dt class="col-md-4">1° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.user_p1.nombres.split(' ')[0] +' '+item.user_p1.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template v-if="item.user_p2">
                                                <dt class="col-md-4">2° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.user_p2.nombres.split(' ')[0] +' '+item.user_p2.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template>
                                                <dt class="col-md-4">Prof. Externo:</dt>
                                                <dd class="col-md-8">{{item.p_externo}}</dd>
                                            </template>
                                        </dl>
                                    </dd>
                                    <dt class="col-md-4">Tipo de documento:</dt>
                                    <dd class="col-md-8">{{item.fit.tipo}}</dd>
                                </dl>
                            </div>
                            </div>
                        </div>
                    </template>
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
                    </template>
                    <template v-else>
                    <div class="callout callout-primary">
                        <h5> No se han encontrado resultados...</h5>
                    </div>
                    </template>
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
                <h3 class="card-title">Total de comisiones establecidas</h3>
              </div>
              <div id="accordion3">
                <template v-if="listarComisionesTotalesPaginated.length">
                    <template v-for="(item, index) in listarComisionesTotalesPaginated">
                        <div class="card" :key="'total'+index">
                            <div class="card-body" :id="'headingTotal'+index">
                            <h3 class="mb-0">
                                <button class="btn btn-link col-md-12 noPadNoMar d-flex" data-toggle="collapse" :data-target="'#collapseTotal'+index" aria-expanded="false" :aria-controls="'collapseTotal'+index">
                                    <div title="Sección expandible" class="col-md-1"><a class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i></a></div>
                                    <div title="Sección expandible" class="col-md-8 noPadNoMar"><p class="float-left">{{moment(item.comisiones.updated_at).format("DD-MM-YYYY") + ', ' + globalFunctions.capitalizeFirstLetter(item.titulo.slice(0, 40))}}</p></div>
                                    <div class="col-md-3 noPadNoMar">
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
                                    </div>
                                </button>
                            </h3>
                            </div>

                            <div :id="'collapseTotal'+index" class="collapse" :aria-labelledby="'headingTotal'+index" data-parent="#accordion3">
                            <div class="card-footer">
                                <dl class="row">
                                    <dt class="col-md-4">Alumnos integrantes:</dt>
                                    <template v-if="item.fit__user">
                                        <dd class="col-md-8">
                                            <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                                {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                            </div>
                                        </dd>
                                    </template>
                                    <dt class="col-md-4">Título extendido:</dt>
                                    <dd class="col-md-8">{{globalFunctions.capitalizeFirstLetter(item.titulo)}}</dd>
                                    <dt class="col-md-4">Descripción:</dt>
                                    <dd class="col-md-8">{{item.descripcion}}</dd>
                                    <template v-if="item.user__p__guia">
                                        <dt class="col-md-4">Prof. Guía:</dt>
                                        <dd class="col-md-8">{{item.user__p__guia.nombres.split(' ')[0] + ' ' + item.user__p__guia.apellidos.split(' ')[0]}}</dd>
                                    </template>
                                    <template v-if="item.user__p__coguia">
                                        <dt class="col-md-4">Prof. Co-guía:</dt>
                                        <dd class="col-md-8">{{item.user__p__coguia.nombres.split(' ')[0] + ' ' + item.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                    </template>
                                    <dt class="col-md-4">Comisión evaluadora:</dt>
                                    <dd class="col-md-8">
                                        <dl v-if="item.comisiones" class="row">
                                            <template v-if="item.comisiones.user_p1">
                                                <dt class="col-md-4">1° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.comisiones.user_p1.nombres.split(' ')[0]+' '+item.comisiones.user_p1.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template v-if="item.user_p2">
                                                <dt class="col-md-4">2° Prof. Interno:</dt>
                                                <dd class="col-md-8">{{item.comisiones.user_p2.nombres.split(' ')[0]+' '+item.comisiones.user_p2.apellidos.split(' ')[0]}}</dd>
                                            </template>
                                            <template>
                                                <dt class="col-md-4">Prof. Externo:</dt>
                                                <dd class="col-md-8">{{item.comisiones.p_externo}}</dd>
                                            </template>
                                        </dl>
                                    </dd>
                                    <dt class="col-md-4">Tipo de documento:</dt>
                                    <dd class="col-md-8">{{item.tipo}}</dd>
                                </dl>
                            </div>
                            </div>
                        </div>
                    </template>
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
                    </template>
                    <template v-else>
                    <div class="callout callout-primary">
                        <h5> No se han encontrado resultados...</h5>
                    </div>
                    </template>
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
import globalFunctions from '../../../services/globalFunctions';
export default {
    props: ['usuario'],
    data(){
    return{
        globalFunctions: new globalFunctions(),
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
          console.log('guia',this.listMisComisiones)
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
          console.log('pertenezco',this.listComisiones)
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
