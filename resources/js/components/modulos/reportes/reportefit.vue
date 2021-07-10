<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Reportes de tesistas</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="container container-fluid">
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
                        <label class="col-md-3 col-form-label">Facultad</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.nIdFacultad" @change="getListarEscuelaByFacultad"
                            placeholder="Asignar facultad"
                            clearable>
                            <el-option
                                v-for="item in listFacultades"
                                :key="item.id"
                                :label="item.nombre"
                                :value="item.id">
                            </el-option>
                            </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Escuelas</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.nIdEscuela" @change="getListarProfesorByEscuela"
                            placeholder="Asignar Escuela"
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
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rut alumno</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="fillBsqTesisReporte.nRut">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Profesor</label>
                        <div class="col-md-9">
                                <el-select v-model="fillBsqTesisReporte.nIdProfesor"
                                placeholder="Asignar Escuela"
                                clearable>
                                <el-option
                                    v-for="item in listProfesores"
                                    :key="item.id"
                                    :label="item.fullname"
                                    :value="item.id_user">
                                </el-option>
                                </el-select>
                            </div>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nota pendiente</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.cEstadoNotap"
                            placeholder="Seleccione un estado"
                            clearable>
                              <el-option
                                v-for="item in listTipoDeNotap"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                              </el-option>
                            </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Estado de tesis</label>
                        <div class="col-md-9">
                            <el-select v-model="fillBsqTesisReporte.cEstadoTesis"
                            placeholder="Seleccione un estado"
                            clearable>
                              <el-option
                                v-for="item in listEstadosTesis"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                              </el-option>
                            </el-select>
                        </div>
                      </div>
                    </div>


                     <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Fecha de última asignatura cursada</label>
                            <div class="col-md-9">
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dfecharango[0]"
                                    type="month"
                                    placeholder="Inicio"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="pickerOptions"
                                    @change="selectStart">
                                </el-date-picker>
                                <el-date-picker
                                    v-model="fillBsqTesisReporte.dfecharango[1]"
                                    type="month"
                                    placeholder="Término"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-md-4 offset-4">
                      <!-- consulta los datos de la fecha son iguales en forma booleana  -->
                    <button :disabled="!!fillBsqTesisReporte.dfecharango[0] !== !!fillBsqTesisReporte.dfecharango[1]"
                    class="btn btn-flat btn-info btnWidth" @click.prevent="getListarTesisReporte"
                    v-loading.fullscreen.lock="fullscreenLoading">
                        Buscar
                    </button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">Limpiar</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">
                    <template v-if="listTesis.length">
                        <el-tooltip class="item" effect="dark" content="Exportar en Formato Excel" placement="right">
                          <el-button @click.prevent="setGenerarDocumento">
                          <i  class="fas fa-file-excel"></i>
                          Exportar
                          </el-button>
                        </el-tooltip>
                    </template>
                  Bandeja de resultados</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listTesis.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Alumno</th>
                        <th>Rut</th>
                        <th>Escuela</th>
                        <th>Profesor Guia</th>
                        <th>Estado Tesis</th>
                        <th>Detalles Tesis</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(fit, index1) in listTesis" :key="index1">
                        <td>
                        <template v-for="(fitUser, index2) in fit.fit__user">
                            <div :key="'nombreC' + index1 + ' ' + index2">
                                {{fitUser.user.nombres+' '+fitUser.user.apellidos}}
                            </div>
                        </template>
                        </td>
                        <td>
                        <template v-for="(fitUser, index2) in fit.fit__user">
                            <div :key="'rut' + index1 + ' ' + index2">
                                {{fitUser.user.rut}}
                            </div>
                        </template>
                        </td>
                        <td v-text="fit.user__p__guia.escuelas.nombre"></td>
                        <td>{{fit.user__p__guia.nombres+' '+fit.user__p__guia.apellidos}}</td>
                        <td>
                          <template v-if="fit.estado == 'D'">
                            <span class="badge badge-warning" >En desarrollo</span>
                          </template>
                          <template v-else-if="fit.estado == 'A'">
                            <span class="badge badge-success" >Aprobada</span>
                          </template>
                          <template v-else>
                            <span class="badge badge-danger" >Reprobada</span>
                          </template>
                        </td>
                        <td>
                          <router-link class="btn btn-flat btn-primary btn-sm" :to="{name:'tesis.ver', params:{id: fit.id}}">
                              Fit
                          </router-link>
                          <router-link v-if="fit.avances_tesis.length > 0" class="btn btn-flat btn-success btn-sm" :to="{name:'reportes.detallesavances', params:{id: fit.fit__user[0].id_user}}">
                             Avances
                          </router-link>
                          <div v-if="fit.avances_tesis.length === 0" class="btn btn-flat btn-success btn-sm disabled">Avances</div>
                          <router-link v-if="fit.bitacoras.length > 0" class="btn btn-flat btn-secondary btn-sm" :to="{name:'reportes.detallesbitacoras', params:{id: fit.fit__user[0].id_user}}">
                             Bitacoras
                          </router-link>
                          <div v-if="fit.bitacoras.length === 0" class="btn btn-flat btn-secondary btn-sm disabled">Bitacoras</div>
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
        fillBsqTesisReporte:{
          nRut: '',
          nIdEscuela:'',
          nIdFacultad: '',
          nIdProfesor:'',
          nIdTesis: '',
          cEstadoNotap: '',
          cTitulo: '',
          cNombreI1: '',
          cEstadoPg: '',
          cEstadoD: '',
          dfecharango: [],
          cEstadoTesis: ''
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
        listFechaUR: [
          {value: '1', label: 'hace 1 año'},
          {value: '2', label: 'hace 2 años'},
          {value: '3', label: 'hace 3 años'},
          {value: '4', label: 'mas de 3 años'}
        ],
        listTipoDeNotap: [
          {value: 'A', label: 'Activa'},
          {value: 'V', label: 'Vencida'}
        ],
        listTipoDeTrabajo: [
          {value: 'Tesis', label: 'Tesis'},
          {value: 'Memoria', label: 'Memoria'}
        ],
        listTipoVinculacion: [
          {value: 'Fondo Concursable', label: 'Fondo Concursable'},
          {value: 'Empresa', label: 'Empresa'},
          {value: 'Comunidad', label: 'Comunidad'}
        ],
        listEstadosTesis: [
          {value: 'A', label: 'Aprobada'},
          {value: 'R', label: 'Reprobada'},
          {value: 'D', label: 'En Desarrollo'}
        ],
        listTesis: [],
        listTesisOriginal: [],
        listFacultades: [],
        listEscuelas: [],
        listEscuelasOriginal: [],
        listProfesores: [],
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
        pickerOptions: {
          disabledDate(time) {
            return time.getTime() > Date.now();
          }
        },
        endOption: {
          disabledDate(time) {
            return time.getTime() > Date.now();
          }
        },
      }
    },
    computed: {
      pageCount(){
        //obtener el numero de paginas
        let a = this.listTesis.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      listarTesisPaginated(){
        //
        let inicio = this.pageNumber * this.perPage,
          fin = inicio + this.perPage;
        return this.listTesis.slice(inicio, fin);
      },
      pagesList(){
        let a = this.listTesis.length,
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
      this.getListarEscuelas();
      this.getListarFacultades();
      this.getListarProfesorByEscuela();
      this.selectStart();
    },
    methods:{
        getListarEscuelas(){
            this.fullscreenLoading = true;
            var url = '/administracion/escuelas/getListarEscuelas'
            axios.get(url, {
            }).then(response => {
                this.listEscuelasOriginal = response.data;
                this.listEscuelas = response.data;
                this.fullscreenLoading = false;
            })
        },
        getListarFacultades(){
            this.fullscreenLoading = true;
            var url = '/administracion/escuelas/getListarFacultades'
            axios.get(url, {
            }).then(response => {
                this.listFacultades = response.data;
                this.fullscreenLoading = false;
            })
        },
        getListarEscuelaByFacultad(){
            this.fullscreenLoading = true;
            this.listEscuelas = this.listEscuelasOriginal;
            const idFacultad = this.fillBsqTesisReporte.nIdFacultad;
            if (idFacultad !== '') {
                this.listEscuelas = this.listEscuelas.filter(function(escuela) {
                    return escuela.facultad.id === idFacultad;
                });
            }
            this.fullscreenLoading = false;
        },
        getListarProfesorByEscuela(){
            this.fullscreenLoading = true;
            var url = '/reportes/getListarProfesorByEscuela'
            axios.get(url, {
            params: {
                'nIdEscuela' : this.fillBsqTesisReporte.nIdEscuela,
            }
            }).then(response => {
                this.listProfesores = response.data;
                this.fullscreenLoading = false;
            })
        },
        setGenerarDocumento(){
            //this.fullscreenLoading = true;
            var url = '/administracion/reportes/export';
            let postConfig = {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                responseType: 'blob',
            }
            axios.post(url,
            {
                'listTesis': JSON.stringify(this.listTesisOriginal)
            }, postConfig
            ).then(response => {
            var oMyBlob = new Blob([response.data], {type : 'application/vnd.ms-excel'});
            var url = document.createElement('a')
            url.href = URL.createObjectURL(oMyBlob);
            url.download = 'usuarios.xlsx'
            url.click()
            })
        },
        getListarTesisReporte(){
            // console.log(this.fillBsqTesisReporte)
            this.fullscreenLoading = true;
            var url = '/administracion/reportes/getListarTesisReporte'
            axios.get(url, {
            params: {
                'nRut'          : this.fillBsqTesisReporte.nRut,
                'nIdFacultad'   : this.fillBsqTesisReporte.nIdFacultad,
                'nIdEscuela'    : this.fillBsqTesisReporte.nIdEscuela,
                'cEstadoNotap'  : this.fillBsqTesisReporte.cEstadoNotap,
                'nIdProfesor'   : this.fillBsqTesisReporte.nIdProfesor,
                'cEstadoTesis'  : this.fillBsqTesisReporte.cEstadoTesis,
                'dFechaInicio'  : (!this.fillBsqTesisReporte.dfecharango) ? '' : this.fillBsqTesisReporte.dfecharango[0],
                'dFechaFin'     : (!this.fillBsqTesisReporte.dfecharango) ? '' : this.fillBsqTesisReporte.dfecharango[1],
            }
            }).then(response => {
                this.inicializarPaginacion();
                this.listTesisOriginal = response.data;
                this.listTesis = response.data;
                const newListTesis = this.moveIndex(this.listTesis);
                this.listTesis = newListTesis;
                this.fullscreenLoading = false;
            })
        },
        limpiarCriteriosBsq(){
            this.fillBsqTesisReporte.nRut = '';
            this.fillBsqTesisReporte.nIdEscuela = '';
            this.fillBsqTesisReporte.cEstadoNotap = '';
            this.fillBsqTesisReporte.nIdProfesor = '';

            this.fillBsqTesisReporte.cEstadoTesis = '';
            this.fillBsqTesisReporte.dfecharango = '';
        },
        limpiarBandejaUsuarios(){
            this.listTesis = [];
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
        limpiarModal(){
            this.fillVerFIT.cNombre = ''
            this.fillVerFIT.cSlug = ''
            this.listPermisos = [];
            this.modalOption = 0;
        },
        selectStart() {
            this.fillBsqTesisReporte.dfecharango[1] = null;
            this.endOption = {
                disabledDate: (time) => {
                    return time.getTime() < Date.parse(this.fillBsqTesisReporte.dfecharango[0]) || time.getTime() > Date.now();
                }
            };
        },
        moveIndex( listOfElements ) {
            let i=0;
            let newList = [];
            if (Array.isArray(listOfElements)) {
                listOfElements.forEach(element => {
                    newList[i]=element;
                    i++;
                });
            } else {
                const array = Object.keys(listOfElements);
                const key = array[0];
                newList[0] = listOfElements[key];
            }
            return newList;
        }

    }//cierre de methods
  }
</script>

<style>
.scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
}
.el-range-editor.el-input__inner{
    width: 100% !important;
}
.el-date-editor .el-range-separator{
    width: 7% !important;
}
.disabled{
    cursor: not-allowed;
    opacity: .65;
}
</style>
