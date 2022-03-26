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
                        <input placeholder="Nombre" type="text" class="form-control" v-model="fillBsqFID.nombre" @keyup.enter="getListarTesis">
                    </div>
                    <div class="col-md-4">
                        <input placeholder="Apellido" type="text" class="form-control" v-model="fillBsqFID.apellido" @keyup.enter="getListarTesis">
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group row">
                    <label class="col-md-4 col-form-label">Rut</label>
                    <div class="col-md-8">
                        <input placeholder="11111111-1" type="text" class="form-control" v-model="fillBsqFID.rut" @keyup.enter="getListarTesis">
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
                        @change="getListarTesis">
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
                <button class="btn btn-flat btn-info btnWidth" @click.prevent="getListarTesis"
                  >{{globVar.btnSearch}}</button>
                <button class="btn btn-flat btn-default btnWidth" @click.prevent="limpiarCriteriosBsq">{{globVar.btnClear}}</button>
              </div>
            </div>
          </div>

          <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Bandeja de resultados</h3>
            </div>
            <div class="card-body table-responsive">
                <template v-if="listaFIDs.length > 0">
                    <div class="input-group">
                        <div class="col-md-12 input-group" style="display: flex; align-items: center;">
                            <input type="checkbox" v-bind:id="'cboxTodos'" v-model="checkboxTodos" @change="checkAll()">
                            <b>Marcar todos</b>
                        </div>
                        <table class ="col-md-9 table table-hover table-head-fixed text-nowrap projects smallSize">
                            <thead>
                            <tr>
                                <th class="col-md-4">Alumno</th>
                                <th>Título {{terminoTitulo}}</th>
                                <th>Fecha inscripción {{terminoTitulo}}</th>
                            </tr>
                            </thead>
                            <tbody v-loading="fullscreenLoading">
                            <tr v-for="(item, index) in listaFIDs" :key="index">
                                <td class="col-md-4"> <!-- itera mostrando la cantidad total de estudiantes -->
                                    <div class="input-group" style="display: flex; align-items: center;"
                                    v-for="(itemUser, index) in item.listUsers" :key="index">
                                        <input type="checkbox" @change="check(itemUser, item.id)"
                                        v-model="itemUser.boolean" v-bind:id="'cbox_'+itemUser.id_user+'_'+item.id_user"
                                        :value="{idUser: itemUser.id_user, idFid: item.id_user}">
                                        <div v-text="itemUser.nombres + ' ' + itemUser.apellidos"></div>
                                    </div>
                                </td>
                                <td>{{'('+item.escuela.nombre_abreviado+')'}}{{(item.titulo.length >= 40) ? item.titulo.slice(0, 40)+'...': item.titulo.slice(0, 40)}}</td>
                                <td>{{item.created_at | moment}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="col-md-3">
                            <div class="card">
                                <!-- position-fixed -->
                                <div class="card card-info mb-0">
                                    <div class="card-header">
                                        <h3 class="card-title">Inserción de fecha</h3>
                                    </div>
                                    <div class="card-body">
                                        <form role="form">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <label class="col-md-12 col-form-label">Fecha de nota P.</label>
                                                    <div class="col-md-12">
                                                        <el-date-picker
                                                            v-model="fillForm.date"
                                                            placeholder="Fecha inicio"
                                                            format="dd/MM/yyyy"
                                                            value-format="yyyy-MM-dd">
                                                        </el-date-picker>
                                                    </div>
                                                </div>
                                                <button class="btn btn-info btnWidth" @click.prevent="asignarNotaP">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
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
        </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
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
      fillForm:{
        date: '',
        listUser: []
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
      terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
      terminoTituloExtendido: JSON.parse(localStorage.getItem('TerminoDeTituloExtendido')),
      listaDeCarrera: [],
      listaFIDs: [],
      checkboxTodos: false,

    }
  },
  computed: {
  },
   mounted(){
    EventBus.$emit('navegar', 'Asignar notas pendientes');
    this.init();
  },
  filters:{
    moment: function (date) {
      moment.locale('es');
      return moment(date).format('DD/MM/YYYY');
    }
  },
  methods:{
    init(){
      this.getEscuelas();
    },
    limpiarCriterios(){
      this.fillCrearNotaP.cNombre = '';
      this.fillCrearNotaP.cSlug = '';
    },
    abrirModal(){
      this.modalShow = !this.modalShow;
    },
    getListarTesis(){
        this.fullscreenLoading = true;
        var url = '/alumno/getListarTesis';
        axios.get(url, {
            params:{
            'nombre'    :   this.fillBsqFID.nombre,
            'apellido'  :   this.fillBsqFID.apellido,
            'estado'    :   ['', 'D'],
            'rut'       :   this.fillBsqFID.rut,
            'fechaSt'   :   (!this.fillBsqFID.fechaInsIni) ? '' : this.fillBsqFID.fechaInsIni,
            'fechaEn'   :   (!this.fillBsqFID.fechaInsFin) ? '' : this.fillBsqFID.fechaInsFin,
            'nIdEscuela':   this.fillBsqFID.idCarrera
            }
        }).then(response => {
            this.listaFIDs = response.data;
            this.listaFIDs.forEach(fid => {
                fid.listUsers.forEach(user => {
                    user.boolean = false;
                });
            });
            this.fullscreenLoading = false;
        })
    },
    getEscuelas(){
        this.fullscreenLoading = true;
        var url = '/administracion/escuelas/getListarEscuelas';
        axios.get(url, {
        }).then(response => {
            this.listaDeCarrera = response.data;
            this.listaDeCarrera.unshift({'id':'', 'nombre': 'Todas', 'facultad': {'id':0}});
            this.fullscreenLoading = false;
        })
    },
    limpiarCriteriosBsq(){
      this.fillBsqFID.rut = '';
      this.fillBsqFID.nombre = '';
      this.fillBsqFID.apellido = '';
      this.fillBsqFID.idCarrera = '';
      this.fillBsqFID.fechaInsIni = '';
      this.fillBsqFID.fechaInsFin = '';
    },
    check(user, idFid){
        let data = {idUser: user.id_user, idFid: idFid};
        let index = this.fillForm.listUser.findIndex(obj => JSON.stringify(obj) == JSON.stringify(data));
        if (index != -1) {
            this.fillForm.listUser.splice(index, 1);
        } else {
            this.fillForm.listUser.push({idUser: user.id_user, idFid: idFid});
        }
        console.log(this.fillForm.listUser);
    },
    checkAll(){
        if (this.checkboxTodos) {
            this.listaFIDs.forEach(fid => {
                fid.listUsers.forEach(user => {
                    let objUserFid = {idUser: user.id_user, idFid: fid.id};
                    if (this.fillForm.listUser.findIndex(obj => JSON.stringify(obj) == JSON.stringify(objUserFid)) == -1) {
                        user.boolean = true;
                        this.fillForm.listUser.push(objUserFid);
                    }
                });
            });
        } else {
            this.listaFIDs.forEach(fid => {
                fid.listUsers.forEach(user => {
                    user.boolean = false;
                });
            });
            this.fillForm.listUser = [];
        }
        console.log(this.fillForm.listUser);
    },
    asignarNotaP(){
        const url = '/notaspendientes/asignarNotasP';
        if (this.revisionDatos()) {
            axios.post(url, this.fillForm
            ).then(response => {
                this.$router.push('/notaspendientes');
                Swal.fire({
                    icon: 'success',
                    title: 'Notas pendientes registradas correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
            }).catch(response=>{
                this.fullscreenLoading = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error al registrar notas pendientes',
                    showConfirmButton: false,
                    timer: 1500
                })
            })
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Faltan datos ingresados',
                showConfirmButton: false,
                timer: 1500
            })
        }
    },
    revisionDatos(){
        if (!this.fillForm.date) return false;
        if (this.fillForm.listUser.length == 0) return false;
        return true;
    }
  }// cierre methods
}
</script>

<style>
.smallSize {
    font-size: 0.9rem;
}
</style>
