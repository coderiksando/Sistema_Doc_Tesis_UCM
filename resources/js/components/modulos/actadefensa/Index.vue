<template>
  <div class="card">
    <div class="card-body">
      <div class="container-fluid">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Criterios de búsqueda</h3>
          </div>
          <div class="card-body">
            <form role="form">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Nombres o apellido</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqAlumno.cNombre" @keyup.enter="getListarAlumnos">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Rut</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" v-model="fillBsqAlumno.nRut" @keyup.enter="getListarAlumnos">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-3 col-form-label">Estado</label>
                    <div class="col-md-9">
                        <el-select v-model="fillBsqAlumno.nEstadoAlumno"
                        placeholder="Selecciona estado"
                        clearable>
                        <el-option
                            v-for="item in listEstadosAlumno"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                        </el-select>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-md-4 offset-4">
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarAlumnos" v-loading.fullscreen.lock="fullscreenLoading"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Bandeja de resultados</h3>
          </div>
          <div class="card-body table table-responsive">
            <template v-if="listarAlumnosPaginated.length">

              <table class ="table table-hover table-head-fixed text-nowrap projects">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Rut</th>
                    <th>Estado de aprobación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarAlumnosPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <a v-text="itemUser.nombres + ' ' + itemUser.apellidos"></a>
                        </div>
                    </td>
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <a v-text="itemUser.rut"></a>
                        </div>
                    </td>
                    <td>
                      <template v-if="item.estado == 'D'">
                        <span>En desarrollo</span>
                      </template>
                      <template v-else-if="item.estado == 'A'">
                        <span>Aprobada</span>
                      </template>
                      <template v-else>
                        <span>Reprobada</span>
                      </template>
                    </td>
                    <td>
                      <router-link title="Subir nota de documento final"  class="btn btn-success boton" :to="{name:'actadefensa.subirnota', params:{id: item.id}}">
                        <i class="fas fa-graduation-cap"></i>
                      </router-link>
                      <!-- <a v-if="item.path" class="btn btn-warning boton" :href="item.path" @click.prevent="downloadItem(item.path)"><i class="fas fa-file-download"> </i></a> -->
                      <router-link :title="'Subir acta de defensa de '+terminoTitulo" class="btn boton" :class="{ 'btn-primary' : item.path, 'btn-warning' : !item.path}" :to="{name:'actadefensa.subiracta', params:{id: item.id}}">
                        <i class="fas fa-file-upload"></i>
                      </router-link>
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
import globVar from '../../../services/globVar';
export default {
  data(){
    return{
      globVar: new globVar(),
      fillBsqAlumno:{
        cNombre: '',
        nRut: '',
        nEstadoAlumno:''
      },
      fullscreenLoading: false,
      listAlumnos:[],
      listEscuelas:[],
      listEstados: [
        {value: 'A', label: 'Activo'},
        {value: 'I', label: 'Inactivo'}
      ],
      listEstadosAlumno: [
        {value: 'A', label: 'Terminada'},
        {value: 'R', label: 'Reprobada'},
        {value: 'D', label: 'En desarrollo'}
      ],
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 5
    }
  },
  computed: {
    pageCount(){
      //obtener el numero de paginas
      let a = this.listAlumnos.length,
          b = this.perPage;
      return Math.ceil(a / b);
    },
    listarAlumnosPaginated(){
      //
      let inicio = this.pageNumber * this.perPage,
        fin = inicio + this.perPage;
      return this.listAlumnos.slice(inicio, fin);
    },
    pagesList(){
      let a = this.listAlumnos.length,
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
    EventBus.$emit('navegar', 'Actas de defensa');
    },
  methods:{
    downloadItem (url) {
    axios.get(url, { responseType: 'blob' })
      .then(response => {
        const blob = new Blob([response.data], { type: 'application/pdf' });
        var link = URL.createObjectURL(blob);
        window.open(link);
      }).catch(console.error);
    },


    limpiarCriteriosBsq(){
      this.fillBsqAlumno.cNombre = '';
      this.fillBsqAlumno.nRut = '';
      this.fillBsqAlumno.nIdEscuela = '';
      this.fillBsqAlumno.nEstadoAlumno='';
    },
    limpiarBandejaUsuarios(){
      this.listAlumnos = [];
    },
    getListarAlumnos(){
      this.fullscreenLoading = true;
      var url = '/secretaria/getListarAlumnos'
      axios.get(url, {
        params: {
          'cNombre'       : this.fillBsqAlumno.cNombre,
          'nRut'          : this.fillBsqAlumno.nRut,
          'nEstadoAlumno' : this.fillBsqAlumno.nEstadoAlumno
        }
      }).then(response => {
          this.inicializarPaginacion();
          this.listAlumnos = response.data;
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
    }
  }//cierre de methods
}
</script>

<style>
.boton{
    border:0px !important;
    width: 38px !important;
    height:38px !important;
   }
</style>
