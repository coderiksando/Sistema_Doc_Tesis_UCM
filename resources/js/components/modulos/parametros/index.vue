<template>
<div>
    <meta charset="utf-8"/>
    <div class="card-body">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Parametros del Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group row">
                                <label class="col-md-8 col-form-label">Estudiantes maximos por tesis</label>
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
                                <label class="col-md-8 col-form-label">Peso máximo avances de tesis (MB)</label>
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
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Formatos Avances de Tesis</label>
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
                                <label class="col-md-4 col-form-label">Formatos Actas de Tesis</label>
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
        maxStudentNumber : 0,
        avancesTesisSize: 0,
        actaSize: 0,
        defMaxStudentNumber : 0,
        defAvancesTesisSize: 0,
        defActaSize: 0,
        fullscreenLoading: false
      }
    },
    mounted(){
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
            axios.post(url,{'params': ['MaxStudentNumber', 'AvancesTesisSize', 'ActaSize', 'AvancesTesisFormato', 'ActaFormato']}).then(response => {
                this.maxStudentNumber = this.defMaxStudentNumber = parseInt(response.data[0][0]);
                this.avancesTesisSize = this.defAvancesTesisSize = parseInt(response.data[1][0]);
                this.actaSize = this.defActaSize = parseInt(response.data[2][0]);
                this.formatosAvance.value = response.data[3];
                this.formatosActa.value = response.data[4];
            })
        },
        guardarParametros(){
            var url = '/admin/setParametros';
            this.fullscreenLoading = true;
            axios.post(url,{
                'MaxStudentNumber' : this.maxStudentNumber, 
                'AvancesTesisSize': this.avancesTesisSize, 
                'ActaSize' : this.actaSize, 
                'AvancesTesisFormato' : this.formatosAvance.value, 
                'ActaFormato' : this.formatosActa.value
                }).then(response => {
                    console.log(response.data);
                    this.fullscreenLoading = false;
                    // this.$router.push('/parametros');
                    Swal.fire({
                    icon: 'success',
                    title: 'Parámetros guardados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })
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
        }
    }
}
</script>

<style
src="vue-multiselect/dist/vue-multiselect.min.css">
</style>