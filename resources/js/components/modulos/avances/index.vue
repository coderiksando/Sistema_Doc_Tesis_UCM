<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Avances de tesis</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <template  v-if="listRolPermisosByUsuario.includes('avances.crear')">            
          <div class="card-header">
            <div class="card-tools">
            <template v-if="fillEstadoTesis.cEstado  == 'A'">
              <router-link class="btn btn-success btn-sm bt-fh"  :to="'/avances/subirfinalpdf'">
                <i class="fas fa-file-upload float fa-fw"></i> Subir PDF final
              </router-link>
            </template>
            <template v-if="fillEstadoTesis.cEstado  == 'D' || fillEstadoTesis.cEstado  == 'R'">
              <router-link class="btn btn-danger btn-sm link-disabled bt-fh" :to="''">
                <i class="fas fa-lock fa-fw"></i> Aun no puedes subir el PDF final
              </router-link>
            </template>
            <template v-if="fillEstadoTesis.cEstado  == 'D'">
              <router-link class="btn btn-info btn-sm bt-fh" :to="'/avances/crear'">
                <i class="fas fa-plus-square fa-fw"></i> Subir Avance
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
                <h3 class="card-title">Criterios de busqueda</h3>
              </div>              
             <!-- Filtro de busqueda de avances -->
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-9">
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label">Seleccionar estado</label>
                          <div class="col-md-9">
                              <Multiselect
                                v-model="selectedEstado"
                                placeholder="Seleccionar estado"
                                :options="listEstados"
                                label ="nombre"
                                selectLabel="Presiona enter para seleccionar"
                                selectedLabel="Seleccionado"
                                deselectLabel="No puedes remover este valor"
                                :allow-empty="false"
                                @input="getListarAlumnosByprofesor"
                                >
                              <template slot="noResult">No hay resultados</template>
                              <template slot="noOptions">Lista vacía</template>
                              </Multiselect>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-md-3 col-form-label">Seleccionar Alumno</label>
                          <div class="col-md-9">
                              <Multiselect
                                v-model="selectedAlumno"
                                placeholder="Seleccionar estudiante"
                                :options="listAlumnos"
                                label = "nombres"
                                selectLabel="Presiona enter para seleccionar"
                                selectedLabel="Seleccionado"
                                deselectLabel="No puedes remover este valor"
                                :allow-empty="false"
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
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                    <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarAvancesByAlumno" v-loading.fullscreen.lock="fullscreenLoading"
                      >Buscar</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">Limpiar</button>
                  </div>
                </div>
              </div>
            </div>
          </template>
              
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Bandeja de resultados</h3>
              </div>
              <div class="card-body table">
                <template v-if="listarAvancesPaginated.length">
                  
                  <table class ="table table-hover table-head-fixed projects t-fix">
                    <thead>
                      <tr class="row">
                        <th class="col-md-3">Fecha</th>
                        <th class="col-md-7">Descripcion</th>
                        <th class="col-md-2">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="row" v-for="(item, index) in listarAvancesPaginated" :key="index">
                        <td class="col-md-3">{{ item.created_at | moment }}</td>
                        <td class="col-md-7">
                          <textarea readonly v-model="item.descripcion" oninput='this.style.height = this.scrollHeight + "px"'></textarea>
                        </td>
                        <td class="col-md-2">
                          <a class="btn btn-warning boton" :href="item.archivo_pdf.path" target="_blank">
                            <i class="fas fa-file-download"> </i>
                          </a>
                          <template  v-if="listRolPermisosByUsuario.includes('avances.editar')">            
                            <router-link class="btn btn-info boton" :to="{name:'avances.editar', params:{id: item.id}}">
                                <i class="fas fa-pencil-alt"></i>
                            </router-link>
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
                  <div class="callout callout-info">
                    <h5> No se han encontrado resultados...</h5>
                  </div>
                </template>
              </div>
            </div>
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
      fillEstadoTesis:{
        cEstado: '',
      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listAvances:[],
      listAlumnos:[],
      listEstados: [
        {nombre: 'En desarrollo', valor: 'D'}, 
        {nombre: 'Aprobada', valor: 'A'}, 
        {nombre: 'Reprobada', valor: 'R'}],
      selectedAlumno:{},
      selectedEstado: {nombre: 'En desarrollo', valor: 'D'},
      listPermisos:[],
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
    this.getEstadoTesis()
    this.getListarAvances();
    this.getListarAlumnosByprofesor();
  },
  filters:{
    moment: function (date) {
      moment.locale('es');
      return moment(date).format('DD/MM/YYYY, h:mm a');
    }
  },
  methods:{
    limpiarCriteriosBsq(){
      this.selectedAlumno = {};
      this.selectedEstado = {};
    },
    limpiarBandejaUsuarios(){
      this.listAvances = [];
    },
    getEstadoTesis(){
        var url = '/avances/getEstadoTesis'
        axios.get(url, {
      }).then(response => {
          console.log(response.data);
          if(response.data){
            this.fillEstadoTesis.cEstado     = response.data;
          }
      })
    },
    getListarAvancesByAlumno(){
      this.fullscreenLoading = true;
      var url = '/avances/getListarAvancesByAlumno'
      axios.get(url, {
        params: {
          'id_user' : this.selectedAlumno.id_user,
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listAvances = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarAlumnosByprofesor(){
      this.fullscreenLoading = true;
      console.log(this.selectedEstado);
      this.selectedAlumno = {};
      var url = '/avances/getListarAlumnosByprofesor';
      console.log(this.selectedEstado.valor)
      axios.get(url, {
        params: {
          'estado'    : this.selectedEstado.valor
        }
        }).then(response => {
          this.inicializarPaginacion();
          this.listAlumnos = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarAvances(){
      this.fullscreenLoading = true;
      var url = '/avances/getListarAvances'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacion();
          this.listAvances = response.data;
          this.fullscreenLoading = false;
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
   .bt-fh{
    height: 28px !important;
   }
   .t-fix{
    table-layout: fixed;
    word-wrap: break-word;
  }
</style>