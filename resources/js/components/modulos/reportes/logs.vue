<template>
    <div>
    <div class="container">

      <div class="card">
        <div class="card-body">
          <div class="container-fluid">
              <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Criterios de busqueda de registros</h3>
              </div>
              <div class="card-body">
                <form role="form">
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Actividad</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="fillBuscarRegistros.cActividad">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Rol</label>
                        <div class="col-md-9">
                            <el-select filterable v-model="fillBuscarRegistros.cRol"
                            placeholder="Seleccionar rol"
                            clearable>
                            <el-option
                                v-for="item in listRoles"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                            </el-select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group row">
                        <label class="col-md-3 col-form-label">Usuario</label>
                        <div class="col-md-9">
                                <el-select filterable v-model="fillBuscarRegistros.nIdUsuario"
                                placeholder="Seleccionar usuario"
                                clearable>
                                <el-option
                                    v-for="item in listUsuarios"
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
                            <label class="col-md-3 col-form-label">Fecha</label>
                            <div class="col-md-9">
                                <el-date-picker
                                    v-model="fillBuscarRegistros.dfecharango"
                                    type="daterange"
                                    range-separator="/"
                                    start-placeholder="Fecha Inicio"
                                    end-placeholder="Fecha Fin"
                                    value-format="yyyy-MM-dd">
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
                    <button class="btn btn-flat btn-primary btnWidth" @click.prevent="getListarRegistros" v-loading.fullscreen.lock="fullscreenLoading"
                      >Buscar</button>
                    <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">Limpiar</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  Bandeja de Resultados</h3>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listRegistrosPaginated.length">

                  <table class ="table table-hover table-head-fixed text-nowrap projects">
                    <thead>
                      <tr>
                        <th>Usuario</th>
                        <th>Actividad</th>
                        <th>Ip</th>
                        <th>Rol</th>
                        <th>Fecha</th>
                        <th>Objetivo</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr v-for="(item, index) in listRegistrosPaginated" :key="index">
                        <td v-text="item.nombres + ' ' + item.apellidos"></td>
                        <td v-text="item.actividad"></td>
                        <td v-text="item.ip"></td>
                        <td v-text="item.rol"></td>
                        <td v-text="item.fecha"></td>
                        <td>
                          <template v-if="item.actividad == 'Registrar avance' || item.actividad == 'Editar avance'">
                            <router-link class="btn btn-flat btn-primary btn-sm" target="_blank" :to="{name:'avances.editar', params:{id: item.target}}"><i class="fa fa-eye"></i></router-link>
                          </template>
                          <template v-if="item.actividad == 'login'">
                            <button disabled='true' class="btn btn-flat btn-primary btn-sm"><i class="fa fa-eye-slash"></i></button>
                          </template>
                          <template v-if="item.actividad == 'Registrar Bitácora' || item.actividad == 'Editar Bitácora'">
                            <router-link class="btn btn-flat btn-primary btn-sm" target="_blank" :to="{name:'bitacoras.editar', params:{id: item.target}}"><i class="fa fa-eye"></i></router-link>
                          </template>
                          <template v-if="item.actividad == 'Editar Comisión' || item.actividad == 'Registrar Comisión' ">
                            <router-link class="btn btn-flat btn-primary btn-sm" target="_blank" :to="{name:'comisiones.editar', params:{id: item.target}}"><i class="fas fa-eye"></i></router-link>
                          </template>
                          <template v-if="item.actividad == 'Subir Acta'">
                            <a class="btn btn-flat btn-primary btn-sm" target="_blank" :href="item.target"><i class="fas fa-eye"></i></a>
                          </template>
                          <template v-if="item.actividad == 'Subir nota'">
                            <router-link class="btn btn-flat btn-primary btn-sm" target="_blank" :to="{name:'tesis.ver', params:{id: item.target}}"><i class="fas fa-eye"></i></router-link>
                          </template>
                        </td>

                      </tr>
                    </tbody>
                  </table>
                </template>
                <template v-else>
                  <div class="callout callout-primary">
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
    <!-- /.container -->

    <!-- Bootstrap core JavaScript -->
    </div>
</template>

<script>
export default {
    data(){
      return{
        fillBuscarRegistros:{
          cActividad: '',
          cRol: '',
          nIdUsuario:'',
          dfecharango: ''
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),

        listRegistros:[],
        listRoles:['Alumno', 'Profesor', 'Director', 'Coordinador', 'Secretaria'],
        listUsuarios:[],
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
        let a = this.listRegistros.length,
            b = this.perPage;
        return Math.ceil(a / b);
      },
      listRegistrosPaginated(){
        //
        let inicio = this.pageNumber * this.perPage,
          fin = inicio + this.perPage;
        return this.listRegistros.slice(inicio, fin);
      },
      pagesList(){
        let a = this.listRegistros.length,
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
      this.getListarUsuarios();
    },
    methods:{
      getListarUsuarios(){
      this.fullscreenLoading = true;
      var url = '/administracion/usuario/getListarUsuarios'
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacion();
          this.listUsuarios = response.data;
          this.fullscreenLoading = false;
      })
    },
      getListarRegistros(){
        this.fullscreenLoading = true;
        var url = '/administracion/reportes/getListarLogs'
        axios.get(url, {
          params: {
            'cActividad'        : this.fillBuscarRegistros.cActividad,
            'cRol'  : this.fillBuscarRegistros.cRol,
            'nIdUsuario' : this.fillBuscarRegistros.nIdUsuario,
            'dFechaInicio': (!this.fillBuscarRegistros.dfecharango) ? '' : this.fillBuscarRegistros.dfecharango[0],
            'dFechaFin'   : (!this.fillBuscarRegistros.dfecharango) ? '' : this.fillBuscarRegistros.dfecharango[1],
          }
        }).then(response => {
            this.inicializarPaginacion();
            this.listRegistros = response.data;
            console.log(this.listRegistros);
            this.fullscreenLoading = false;
        })
      },
      limpiarCriteriosBsq(){
        this.fillBuscarRegistros.cActividad = '';
        this.fillBuscarRegistros.nIdUsuario = '';
        this.fillBuscarRegistros.cRol = '';
        this.fillBuscarRegistros.dFechaUR = '';
        this.fillBuscarRegistros.nIdUsuario = '';
        this.fillBuscarRegistros.cTipo = '';
        this.fillBuscarRegistros.cVinculacion = '';
        this.fillBuscarRegistros.cEstadoTesis = '';
        this.fillBuscarRegistros.dfecharango = '';
      },
      limpiarBandejaUsuarios(){
        this.listRegistros = [];
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
    }
}
</script>

<style>

</style>
