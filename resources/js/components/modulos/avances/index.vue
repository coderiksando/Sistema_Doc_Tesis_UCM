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
    </div>
</template>

<script>
import moment from 'moment';
import Multiselect from 'vue-multiselect';

export default {
  components: {Multiselect},
  data(){
    return{
      fillEstadoTesis:{
        cEstado: '',
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listAvances:[],
      listFits:[],
      listEstados: [
        {nombre: 'En desarrollo', valor: 'D'},
        {nombre: 'Aprobada', valor: 'A'},
        {nombre: 'Reprobada', valor: 'R'}],
      selectedFit:{},
      selectedEstado: {nombre: 'En desarrollo', valor: 'D'},
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
      id_fit : this.$attrs.id
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
            this.listAvances = response.data;
            console.log(this.listAvances)
            this.loading = false;
        })
      }
    },
    getListarFitsByprofesor(){
      this.fullscreenLoading = true;
      this.selectedFit = {};
      this.listAvances = [];
      var url = '/avances/getListarFitsByprofesor';
      axios.get(url, {
        params: {
          'estado'    : (this.id_fit) ? this.$attrs.estado : this.selectedEstado.valor
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
            }
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
