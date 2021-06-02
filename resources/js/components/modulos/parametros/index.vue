<template>
<div>
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
                        <div class="col-md-2">
                            <button class="btn btn-flat btn-info btnWidth">
                                <i class="fa fa-floppy-o"></i>
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
                        <div class="col-md-2">
                            <button class="btn btn-flat btn-info btnWidth">
                                <i class="fa fa-floppy-o"></i>
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
                        <div class="col-md-2">
                            <button class="btn btn-flat btn-info btnWidth">
                                <i class="fa fa-floppy-o"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Formatos Avances de Tesis</label>
                                <div class="col-md-8">
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
                                <div class="col-md-8">
                                    <Multiselect
                                    v-model="formatosActa.value"
                                    mode="tags"
                                    placeholder="Seleccionar formatos"
                                    :options="formatosActa.options"
                                    :taggable="true"
                                    :multiple="true"
                                    tag-placeholder="Agregar formato"
                                    @tag="addTagActa"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-4 offset-4">
                            <button class="btn btn-flat btn-info btnWidth" @click.prevent="printo">Buscar</button>
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
                this.maxStudentNumber = parseInt(response.data[0]);
                this.avancesTesisSize = parseInt(response.data[1]);
                this.actaSize = parseInt(response.data[2]);
                this.formatosAvance.value = response.data[3];
                this.formatosActa.value = response.data[4];
            })
        }
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>