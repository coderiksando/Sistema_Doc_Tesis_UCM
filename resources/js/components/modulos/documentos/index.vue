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
                    <label class="col-md-3 col-form-label">Nombres o apellidos</label>
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
                    <th>Estado</th>
                    <th>Nota</th>
                    <th>Descargar</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in listarAlumnosPaginated" :key="index">
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <p v-text="itemUser.nombres + ' ' + itemUser.apellidos"></p>
                        </div>
                    </td>
                    <td> <!-- itera mostrando la cantidad total de estudiantes -->
                        <div v-for="(itemUser, index) in item.alumnos" :key="index">
                            <p v-text="itemUser.rut"></p>
                        </div>
                    </td>
                    <td>
                        {{globFunct.mergedStates(item).resultado}}
                    </td>
                    <td>
                      <span v-text="!item.nota || item.nota == 0.0 ? 'n/a': item.nota"></span>
                    </td>
                    <td >
                      <button :disabled="!item.final" title="Documento final" class="btn boton btn-info font-weight-bold" @click.prevent="download((item.final) ? item.final.path : '')">DF</button>
                      <button :disabled="!item.constancia" title="Constancia de examen" class="btn boton btn-info font-weight-bold" @click.prevent="descargarConstancia(item)">CE</button>
                      <button :disabled="!item.acta" title="Acta de defensa" class="btn btn-info boton font-weight-bold" @click.prevent="download(item.acta)">AD</button>
                      <button :title="terminoTituloEx" class="btn btn-primary boton font-weight-bold" @click.prevent="setGenerarDocumento(item.id)">
                         {{terminoTitulo}}
                      </button>
                      <button title="Memo de revisión" class="btn btn-primary boton font-weight-bold" @click.prevent="setGenerarMemoRevision(item.id)">
                          MR
                      </button>
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
  </div>
</template>

<script>
import globVar from '../../../services/globVar';
import globFunct from '../../../services/globFunct';
export default {
  data(){
    return{
      globVar: new globVar(),
      globFunct: new globFunct(),
      fillBsqAlumno:{
        cNombre: '',
        nRut: '',
        nIdEscuela:''
      },
      fullscreenLoading: false,
      terminoTituloEx: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      listEscuelas:[],
      listAlumnos:[],
      listEstados: [
        {value: 'A', label: 'Activo'},
        {value: 'I', label: 'Inactivo'}
      ],
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      pageNumber: 0,
      perPage: 10,
      nivelAcceso: 1
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
    EventBus.$emit('navegar', 'Documentos de alumnos');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
      this.getListarAlumnos();
    },
    setGenerarDocumento(id_tesis){
      //this.fullscreenLoading = true;

      var config = {
        responseType: 'blob'
      }

      var url = '/administracion/tesis/setGenerarDocumento'
      axios.post(url, {
          'nIdTesis' : id_tesis
      }, config).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/pdf'});
          var url = URL.createObjectURL(oMyBlob);
          window.open(url);
          //this.listTesis = response.data;
          //this.fullscreenLoading = false;
      })
    },
    setGenerarMemoRevision(id_tesis){
      var config = {
        responseType: 'blob'
      }
      var url = '/secretaria/setGenerarMemoRevision'
      axios.post(url, {
          'nIdTesis' :id_tesis
      }, config).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/pdf'});
          var url = URL.createObjectURL(oMyBlob);
          window.open(url);
          //this.listTesis = response.data;
          //this.fullscreenLoading = false;
      })
    },
    limpiarCriteriosBsq(){
      this.fillBsqAlumno.cNombre = '';
      this.fillBsqAlumno.nRut = '';
      this.fillBsqAlumno.nIdEscuela = '';
    },
    limpiarBandejaUsuarios(){
      this.listAlumnos = [];
    },
    getListarAlumnos(){
      this.fullscreenLoading = true;
      if (this.listRolPermisosByUsuario.includes('fid.acceso.total')) {
        this.nivelAcceso = 0;
      }
      var url = '/secretaria/getListarAlumnos'
      axios.get(url, {
        params: {
          'cNombre'     : this.fillBsqAlumno.cNombre,
          'nRut'        : this.fillBsqAlumno.nRut,
          'nivelAcceso' : this.nivelAcceso
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
    },
    setCambiarEstadoUsuario(op, id_user){
        Swal.fire({
            title: 'Estas seguro? ' + ((op == 1) ? 'desactivar ' : 'activar ') + ' el usuario',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: ((op == 1) ? 'Si, desactivar ' : 'Si, activar ')
        }).then((result) => {
            if (result.value) {
                this.fullscreenLoading = true;
                var url = '/secretaria/setCambiarEstadoUsuario'
                axios.post(url, {
                'nIdUsuario' : id_user,
                'cEstado'    : (op == 1) ? 'I' : 'A'
                }).then(response => {
                    Swal.fire({
                    icon: 'success',
                    title: 'Se ' + ((op == 1) ? 'desactivo ' : 'activo ') +'el usuario',
                    showConfirmButton: false,
                    timer: 1500
                    })
                    this.getListarAlumnos();
                })
            }
        })
    },
    redirectTo(route, objectId, newTab) {
        if (route.search("http") !== 0) {
            if (!newTab) {
                if (objectId) this.$router.push({name: route, params: objectId});
                else this.$router.push({name: route});
            } else {
                const ruteData = this.$router.resolve({name: route, params: objectId});
                window.open(ruteData.href, '_blank');
            }
        } else window.open(route, '_blank');
    },

    descargarConstancia(fid){
      if (fid.alumnos.length == 1) {
        this.download(fid.constancia);
      }else{
        var url = '/archivo/descargaZipByFid';
        let postConfig = {
              headers: {'X-Requested-With': 'XMLHttpRequest'},
              responseType: 'blob',
          }
        axios.post(url,
        {
          nIdFid: fid.id,
          cTipo: 'constancia_t'
        },
        postConfig
        ).then(response => {
          var oMyBlob = new Blob([response.data], {type : 'application/zip'});
          var url = document.createElement('a')
          url.href = URL.createObjectURL(oMyBlob);
          url.download = 'Constancias.zip';
          url.click();
        });
      }
    },
    download(item) {
      axios({
        url: item.path,
        method: 'GET',
        responseType: 'blob'
      }).then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', item.filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
      })
    }

  }//cierre de methods
}
</script>

<style>

</style>
