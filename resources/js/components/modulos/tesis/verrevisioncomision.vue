<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark font-weight-bold">Revisiones de comisión</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="container container-fluid">
      <div class="card">
        <div class="card-header">
          <div class="card-tools">
            <a class="btn btn-info bnt-sm" href="javascript:history.go(-1)">
              <i class="fas fa-arrow-left"></i> {{globVar.btnBack}}
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="container-fluid">
            <div class="card card-info">
              <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">Bandeja de resultados</h3>
                    </div>
                </div>
              </div>
              <div class="card-body table table-responsive">
                <template v-if="listRevisiones.length">
                  <table class ="table table-hover table-head-fixed text-nowrap projects" v-loading.fullscreen.lock="fullscreenLoading">
                    <thead>
                      <tr>
                        <th>Profesor</th>
                        <th>Comentario</th>
                        <th>Archivo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in listRevisiones" :key="index">
                        <td>
                            <p v-text="item.nombre"></p>
                        </td>
                        <td>
                          <p v-text="item.comentario"></p>
                        </td>
                        <td>
                          <template v-if="item.archivo_pdf.path">
                            <a title="Descargar revisión" class="btn boton btn-info" :href="item.archivo_pdf.path" target="_blank">
                              <i class="fas fa-file-download"></i>
                            </a>
                          </template>
                          <template v-else>
                            <button disabled='true' title="No hay archivo disponible" class="btn boton btn-danger">
                            <i class="fa fa-clock-o"></i></button>
                          </template>
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
            </div>
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
      listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
      listRolByUser: JSON.parse(localStorage.getItem('rolUser')),
      fullscreenLoading: false,
      rolActivo : JSON.parse(localStorage.getItem('rolActivo')),

      listRevisiones: []
    }
  },
  mounted(){
    EventBus.$emit('navegar', 'Revisiones de la comisión');
    this.getListarRevisiones();
  },
  methods:{
    getListarRevisiones(){
      this.fullscreenLoading = true;
      var url = '/alumno/getRevisionesComision'
      axios.get(url, {
      }).then(response => {
          this.listRevisiones = response.data;
          this.fullscreenLoading = false;
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

  .mine{
    padding: 1.25rem 1.25rem 0 1.25rem !important;
  }
</style>
