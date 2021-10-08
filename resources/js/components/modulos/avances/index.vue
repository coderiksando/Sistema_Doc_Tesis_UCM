<template>
    <div class="card" v-loading.fullscreen.lock = fullscreenLoading>
      <template  v-if="listRolPermisosByUsuario.includes('avances.crear')">
        <div class="card-header">
          <div class="card-tools">
          <template v-if="fillEstadoTesis.cEstado  == 'A'">
            <router-link class="btn btn-success btn-sm bt-fh"  :to="'/avances/subirfinalpdf'">
              <i class="fas fa-file-upload float fa-fw"></i>Subir PDF final
            </router-link>
          </template>
          <template v-if="fillEstadoTesis.cEstado  == 'D' || fillEstadoTesis.cEstado  == 'R'">
            <router-link class="btn btn-danger btn-sm link-disabled bt-fh" :to="''">
              <i class="fas fa-lock fa-fw"></i>Aún no puedes subir el PDF final
            </router-link>
          </template>
          <template v-if="fillEstadoTesis.cEstado  == 'D'">
            <router-link class="btn btn-info btn-sm bt-fh" :to="'/avances/crear'">
              <i class="fas fa-plus-square fa-fw"></i> Subir avance
            </router-link>
          </template>

          </div>
        </div>
      </template>
      <div class="card-body">
        <div class="container-fluid">
          <template  v-if="listRolPermisosByUsuario.includes('avances.listaralumnos')">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Criterios de búsqueda</h3>
            </div>
            <!-- Filtro de busqueda de avances -->
            <div class="card-body">
              <form role="form">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Estado</label>
                        <div class="col-md-9">
                            <Multiselect
                              v-model="selectedEstado"
                              placeholder="Seleccionar estado"
                              :options="listEstados"
                              label ="nombre"
                              selectLabel="Presiona enter para seleccionar"
                              selectedLabel="Seleccionado"
                              :allow-empty="false"
                              deselectLabel="Cancelar"
                              @input="getListarFitsByprofesor"
                              >
                            <template slot="noResult">No hay resultados</template>
                            <template slot="noOptions">Lista vacía</template>
                            </Multiselect>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Alumno</label>
                        <div class="col-md-9">
                            <Multiselect
                              v-model="selectedFit"
                              placeholder="Seleccionar estudiante"
                              :options="listFits"
                              label = "nombres"
                              selectLabel="Seleccionar"
                              selectedLabel="Seleccionado"
                              deselectLabel="Remover"
                              @input="getListarAvancesByFit"
                              >
                            <template slot="noResult">No hay resultados</template>
                            <template slot="noOptions">Lista vacía</template>
                            </Multiselect>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-6" v-if="listRolPermisosByUsuario.includes('fid.acceso.total')">
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Escuela</label>
                        <div class="col-md-9">
                            <Multiselect
                              v-model="selectedEscuela"
                              placeholder="Seleccionar escuela"
                              :options="listEscuelas"
                              label ="nombre"
                              selectLabel="Presiona enter para seleccionar"
                              selectedLabel="Seleccionado"
                              :allow-empty="false"
                              deselectLabel="Cancelar"
                              @input="getListarFitsByprofesor"
                              >
                            <template slot="noResult">No hay resultados</template>
                            <template slot="noOptions">Lista vacía</template>
                            </Multiselect>
                        </div>
                    </div>
                  </div>
                </div>
              </form>
            </div> <!-- Filtro de busqueda de avances -->
          </div>
        </template>

          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Bandeja de resultados</h3>
            </div>
            <div class="card-body" v-loading="loading">
              <template v-if="listarAvancesPaginated.length">
                <div id="accordion">
                  <div class="card-white" v-for="(item, index) in listarAvancesPaginated" :key="index">
                    <div class="card-header" v-bind:id="'heading'+index">
                      <div class="container">
                        <a class="btn btn-outline-primary" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        </a>
                        <button class="btn btn-link" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                          {{item.created_at | moment}}
                        </button>
                        <template v-if="listRolPermisosByUsuario.includes('EsProfesor') && selectedEstado.valor == 'D'">
                            <button v-if="item.archivo_pdf.tipo_pdf == 'avance_t'" title="Enviar a revisión el documento" class="btn btn-success boton float-right mx-1 btn-w" @click.prevent="setAvanceARevision(item, true)">
                                <i class="fas fa-file-export"></i>
                            </button>
                            <button v-if="item.archivo_pdf.tipo_pdf == 'revision'" title="Cancelar la revisión del documento" class="btn btn-danger boton float-right mx-1 btn-w" @click.prevent="setAvanceARevision(item, false)">
                                <i class="fas fa-file-import"></i>
                            </button>
                        </template>
                        <a title="Ver documento" class="btn btn-warning boton float-right mx-1 btn-w" :href="item.archivo_pdf.path" target="_blank">
                            <i class="fas fa-file-download"> </i>
                        </a>
                        <template  v-if="listRolPermisosByUsuario.includes('avances.editar')">
                            <router-link title="Editar avance" class="btn btn-info boton float-right mx-1 btn-w" :to="{name:'avances.editar', params:{id: item.id}}">
                                <i class="fas fa-pencil-alt"></i>
                            </router-link>
                        </template>
                      </div>
                    </div>

                    <div v-bind:id="'collapse'+index" class="collapse" v-bind:aria-labelledby="'heading'+index" data-parent="#accordion">
                      <div class="card-body">
                        <div v-text="item.descripcion" style="white-space: pre-wrap"></div>
                      </div>
                    </div>
                  </div>
                </div>
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
                  :class="[page == pageNumber ? 'active' : '']"
                  :style="(page < pageNumber - 2 || page > pageNumber + 2) ? 'display: none' : ''">
                  <a href="#" class=page-link @click.prevent="selectPage(page)"> {{page+1}}</a>
                </li>
                <li class="page-item" v-if="pageNumber < pageCount -1">
                  <a href="#" class="page-link" @click.prevent="nextPage">Post</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    <template v-if="mostrarModalComision">
      <div class="swal2-container swal2-center swal2-backdrop-show" style="overflow-y: auto;" @mousedown.prevent="mostrarModalComision=false">
        <div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-icon-warning swal2-show" tabindex="-1" role="dialog" aria-live="assertive" aria-modal="true" style="display: flex; z-index: 2; width: 70% !important; min-width: 360px !important" v-on:mousedown.stop>
          <div class="swal2-header">
            <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Sistema de documentos UCM</h5>
                    <button class="close" @click="modalShow = !modalShow"></button>
                  </div>
                  <div class="modal-body">
                    <div class="callout callout-danger" style="padding: 5px" v-for="(item, index) in mensajeError" :key="index" v-text="item"></div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-secondary" @click="modalShow = !modalShow">{{globVar.btnClose}}</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;">
              <div class="swal2-icon-content">!</div>
            </div>
            <h2 class="swal2-title" id="swal2-title" style="display: flex;">Para ingresar un documento a revisión debe ingresar su comisión definitiva</h2>
            <button type="button" class="swal2-close" aria-label="Close this dialog" style="display: none;">×</button>
          </div>
          <div class="swal2-content text-justify">
            <form role="form">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. comisión 1 (requerido)</label>
                    <div class="col-md-9">
                      <el-select v-model="fillEditarComision.Profesor1"
                      placeholder="Asignar profesor para comisión"
                      filterable
                      clearable>
                      <el-option
                          v-for="item in listProfesores"
                          :key="item.id_user"
                          :label="item.fullname"
                          :value="item.id_user">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. comisión 2</label>
                    <div class="col-md-9">
                      <el-select v-model="fillEditarComision.Profesor2"
                      placeholder="Asignar profesor para comisión"
                      filterable
                      clearable>
                      <el-option
                          v-for="item in listProfesores"
                          :key="item.id_user"
                          :label="item.fullname"
                          :value="item.id_user">
                      </el-option>
                      </el-select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Nombre profesor externo" class="form-control" v-model="fillEditarComision.NombrePEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Email prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Email profesor externo" class="form-control" v-model="fillEditarComision.EmailPEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Institución prof. externo</label>
                    <div class="col-md-9">
                      <input type="text" placeholder="Institución profesor externo" class="form-control" v-model="fillEditarComision.InstitucionPEx" @keyup.enter="setEditarComision">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="swal2-actions">
            <button type="button" class="swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); --darkreader-inline-bgcolor:#2166a7; --darkreader-inline-border-left:#1d5a93; --darkreader-inline-border-right:#1d5a93;"  @click.prevent="setEditarComision(datosMomentaneos[0],datosMomentaneos[1])">Si, Aprobar</button>
            <button type="button" class="swal2-cancel swal2-styled" style="display: inline-block; background-color: rgb(221, 51, 51); --darkreader-inline-bgcolor:#a61c1c;" @click.prevent="mostrarModalComision=false">Cancelar</button>
          </div>
        </div>
      </div>
    </template>

    </div>
</template>

<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect';
import globVar from '../../../services/globVar';

export default {
  components: {Multiselect},
  data(){
    return{
      globVar: new globVar(),
      fillEstadoTesis:{
        cEstado: '',
        nIdEscuela: ''
      },
      fillEditarComision:{
        Profesor1       : '',
        Profesor2       : '',
        NombrePEx       : '',
        EmailPEx        : '',
        InstitucionPEx  : ''
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listAvances:[],
      comision: {},
      listFits:[],
      listEstados: [
        {nombre: 'En desarrollo', valor: 'D'},
        {nombre: 'Aprobada', valor: 'A'},
        {nombre: 'Reprobada', valor: 'R'}],
      listProfesores: [],
      selectedFit:{},
      selectedEstado: {nombre: 'En desarrollo', valor: 'D'},
      selectedEscuela: {nombre: 'Todas', id: 0},
      listPermisos:[],
      loading: false,
      fullscreenLoading: false,
      pageNumber: 0,
      perPage: 10,
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
      mostrarModalComision: false,
      datosMomentaneos: [],
      id_fit : this.$attrs.id,
      listEscuelas: [],
      nivelAcceso: 3
    }
  },
  computed: {

    pageCount(){
      //obtener el numero de paginas
      let a = this.listAvances.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarAvancesPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listAvances.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listAvances.length,
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
    EventBus.$emit('navegar', 'Avances de documento');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  filters:{
    moment: function (date) {
      moment.locale('es');
      return moment(date).format('DD/MM/YYYY, h:mm a');
    }
  },
  methods:{
    init(){
      this.getEstadoTesis();
      this.getListarAvances();
      this.getListarFitsByprofesor();
      this.getListarProfesores();
      this.getListarEscuelas();
    },
    limpiarCriteriosBsq(){
      this.selectedFit = {};
      this.selectedEstado = {};
    },
    limpiarBandejaUsuarios(){
      this.listAvances = [];
    },
    getEstadoTesis(){
        var url = '/avances/getEstadoTesis'
        axios.get(url, {
      }).then(response => {
          if(response.data){
            this.fillEstadoTesis.cEstado = response.data;
          }
      })
    },
    getListarAvancesByFit(){
      if (!this.selectedFit) {
          this.limpiarBandejaUsuarios();
      }else{
        this.loading = true;
        var url = '/avances/getListarAvancesByFit'
        axios.get(url, {
          params: {
            'Fit' : this.selectedFit.id,
          }
        }).then(response => {
            this.inicializarPaginacion();
            this.listAvances = response.data[0];
            this.comision = response.data[1];
            this.fillEditarComision.IdComision       = this.comision.id;
            this.fillEditarComision.Profesor1        = this.comision.id_profesor1;
            this.fillEditarComision.Profesor2        = this.comision.id_profesor2;
            this.fillEditarComision.NombrePEx        = this.comision.p_externo;
            this.fillEditarComision.EmailPEx         = this.comision.correo_p_externo;
            this.fillEditarComision.InstitucionPEx   = this.comision.institucion_p_externo;
            this.loading = false;
        })
      }
    },
    getListarFitsByprofesor(){
      this.fullscreenLoading = true;
      this.selectedFit = {};
      this.listAvances = [];
      if (this.listRolPermisosByUsuario.includes('fid.acceso.parcial')) this.nivelAcceso = 2;
      if (this.listRolPermisosByUsuario.includes('fid.acceso.total')) this.nivelAcceso = 1;
      var url = '/avances/getListarFitsByprofesor';
      axios.get(url, {
        params: {
          'estado'    : (this.id_fit) ? this.$attrs.estado : this.selectedEstado.valor,
          'escuela'   : this.selectedEscuela.id,
          'nivel'     : this.nivelAcceso
        }
        }).then(response => {
          this.listFits = response.data;
          if (this.id_fit) {
            this.selectedFit = this.listFits.find(fit => fit.id == this.$attrs.id);
            this.selectedEstado = this.listEstados.find(estado => estado.valor == this.selectedFit.estado);
            this.id_fit = '';
            this.getListarAvancesByFit();
          }
          this.fullscreenLoading = false;
      })
    },
    getListarAvances(){
        this.loading = true;
        var url = '/avances/getListarAvances';
        axios.get(url, {
        }).then(response => {
            this.inicializarPaginacion();
            this.listAvances = response.data;
            this.loading = false;
        })
    },
    setAvanceARevision(avance, cambio){
        let titulo = '';
        if (cambio) titulo = 'Estás seguro que quieres enviar a revisión?';
        else titulo = 'Desea retirar el documento de revisión?';
        Swal.fire({
            title: titulo,
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                if (this.comision) {
                    if (this.comision.estado == 'Provisoria') {
                        this.mostrarModalComision = true;
                        this.datosMomentaneos = [avance, cambio];
                    }
                    else this.enviarARevision(avance, cambio);
                }
                else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No existe una comisión.',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    this.$router.push('/comisiones/crear/' + this.listAvances[0].id_tesis);
                }
            }
        });
    },
    getListarProfesores(){
        var url = '/alumno/getListarProfesores';
        axios.get(url, {
            params: {
                'allTeacher' : true
            }
        }).then(response => {
            this.listProfesores = response.data;
        })
    },
    setEditarComision(avance, cambio){
        if(this.validarEditarComision()){
            this.modalShow = true;
            return;
        }
        this.fullscreenLoading = true;
        var url = '/comisiones/setEditarComision'
        axios.post(url, {
            'IdComision'        : this.fillEditarComision.IdComision,
            'Profesor1'         : this.fillEditarComision.Profesor1,
            'Profesor2'         : this.fillEditarComision.Profesor2,
            'NombrePEx'         : this.fillEditarComision.NombrePEx,
            'EmailPEx'          : this.fillEditarComision.EmailPEx,
            'InstitucionPEx'    : this.fillEditarComision.InstitucionPEx,
            'estado'            : 'Definitiva'
        }).then(response => {
            this.enviarARevision(avance, cambio);
            this.fullscreenLoading = false;
        })
    },
    validarEditarComision(){
      this.error = 0;
      this.mensajeError = [];
        if(!this.fillEditarComision.Profesor1){
          this.mensajeError.push("El Profesor nº1 es un campo obligatorio")
        }
        if(this.mensajeError.length){
          this.error = 1;
        }
        return this.error;
    },
    enviarARevision (avance, cambio) {
        const url = '/avances/setAvanceARevision';
        axios.post(url, {
            'idTesis'   : avance.id_tesis,
            'idArchivo' : avance.id_archivo,
            'change'    : cambio
        }).then(response => {
            let tituloRespuesta = '';
            if (cambio) tituloRespuesta = 'Su avance ha sido enviado a revisión';
            else tituloRespuesta = 'Su documento se ha retirado de revisión';
            Swal.fire({
                icon: 'success',
                title: tituloRespuesta,
                showConfirmButton: false,
                timer: 2500
            });
            this.getListarAvances();
        });
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
    abrirModal(){
      this.modalShow = !this.modalShow;
      this.limpiarModal();
    },
    getListarEscuelas(){
      this.fullscreenLoading = true;
      var url = '/administracion/escuelas/getListarEscuelas'
      axios.get(url, {
      }).then(response => {
        this.listEscuelas = response.data;
        this.listEscuelas.unshift({'id':'', 'nombre': 'Todas', 'facultad': {'id':0}});
      })
    },
  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }

  .boton{
    border:0px !important;
    width: 38px !important;
    height:38px !important;
   }
   .btn-w{
     color: white !important;
   }
   .bt-fh{
    height: 28px !important;
   }
   .t-fix{
    table-layout: fixed;
    word-wrap: break-word;
  }

  .card-white .card-header{
    background-color: white !important;
    display: flex;
  }
.fa-plus-circle{
  top: 50vh !important;
}
</style>
