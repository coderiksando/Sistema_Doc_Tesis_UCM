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
                    <label class="col-md-4 col-form-label">Alumno</label>
                    <div class="col-md-4">
                        <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqFID.nombre" @keyup.enter="getListarNotasPendientes">
                    </div>
                    <div class="col-md-4">
                        <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqFID.apellido" @keyup.enter="getListarNotasPendientes">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Rut</label>
                    <div class="col-md-8">
                        <input placeholder="11111111-1" type="text" class="form-control" v-model="fillBsqFID.rut" @keyup.enter="getListarNotasPendientes">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Rango de fecha de inscripción</label>
                    <div class="col-md-8 form-group row pr-0">
                        <div class="col-md-12 pr-0">
                            <el-date-picker
                                v-model="fillBsqFID.fechaInsIni"
                                placeholder="Fecha inicio"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </div>
                        <div class="col-md-12 pr-0">
                            <el-date-picker
                                v-model="fillBsqFID.fechaInsFin"
                                placeholder="Fecha final"
                                format="dd/MM/yyyy"
                                value-format="yyyy-MM-dd">
                            </el-date-picker>
                        </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Carrera</label>
                    <div class="col-md-8">
                        <el-select v-model="fillBsqFID.idCarrera"
                        placeholder="Seleccione una idCarrera"
                        filterable
                        autocomplete="fillBsqFID.nIdEscuela"
                        @change="getListarNotasPendientes">
                        <el-option
                            v-for="item in listaDeCarrera"
                            :key="item.id"
                            :label="item.nombre"
                            :value="item.id">
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
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarNotasPendientes"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
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
      fillBsqFID:{
          nombre: '',
          apellido: '',
          rut: '',
          idCarrera: '',
          fechaInsIni: '',
          fechaInsFin: ''
      },
      fullscreenLoading: false,
      modalShow: false,
      mostrarModal: {
        display: 'block',
        background: '#0000006b',
      },
      ocultarModal: {
        display: 'none',
      },
      error: 0,
      thisyear: new Date(),
      mensajeError:[],
      listaDeCarrera: []
    }
  },
  computed: {
  },
   mounted(){
    EventBus.$emit('navegar', 'Asignar notas pendientes');
    this.init();
  },
  methods:{
    init(){
    },
    limpiarCriterios(){
      this.fillCrearNotaP.cNombre = '';
      this.fillCrearNotaP.cSlug = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getListarNotasPendientes(){

    },
    limpiarCriteriosBsq(){
      this.fillBsqFID.rut = '';
      this.fillBsqFID.nombre = '';
      this.fillBsqFID.apellido = '';
      this.fillBsqFID.idCarrera = '';
      this.fillBsqFID.fechaInsIni = '';
      this.fillBsqFID.fechaInsFin = '';
    },
  }// cierre methods
}
</script>

<style>

</style>
