<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Comisiones</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <router-link class="btn btn-info bnt-sm" :to="'/comisiones/crear'">
              <i class="fas fa-plus-square"></i> Registrar nueva comisión
            </router-link>
          </div>
        </div>
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
                        <th>profesor 1</th>
                        <th>profesor 2</th>
                        <th>profesor externo</th>
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
                            <router-link class="btn btn-flat btn-info btn-sm" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                              <i class="fas fa-pencil-alt"></i>
                            </router-link>
                            </template>
                            <router-link class="btn btn-flat btn-primary btn-sm" :to="{name:'tesis.ver', params:{id: item.id}}">
                              <i class="fas fa-eye"></i>
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
                            <template v-if="item.fit.id_p_guia">
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
                            <router-link class="btn btn-flat btn-primary btn-sm" :to="{name:'tesis.ver', params:{id: item.id}}">
                              <i class="fas fa-eye"></i>
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
      </div>
    </div>

</div>

</template>

<script>
export default {
    props: ['usuario'],
  data(){
    return{
        fillBsqBitacoraByAlumno:{
        id_user: '',

      },
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listAlumnos:[],
      listPermisos:[],
      listMisComisiones:[],
      listComisiones:[],
      fullscreenLoading: false,
      pageNumber: 0,
      pageNumber2: 0,
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
    }
  },
  mounted(){
    this.getListarMisComisiones();
    this.getListarComisiones();
    // this.getListarAlumnosByprofesor();
  },
  methods:{
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
          this.fullscreenLoading = false;
      })
    },

    limpiarCriteriosBsq(){
      this.fillBsqBitacoraByAlumno.id_user = '';
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
</style>
