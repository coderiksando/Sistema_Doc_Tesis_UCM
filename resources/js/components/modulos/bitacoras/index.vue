<template>
  <div class="card" v-loading.fullscreen.lock="fullscreenLoading">
    <template  v-if="listRolPermisosByUsuario.includes('bitacoras.crear')">
      <div class="card-header">
        <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/bitacoras/crear'">
          <i class="fas fa-plus-square"></i> Ingresar Acta de reunión
        </router-link>
        </div>
      </div>
    </template>
    <div class="card-body" style="min-height: 70vh !important">
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
                <div class="col-md-9">
                  <div class="form-group row">
                      <label class="col-md-3 col-form-label">Seleccionar alumno/s</label>
                      <div class="col-md-9">
                          <Multiselect
                            v-model="selectedFit"
                            placeholder="Seleccionar estudiante"
                            :options="listFits"
                            label = "nombres"
                            selectLabel="Seleccionar"
                            selectedLabel="Seleccionado"
                            deselectLabel="Remover"
                            @input="getListarBitacorasByFit"
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

        <div class="card card-info" v-loading="loading">
          <div class="card-header">
            <h3 class="card-title">Bandeja de resultados</h3>
          </div>
          <template v-if="listBitacoras.length">
            <div id="accordion">
              <div class="card-white" v-for="(item, index) in listBitacoras" :key="index">
                <div class="card-header" v-bind:id="'heading'+index">
                  <div class="container">
                    <a class="btn btn-outline-primary" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    </a>
                    <button class="btn btn-link" data-toggle="collapse" v-bind:data-target="'#collapse'+index" aria-expanded="false" v-bind:aria-controls="'collapse'+index">
                      {{item.fecha | moment}}
                    </button>
                    <template  v-if="listRolPermisosByUsuario.includes('bitacoras.editar')">
                      <router-link class="btn btn-info boton btn-w float-right" :to="{name:'bitacoras.editar', params:{id: item.id}}">
                          <i class="fas fa-pencil-alt"></i>
                        </router-link>
                    </template>
                  </div>
                </div>

                <div v-bind:id="'collapse'+index" class="collapse" v-bind:aria-labelledby="'heading'+index" data-parent="#accordion">
                  <div class="card-body">
                    <div v-html="item.acuerdo"></div>
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
    props: ['usuario'],
  data(){
    return{
        fillBsqBitacoraByAlumno:{
        id_user: '',
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listFits:[],
      listPermisos:[],
      listBitacoras:[],
      selectedFit:{},
      loading: false,
      fullscreenLoading: false,
      pageNumber: 0,
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
      mensajeError:[]
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listBitacoras.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarTesisPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listBitacoras.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listBitacoras.length,
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
    EventBus.$emit('navegar', 'Actas de reunión');
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
      this.getListarMisBitacoras();
      this.getListarFitsByprofesor();
    },
    getListarFitsByprofesor(){
      this.fullscreenLoading = true;
      this.selectedFit = {};
      var url = '/avances/getListarFitsByprofesor';
      axios.get(url, {
        params: {
          'estado'    : (this.$attrs.id != null) ? '' : 'D'
        }
        }).then(response => {
          this.listFits = response.data;
          if (this.$attrs.id != null) {
            this.selectedFit = this.listFits.find(fit => fit.id == this.$attrs.id);
            this.getListarBitacorasByFit();
          }
          this.fullscreenLoading = false;
      })
    },
    getListarMisBitacoras(){
      this.loading = true;
      var url = '/bitacoras/getListarMisBitacoras'
      axios.get(url, {

      }).then(response => {
          this.inicializarPaginacion();
          this.listBitacoras = response.data;
          this.loading = false;
      })
    },
    getListarBitacorasByFit(){
      if (!this.selectedFit) {
        this.limpiarBandejaUsuarios();
      }else{
        this.loading = true;
        var url = '/bitacoras/getListarBitacorasByFit'
        axios.get(url, {
          params: {
            'fit' : this.selectedFit.id,
          }
        }).then(response => {
            this.inicializarPaginacion();
            this.listBitacoras = response.data;
            this.loading = false;
        })
      }
    },
    limpiarCriteriosBsq(){
      this.selectedFit = {};
    },
    limpiarBandejaUsuarios(){
      this.listBitacoras = [];
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
    resizeTextarea(e) {
      let area = e.target;
      if(area.style.height != area.scrollHeight + 'px'){
        area.style.height = area.scrollHeight + 'px'
      }else{
        area.style.height = null;
      }
    },
  }//cierre de methods
}

</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
  textarea{
    text-overflow: ellipsis;
    width:100%;
    overflow-y:hidden;
    border: none;
    resize: none;
  }

</style>
