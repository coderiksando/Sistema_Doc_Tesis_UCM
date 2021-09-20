<template>
<div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Parámetros del sistema</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Permitir registro de estudiantes</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="switch">
                                <input v-model="enableReg" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Estudiantes máximos por documentos</label>
                                <div class="col-md-3">
                                    <input type="number" max="100" class="form-control" v-model="maxStudentNumber">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-block" @click.prevent="resetEstudiantes">
                                <i class="fa fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Peso máximo avances de documento (MB)</label>
                                <div class="col-md-3">
                                    <input type="number" max="100" class="form-control" v-model="avancesTesisSize">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-block" @click.prevent="resetTesisSize">
                                <i class="fa fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Peso máximo actas de defensa (MB)</label>
                                <div class="col-md-3">
                                    <input type="number" max="100" class="form-control" v-model="actaSize">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-block" @click.prevent="resetActaSize">
                                <i class="fa fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Peso máximo constancia de examen (MB)</label>
                                <div class="col-md-3">
                                    <input type="number" max="100" class="form-control" v-model="constanciaSize">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-info btn-block" @click.prevent="resetConstanciaSize">
                                <i class="fa fa-redo"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Término de título abreviado</label>
                                <div class="col-md-6">
                                    <input type="text" max="20" class="form-control" v-model="terminoAbreviado">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info btn-block" @click.prevent="resetTerminoAbreviado">
                                        <i class="fa fa-redo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Término de título extendido</label>
                                <div class="col-md-6">
                                    <input type="text" max="200" class="form-control" v-model="terminoExtendido">
                                </div>
                                <div class="col-md-1">
                                    <button class="btn btn-info btn-block" @click.prevent="resetTerminoExtendido">
                                        <i class="fa fa-redo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Formatos de avances de documento</label>
                                <div class="col-md-7">
                                    <Multiselect
                                    v-model="formatosAvance.value"
                                    mode="tags"
                                    placeholder="Seleccionar formatos"
                                    :options="formatosAvance.options"
                                    :taggable="true"
                                    :multiple="true"
                                    tag-placeholder="Agregar formato"
                                    selectLabel="Presiona enter para seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="Presiona enter para remover"
                                    @tag="addTagAvance"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Formatos de actas de documento</label>
                                <div class="col-md-7">
                                    <Multiselect
                                    v-model="formatosActa.value"
                                    mode="tags"
                                    placeholder="Seleccionar formatos"
                                    :options="formatosActa.options"
                                    :taggable="true"
                                    :multiple="true"
                                    tag-placeholder="Agregar formato"
                                    selectLabel="Presiona enter para seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="Presiona enter para remover"
                                    @tag="addTagActa"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Formatos de constancia de exámen</label>
                                <div class="col-md-7">
                                    <Multiselect
                                    v-model="formatosConstancia.value"
                                    mode="tags"
                                    placeholder="Seleccionar formatos"
                                    :options="formatosConstancia.options"
                                    :taggable="true"
                                    :multiple="true"
                                    tag-placeholder="Agregar formato"
                                    selectLabel="Presiona enter para seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="Presiona enter para remover"
                                    @tag="addTagAvance"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4 offset-5">
                            <button class="btn btn-flat btn-info btnWidth" @click.prevent="guardarParametros" v-loading.fullscreen.lock="fullscreenLoading">
                                <i class="fa fa-save"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Multiselect from 'vue-multiselect';

  export default {
    components: { Multiselect },
    data() {
      return {
        formatosAvance:{
            value: [],
            options: ['.pdf', '.doc', '.xml']
        },
        formatosActa:{
            value: [],
            options: ['.pdf', '.doc', '.xml']
        },
        formatosConstancia:{
            value: [],
            options: ['.pdf', '.doc', '.xml']
        },
        maxStudentNumber : 0,
        avancesTesisSize: 0,
        actaSize: 0,
        constanciaSize: 0,
        defMaxStudentNumber : 0,
        defAvancesTesisSize: 0,
        defActaSize: 0,
        defConstanciaSize: 0,
        fullscreenLoading: false,
        enableReg: 0,
        terminoAbreviado: '',
        terminoExtendido: '',
        defTerminoAbreviado: '',
        defTerminoExtendido: '',
      }
    },
    mounted(){
        EventBus.$emit('navegar', 'Parámetros del sistema');
        this.getParametros()
    },
    methods: {
        addTagAvance (newTag, val) {
            console.log(newTag);
            this.formatosAvance.options.push(newTag)
            this.formatosAvance.value.push(newTag)
        },
        addTagActa (newTag, val) {
            console.log(newTag);
            this.formatosActa.options.push(newTag)
            this.formatosActa.value.push(newTag)
        },
        printo(){
            console.log(this.formatosAvance.value);
        },
        getParametros(){
            var url = '/admin/parametros';
            axios.post(url,{'params': ['MaxStudentNumber', 'AvancesTesisSize', 'ActaSize', 'ConstanciaSize', 'AvancesTesisFormato', 'ActaFormato', 'ConstanciaFormato', 'HabilitarRegistro', 'TerminoDeTitulo', 'TerminoDeTituloExtendido']}).then(response => {
                this.maxStudentNumber = this.defMaxStudentNumber = parseInt(response.data[0][0]);
                this.avancesTesisSize = this.defAvancesTesisSize = parseInt(response.data[1][0]);
                this.actaSize = this.defActaSize = parseInt(response.data[2][0]);
                this.constanciaSize = this.defConstanciaSize = parseInt(response.data[3][0]);
                this.formatosAvance.value = response.data[4];
                this.formatosActa.value = response.data[5];
                this.formatosConstancia.value = response.data[6];
                this.enableReg = parseInt(response.data[7][0]);
                this.terminoAbreviado = this.defTerminoAbreviado = response.data[8][0];
                this.terminoExtendido = this.defTerminoExtendido = response.data[9][0];
            })
        },
        guardarParametros(){
            var url = '/admin/setParametros';
            this.fullscreenLoading = true;
            axios.post(url,{
                'MaxStudentNumber'      : this.maxStudentNumber,
                'AvancesTesisSize'      : this.avancesTesisSize,
                'ActaSize'              : this.actaSize,
                'ConstanciaSize'        : this.constanciaSize,
                'AvancesTesisFormato'   : this.formatosAvance.value,
                'ActaFormato'           : this.formatosActa.value,
                'ConstanciaFormato'     : this.formatosConstancia.value,
                'HabilitarRegistro'     : this.enableReg,
                'TerminoAbreviado'      : this.terminoAbreviado,
                'TerminoExtendido'      : this.terminoExtendido,
                }).then(response => {
                    this.fullscreenLoading = false;
                    Swal.fire({
                        icon: 'success',
                        title: 'Parámetros guardados correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if (response.data == 200) {
                        localStorage.setItem('TerminoDeTitulo', JSON.stringify(this.terminoAbreviado));
                        localStorage.setItem('TerminoDeTituloExtendido', JSON.stringify(this.terminoExtendido));
                    }
            })
        },
        resetEstudiantes(){
            this.maxStudentNumber = this.defMaxStudentNumber;
        },
        resetTesisSize(){
            this.avancesTesisSize = this.defAvancesTesisSize;
        },
        resetActaSize(){
            this.actaSize = this.defActaSize;
        },
        resetConstanciaSize () {
            this.constanciaSize = this.defConstanciaSize;
        },
        resetTerminoAbreviado () {
            this.terminoAbreviado = this.defTerminoAbreviado;
        },
        resetTerminoExtendido () {
            this.terminoExtendido = this.defTerminoExtendido;
        },
    }
}
</script>

<style>

.switch {
  position: relative;
  display: inline-block;
  width: 50%;
  height: 65%;
  max-width: 70px;
  min-height: 34px;
  min-width: 30px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 80%;
  width: 45%;
  left: 5%;
  bottom: 10%;
  background-color: #ffffff;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: rgb(65, 184, 131);
}

input:hover + .slider {
  box-shadow: 0 0 1px rgb(65, 184, 131);
}

input:checked + .slider:before {
  -webkit-transform: translateX(100%);
  -ms-transform: translateX(100%);
  transform: translateX(100%);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

</style>
