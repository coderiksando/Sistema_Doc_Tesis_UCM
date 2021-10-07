<template>
    <div class="card">
    <template v-if="rolActivo == 'Profesor'">
    <div class="card-body pb-0">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header btn" data-toggle="collapse" data-target="#busquedaTotal" aria-expanded="false" aria-controls="busquedaTotal">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-search"></i>
                                Sistema de búsqueda
                            </h3>
                        </div>
                    </div>
                </div>
            <div class="collapse" id="busquedaTotal">
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Título</label>
                            <input :placeholder="'Título de '+terminoTitulo" class="col-md-9 form-control" type="text" v-model="busquedaComision.tituloFID" @keyup.enter="comisionesByRol">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Alumno</label>
                            <input placeholder="Nombre de alumno completo" class="col-md-9 form-control" type="text" v-model="busquedaComision.nombreAlumno" @keyup.enter="comisionesByRol">
                        </div>
                    </div>
                    <div title="Sección enfocada en Prof. Guía" class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Comisión</label>
                            <div class="col-md-9 px-0">
                                <multiselect
                                    v-model="busquedaComision.existenciaComision"
                                    label='valor'
                                    track-by="id"
                                    placeholder="Seleccione un tipo"
                                    :options="busquedaOpcion"
                                    selectLabel="Seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="No puede quitar este valor"
                                    :allow-empty="false"
                                    @input="comisionesByRol">
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Rango de fechas</label>
                            </div>
                            <div class="col-md-6">
                                <el-date-picker
                                    v-model="busquedaComision.dateRange.startDate"
                                    style="width: 100%;"
                                    placeholder="Inicio"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="startOption"
                                    @change="selectStart">
                                </el-date-picker>
                            </div>
                            <div class="col-md-6">
                                <el-date-picker
                                    v-model="busquedaComision.dateRange.endDate"
                                    style="width: 100%;"
                                    placeholder="Final"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :picker-options="endOption"
                                    @change="comisionesByRol">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Comisiones donde soy Prof. Guía</h3>
                    </div>
                    <template v-if="listarComisionesPaginated.length">
                        <div v-if="listarComisionesPaginated.map(x => {if (x.archivoActa.length) return x.archivoActa;}).filter(x => x).length" class="col-md-3">
                            <el-tooltip class="item" effect="dark" content="Descargar actas en forma masiva (ZIP)" placement="right">
                                <el-button @click.prevent="descargaActaZip(listarComisionesPaginated, 'actaGuia')">
                                    <i class="fas fa-file-archive"></i> Exportar actas
                                </el-button>
                            </el-tooltip>
                        </div>
                    </template>
                </div>
            </div>
            <div id="accordion">
            <template v-if="listarComisionesPaginated.length">
                <template v-for="(item, index) in listarComisionesPaginated">
                    <div class="card mb-1" :key="'guia'+index">
                        <div class="card-body py-1" :id="'heading'+index">
                        <h3 class="mb-0">
                            <div class="btn btn-link col-md-12 noPadNoMar d-flex">
                                <div title="Sección expandible" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="false" :aria-controls="'collapse'+index"><a class="btn"><i class="fas fa-plus-circle"></i></a></div>
                                <div title="Sección expandible" class="col-md-9 noPadNoMar" data-toggle="collapse" :data-target="'#collapse'+index" aria-expanded="false" :aria-controls="'collapse'+index"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.titulo.slice(0, 40))}}</p></div>
                                <div class="col-md-3 noPadNoMar d-flex flex-wrap">
                                    <template v-if="!item.comisiones">
                                        <router-link title="Crear comisión" class="btn boton btn-info mr-1" :to="{name:'comisiones.crear', params:{id: item.id}}">
                                            <i class="fas fa-plus-circle"></i>
                                        </router-link>
                                    </template>
                                    <template v-if="item.comisiones">
                                        <router-link title="Editar comisión" class="btn boton btn-info mr-1" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </router-link>
                                    </template>
                                    <a v-if="item.archivoPendienteRevision" title="Descargar documento" class="btn boton btn-warning mr-1" :href="item.archivoPendienteRevision.path" target="_blank">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                    <button v-if="item.archivoActa.length > 0" :title="'Descargar acta de defensa'" class="btn boton btn-primary mr-1" @click.prevent="getDocumento(item.id)">
                                        <i class="fas fa-file-download"></i>
                                    </button>
                                    <template v-if="item.comisiones && item.archivoPendienteRevision">
                                        <button title="Ingresar revisión" class="btn boton btn-success mr-1" @click.prevent="modalInsercionDocumento(item)">
                                            <i class="fas fa-file-upload"></i>
                                        </button>
                                    </template>
                                    <template v-if="item.aprobado_pg == 'EA' && item.comisiones">
                                        <button title="Eliminar comisión y documentos de revisión" class="btn boton btn-danger mr-1" @click.prevent="eliminarComision(item)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapse'+index" class="collapse" :aria-labelledby="'heading'+index" data-parent="#accordion">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-4">Alumnos integrantes:</dt>
                                <template v-if="item.fit__user">
                                    <dd class="col-md-8">
                                        <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                            {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                        </div>
                                    </dd>
                                </template>
                                <dt class="col-md-4">Escuela:</dt>
                                <dd class="col-md-8">{{item.escuela.nombre}}</dd>
                                <dt class="col-md-4">Título:</dt>
                                <dd class="col-md-8">{{globFunct.capitalizeFirstLetter(item.titulo)}}</dd>
                                <dt class="col-md-4">Descripción:</dt>
                                <dd class="col-md-8">{{item.descripcion}}</dd>
                                <template v-if="item.user__p__coguia">
                                    <dt class="col-md-4">Prof. Co-guía:</dt>
                                    <dd class="col-md-8">{{item.user__p__coguia.nombres.split(' ')[0] + ' ' + item.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <dt class="col-md-4">Comisión evaluadora:</dt>
                                <dd class="col-md-8">
                                    <dl v-if="item.comisiones" class="row">
                                        <template v-if="item.comisiones.user_p1">
                                            <dt class="col-md-4">1° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.comisiones.user_p1.nombres.split(' ')[0]+' '+item.comisiones.user_p1.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template v-if="item.comisiones.user_p2">
                                            <dt class="col-md-4">2° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.comisiones.user_p2.nombres.split(' ')[0]+' '+item.comisiones.user_p2.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template>
                                            <dt class="col-md-4">Prof. Externo:</dt>
                                            <dd class="col-md-8">{{item.comisiones.p_externo}}</dd>
                                        </template>
                                    </dl>
                                </dd>
                                <dt class="col-md-4">Tipo de documento:</dt>
                                <dd class="col-md-8">{{item.tipo}}</dd>
                            </dl>
                        </div>
                        </div>
                    </div>
                </template>
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
                <div class="callout callout-primary">
                <h5> No se han encontrado resultados...</h5>
                </div>
            </template>
            </div>

        </div>
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Comisiones donde soy partícipe</h3>
                    </div>
                    <template v-if="listarComisionesPaginated2.length">
                        <div v-if="listarComisionesPaginated2.map(x => {if (x.fit.archivoActa.length) return x.fit.archivoActa;}).filter(x => x).length" class="col-md-3">
                            <el-tooltip class="item" effect="dark" content="Descargar actas donde soy participe (ZIP)" placement="right">
                                <el-button @click.prevent="descargaActaZip(listarComisionesPaginated2, 'actaParticipe')">
                                    <i class="fas fa-file-archive"></i> Exportar actas
                                </el-button>
                            </el-tooltip>
                        </div>
                    </template>
                </div>
            </div>

            <div id="accordion2">
            <template v-if="listarComisionesPaginated2.length">
                <template v-for="(item, index) in listarComisionesPaginated2">
                    <div class="card mb-1" :key="'participe'+index">
                        <div class="card-body py-1" :id="'headingParticipe'+index"
                        :style="(!item.revisionComisionPropia.length && item.archivoPendienteRevision)?'background-color: lightpink;':''">
                        <h3 class="mb-0">
                            <div class="btn btn-link col-md-12 noPadNoMar d-flex">
                                <div :title="(!item.revisionComisionPropia.length && item.archivoPendienteRevision)?'Existe un documento sin revisión':'Sección expandible'"
                                data-toggle="collapse" :data-target="'#collapseParticipe'+index" aria-expanded="false" :aria-controls="'collapseParticipe'+index"><a class="btn"><i class="fas fa-plus-circle"></i></a></div>
                                <div :title="(!item.revisionComisionPropia.length && item.archivoPendienteRevision)?'Existe un documento sin revisión':'Sección expandible'"
                                class="col-md-9 noPadNoMar"  data-toggle="collapse" :data-target="'#collapseParticipe'+index" aria-expanded="false" :aria-controls="'collapseParticipe'+index"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.fit.titulo.slice(0, 40))}}</p></div>
                                <div class="col-md-3 noPadNoMar d-flex flex-wrap">
                                    <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary mr-1" :to="{name:'tesis.ver', params:{id: item.fit.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                    <a v-if="item.archivoPendienteRevision" title="Descargar documento" class="btn boton btn-warning mr-1" :href="item.archivoPendienteRevision.path" target="_blank">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                    <button v-if="item.fit.archivoActa.length > 0" :title="'Descargar acta de defensa'" class="btn boton btn-primary mr-1" @click.prevent="getDocumento(item.fit.id)">
                                        <i class="fas fa-file-download"></i>
                                    </button>
                                    <template v-if="item">
                                        <button title="Ingresar revisión" class="btn boton btn-success mr-1" @click.prevent="modalInsercionDocumento(item.fit)">
                                            <i class="fas fa-file-upload"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapseParticipe'+index" class="collapse" :aria-labelledby="'headingParticipe'+index" data-parent="#accordion2">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-4">Alumnos integrantes:</dt>
                                <template v-if="item.fit.fit__user">
                                    <dd class="col-md-8">
                                        <div v-for="(fitUser, index2) in item.fit.fit__user" :key="index2">
                                            {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                        </div>
                                    </dd>
                                </template>
                                <dt class="col-md-4">Escuela:</dt>
                                <dd class="col-md-8">{{item.fit.escuela.nombre}}</dd>
                                <dt class="col-md-4">Título:</dt>
                                <dd class="col-md-8">{{globFunct.capitalizeFirstLetter(item.fit.titulo)}}</dd>
                                <dt class="col-md-4">Descripción:</dt>
                                <dd class="col-md-8">{{item.fit.descripcion}}</dd>
                                <template v-if="item.fit.user__p__guia">
                                    <dt class="col-md-4">Prof. Guía:</dt>
                                    <dd class="col-md-8">{{item.fit.user__p__guia.nombres.split(' ')[0] + ' ' + item.fit.user__p__guia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <template v-if="item.fit.user__p__coguia">
                                    <dt class="col-md-4">Prof. Co-guía:</dt>
                                    <dd class="col-md-8">{{item.fit.user__p__coguia.nombres.split(' ')[0] + ' ' + item.fit.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <dt class="col-md-4">Comisión evaluadora:</dt>
                                <dd class="col-md-8">
                                    <dl v-if="item" class="row">
                                        <template v-if="item.user_p1">
                                            <dt class="col-md-4">1° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.user_p1.nombres.split(' ')[0] +' '+item.user_p1.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template v-if="item.user_p2">
                                            <dt class="col-md-4">2° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.user_p2.nombres.split(' ')[0] +' '+item.user_p2.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template>
                                            <dt class="col-md-4">Prof. Externo:</dt>
                                            <dd class="col-md-8">{{item.p_externo}}</dd>
                                        </template>
                                    </dl>
                                </dd>
                                <dt class="col-md-4">Tipo de documento:</dt>
                                <dd class="col-md-8">{{item.fit.tipo}}</dd>
                            </dl>
                        </div>
                        </div>
                    </div>
                </template>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item" v-if="pageNumber2 > 0">
                        <a href="#" class="page-link" @click.prevent="prevPage2">Ant</a>
                        </li>
                        <li class="page-item" v-for="(page, index) in pagesList2" :key="index"
                        :class="[page == pageNumber2 ? 'active' : '']"
                        :style="(page < pageNumber2 - 2 || page > pageNumber2 + 2) ? 'display: none' : ''">
                        <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
                        </li>
                        <li class="page-item" v-if="pageNumber2 < pageCount2 -1">
                        <a href="#" class="page-link" @click.prevent="nextPage2">Post</a>
                        </li>
                    </ul>
                </div>
                </template>
                <template v-else>
                <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                </div>
                </template>
            </div>

        </div>

        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title">Comisiones en las que me han sugerido</h3>
                    </div>
                </div>
            </div>
            <div id="accordion3">
            <template v-if="listarComisionesPaginated3.length">
                <template v-for="(item, index) in listarComisionesPaginated3">
                    <div class="card mb-1" :key="'participeSugerido'+index">
                        <div class="card-body py-1" :id="'headingParticipeSugerido'+index">
                        <h3 class="mb-0">
                            <div class="btn btn-link col-md-12 noPadNoMar d-flex">
                                <div title="Sección expandible" data-toggle="collapse" :data-target="'#collapseParticipeSugerido'+index" aria-expanded="false" :aria-controls="'collapseParticipeSugerido'+index"><a class="btn"><i class="fas fa-plus-circle"></i></a></div>
                                <div title="Sección expandible" class="col-md-9 noPadNoMar"  data-toggle="collapse" :data-target="'#collapseParticipeSugerido'+index" aria-expanded="false" :aria-controls="'collapseParticipeSugerido'+index"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.fit.titulo.slice(0, 40))}}</p></div>
                                <div class="col-md-3 noPadNoMar d-flex flex-wrap">
                                    <router-link :title="'Ver '+terminoTitulo" class="btn boton btn-primary mr-1" :to="{name:'tesis.ver', params:{id: item.fit.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapseParticipeSugerido'+index" class="collapse" :aria-labelledby="'headingParticipeSugerido'+index" data-parent="#accordion3">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-4">Alumnos integrantes:</dt>
                                <template v-if="item.fit.fit__user">
                                    <dd class="col-md-8">
                                        <div v-for="(fitUser, index2) in item.fit.fit__user" :key="index2">
                                            {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                        </div>
                                    </dd>
                                </template>
                                <dt class="col-md-4">Escuela:</dt>
                                <dd class="col-md-8">{{item.fit.escuela.nombre}}</dd>
                                <dt class="col-md-4">Título:</dt>
                                <dd class="col-md-8">{{globFunct.capitalizeFirstLetter(item.fit.titulo)}}</dd>
                                <dt class="col-md-4">Descripción:</dt>
                                <dd class="col-md-8">{{item.fit.descripcion}}</dd>
                                <template v-if="item.fit.user__p__guia">
                                    <dt class="col-md-4">Prof. Guía:</dt>
                                    <dd class="col-md-8">{{item.fit.user__p__guia.nombres.split(' ')[0] + ' ' + item.fit.user__p__guia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <template v-if="item.fit.user__p__coguia">
                                    <dt class="col-md-4">Prof. Co-guía:</dt>
                                    <dd class="col-md-8">{{item.fit.user__p__coguia.nombres.split(' ')[0] + ' ' + item.fit.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <dt class="col-md-4">Comisión evaluadora:</dt>
                                <dd class="col-md-8">
                                    <dl v-if="item" class="row">
                                        <template v-if="item.user_p1">
                                            <dt class="col-md-4">1° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.user_p1.nombres.split(' ')[0] +' '+item.user_p1.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template v-if="item.user_p2">
                                            <dt class="col-md-4">2° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.user_p2.nombres.split(' ')[0] +' '+item.user_p2.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template>
                                            <dt class="col-md-4">Prof. Externo:</dt>
                                            <dd class="col-md-8">{{item.p_externo}}</dd>
                                        </template>
                                    </dl>
                                </dd>
                                <dt class="col-md-4">Tipo de documento:</dt>
                                <dd class="col-md-8">{{item.fit.tipo}}</dd>
                            </dl>
                        </div>
                        </div>
                    </div>
                </template>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item" v-if="pageNumber3 > 0">
                        <a href="#" class="page-link" @click.prevent="prevPage3">Ant</a>
                        </li>
                        <li class="page-item" v-for="(page, index) in pagesList3" :key="index"
                        :class="[page == pageNumber3 ? 'active' : '']"
                        :style="(page < pageNumber3 - 2 || page > pageNumber3 + 2) ? 'display: none' : ''">
                        <a href="#" class=page-link @click.prevent="selectPage3(page)"> {{page+1}}</a>
                        </li>
                        <li class="page-item" v-if="pageNumber3 < pageCount3 -1">
                        <a href="#" class="page-link" @click.prevent="nextPage3">Post</a>
                        </li>
                    </ul>
                </div>
                </template>
                <template v-else>
                <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                </div>
                </template>
            </div>

        </div>

        </div>
    </div>
    </template>
    <template v-if="rolActivo != 'Profesor'">
    <div class="card-body pb-0">
        <div class="container-fluid">
            <div class="card card-info">
                <div class="card-header btn" data-toggle="collapse" data-target="#busquedaTotal" aria-expanded="false" aria-controls="busquedaTotal">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title font-weight-bold">
                                <i class="fas fa-search"></i>
                                Sistema de búsqueda
                            </h3>
                        </div>
                    </div>
                </div>
            <div class="collapse" id="busquedaTotal">
                <div class="card-body row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Título</label>
                            <input :placeholder="'Título de '+terminoTitulo" class="col-md-9 form-control" type="text" v-model="busquedaComision.tituloFID" @keyup.enter="getListarComisionesByParametros">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Alumno</label>
                            <input placeholder="Nombre de alumno completo" class="col-md-9 form-control" type="text" v-model="busquedaComision.nombreAlumno" @keyup.enter="getListarComisionesByParametros">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Prof. Guía</label>
                            <input placeholder="Nombre de profesor completo" class="col-md-9 form-control" type="text" v-model="busquedaComision.nombreProf" @keyup.enter="getListarComisionesByParametros">
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Comisión</label>
                            <div class="col-md-9 px-0">
                                <multiselect
                                    v-model="busquedaComision.existenciaComision"
                                    label='valor'
                                    track-by="id"
                                    :options="busquedaOpcion"
                                    placeholder="Seleccione un tipo"
                                    selectLabel="Seleccionar"
                                    selectedLabel="Seleccionado"
                                    deselectLabel="No puede quitar este valor"
                                    :allow-empty="false"
                                    @input="getListarComisionesByParametros">
                                </multiselect>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="col-form-label">Rango de fechas</label>
                            </div>
                            <div class="col-md-6">
                                <el-date-picker
                                    v-model="busquedaComision.dateRange.startDate"
                                    style="width: 100%;"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Inicio"
                                    :picker-options="startOption"
                                    @change="selectStart">
                                </el-date-picker>
                            </div>
                            <div class="col-md-6">
                                <el-date-picker
                                    v-model="busquedaComision.dateRange.endDate"
                                    style="width: 100%;"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Final"
                                    :picker-options="endOption"
                                    @change="comisionesByRol">
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
    <div class="card-body py-0">
        <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title font-weight-bold">Total de comisiones establecidas</h3>
                    </div>
                    <template v-if="listarComisionesTotalesPaginated.length">
                        <div v-if="listarComisionesTotalesPaginated.map(x => {if (x.archivoActa.length) return x.archivoActa;}).filter(x => x).length" class="col-md-3">
                            <el-tooltip class="item" effect="dark" content="Descargar total de actas (ZIP)" placement="right">
                                <el-button @click.prevent="descargaActaZip(listarComisionesTotalesPaginated, 'totalDeActasBuscada')">
                                    <i class="fas fa-file-archive"></i> Exportar actas
                                </el-button>
                            </el-tooltip>
                        </div>
                    </template>
                </div>
            </div>
            <div id="accordion3" v-loading="fullscreenLoading">
            <template v-if="listarComisionesTotalesPaginated.length">
                <template v-for="(item, index) in listarComisionesTotalesPaginated">
                    <div class="card mb-1" :key="'total'+index">
                        <div class="card-body py-1" :id="'headingTotal'+index">
                        <h3 class="mb-0">
                            <div class="btn btn-link col-md-12 noPadNoMar d-flex">
                                <div title="Sección expandible" data-toggle="collapse" :data-target="'#collapseTotal'+index" aria-expanded="false" :aria-controls="'collapseTotal'+index"><a class="btn"><i class="fas fa-plus-circle"></i></a></div>
                                <div v-if="item.comisiones" title="Sección expandible" class="col-md-8 noPadNoMar" data-toggle="collapse" :data-target="'#collapseTotal'+index" aria-expanded="false" :aria-controls="'collapseTotal'+index"><p class="float-left">{{moment(item.comisiones.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.titulo.slice(0, 40))}}</p></div>
                                <div v-if="!item.comisiones" title="Sección expandible" class="col-md-8 noPadNoMar" data-toggle="collapse" :data-target="'#collapseTotal'+index" aria-expanded="false" :aria-controls="'collapseTotal'+index"><p class="float-left">{{moment(item.updated_at).format("DD-MM-YYYY") + ', ' + globFunct.capitalizeFirstLetter(item.titulo.slice(0, 40))}}</p></div>
                                <div class="col-md-4 noPadNoMar d-flex flex-wrap">
                                    <router-link :title="'Ver '+ terminoTitulo" class="btn boton btn-primary mr-1" :to="{name:'tesis.ver', params:{id: item.id}}">
                                        <i class="fas fa-eye"></i>
                                    </router-link>
                                    <a v-if="item.archivoPendienteRevision" title="Descargar documento" class="btn boton btn-warning mr-1" :href="item.archivoPendienteRevision.path" target="_blank">
                                        <i class="fas fa-file-download"></i>
                                    </a>
                                    <button v-if="item.archivoActa.length > 0" :title="'Descargar acta de defensa'" class="btn boton btn-primary mr-1" @click.prevent="getDocumento(item.id)">
                                        <i class="fas fa-file-download"></i>
                                    </button>
                                    <button v-if="item.comisiones" title="Ingresar revisión" class="btn boton btn-success mr-1" @click.prevent="modalInsercionDocumento(item)">
                                        <i class="fas fa-file-upload"></i>
                                    </button>
                                    <!-- <router-link title="Ver revisiones de comisión" class="btn boton btn-info" :to="'tesis/revisiones'">
                                        <i class="fa fa-list-alt"></i>
                                    </router-link> -->
                                    <template v-if="item.comisiones">
                                        <router-link title="Editar comisión" class="btn boton btn-danger mr-1" :to="{name:'comisiones.editar', params:{id: item.comisiones.id}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </router-link>
                                    </template>
                                </div>
                            </div>
                        </h3>
                        </div>

                        <div :id="'collapseTotal'+index" class="collapse" :aria-labelledby="'headingTotal'+index" data-parent="#accordion3">
                        <div class="card-footer">
                            <dl class="row">
                                <dt class="col-md-4">Alumnos integrantes:</dt>
                                <template v-if="item.fit__user">
                                    <dd class="col-md-8">
                                        <div v-for="(fitUser, index2) in item.fit__user" :key="index2">
                                            {{fitUser.user.nombres.split(' ')[0]+' '+fitUser.user.apellidos.split(' ')[0]}}
                                        </div>
                                    </dd>
                                </template>
                                <dt class="col-md-4">Escuela:</dt>
                                <dd class="col-md-8">{{item.escuela.nombre}}</dd>
                                <dt class="col-md-4">Título:</dt>
                                <dd class="col-md-8">{{globFunct.capitalizeFirstLetter(item.titulo)}}</dd>
                                <dt class="col-md-4">Descripción:</dt>
                                <dd class="col-md-8">{{item.descripcion}}</dd>
                                <template v-if="item.user__p__guia">
                                    <dt class="col-md-4">Prof. Guía:</dt>
                                    <dd class="col-md-8">{{item.user__p__guia.nombres.split(' ')[0] + ' ' + item.user__p__guia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <template v-if="item.user__p__coguia">
                                    <dt class="col-md-4">Prof. Co-guía:</dt>
                                    <dd class="col-md-8">{{item.user__p__coguia.nombres.split(' ')[0] + ' ' + item.user__p__coguia.apellidos.split(' ')[0]}}</dd>
                                </template>
                                <dt class="col-md-4">Comisión evaluadora:</dt>
                                <dd class="col-md-8">
                                    <dl v-if="item.comisiones" class="row">
                                        <template v-if="item.comisiones.user_p1">
                                            <dt class="col-md-4">1° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.comisiones.user_p1.nombres.split(' ')[0]+' '+item.comisiones.user_p1.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template v-if="item.user_p2">
                                            <dt class="col-md-4">2° Prof. Interno:</dt>
                                            <dd class="col-md-8">{{item.comisiones.user_p2.nombres.split(' ')[0]+' '+item.comisiones.user_p2.apellidos.split(' ')[0]}}</dd>
                                        </template>
                                        <template>
                                            <dt class="col-md-4">Prof. Externo:</dt>
                                            <dd class="col-md-8">{{item.comisiones.p_externo}}</dd>
                                        </template>
                                    </dl>
                                </dd>
                                <dt class="col-md-4">Tipo de documento:</dt>
                                <dd class="col-md-8">{{item.tipo}}</dd>
                            </dl>
                        </div>
                        </div>
                    </div>
                </template>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item" v-if="totalPageNumber > 0">
                        <a href="#" class="page-link" @click.prevent="totalPrevPage">Ant</a>
                        </li>
                        <li class="page-item" v-for="(page, index) in totalPagesList" :key="index"
                        :class="[page == totalPageNumber ? 'active' : '']"
                        :style="(page < totalPageNumber - 2 || page > totalPageNumber + 2) ? 'display: none' : ''">
                        <a href="#" class=page-link @click.prevent="selectPage2(page)"> {{page+1}}</a>
                        </li>
                        <li class="page-item" v-if="totalPageNumber < totalPageCount -1">
                        <a href="#" class="page-link" @click.prevent="totalNextPage">Post</a>
                        </li>
                    </ul>
                </div>
                </template>
                <template v-else>
                <div class="callout callout-primary">
                    <h5> No se han encontrado resultados...</h5>
                </div>
                </template>
            </div>

        </div>
        </div>
    </div>
    </template>
    <div class="modal fade" :class="{ show: modalShow }" :style="modalShow ? mostrarModal : ocultarModal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ingreso de documentos de revisión</h5>
                <button class="close" @click="limpiezaInsercionDocumento"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="noPadNoMar col-md-12 form-group row">
                                <label  class="col-md-3 col-form-label">Documento de revisión</label>
                                <div class="col-md-9 container-fluid">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-file-upload"></i>
                                        </span>
                                        </div>
                                        <div class="custom-file">
                                        <input type="file" class="custom-file-input"
                                            :class="{
                                                'is-invalid': tesisParams.formatError || tesisParams.sizeError,
                                                'is-valid' : hover
                                            }"
                                            @change="getFileTesis"
                                            @mouseover="hover = true"
                                            @mouseleave="hover = false"
                                        />
                                        <label class="custom-file-label" for="input1">{{
                                            tesisFile
                                            ? tesisFile.name
                                            : "Seleccionar archivo"
                                        }}</label>
                                        </div>
                                    </div>
                                    <div class="custom-file invalid-feedback no-margin" v-show="tesisParams.formatError">
                                        El formato del archivo no es soportado.
                                    </div>
                                    <div class="custom-file invalid-feedback no-margin" v-show="tesisParams.sizeError">
                                        El tamaño del archivo no puede superar los
                                        {{ tesisParams.size }} MB.
                                    </div>
                                    <div class="container">
                                        El tamaño máximo de los archivos es: {{tesisParams.size}} MB.
                                    </div>
                                    <div class="container">
                                        Los formatos de archivo soportados son:
                                    <span v-for="item in tesisParams.types" :key="item" v-text="item +' '"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Comentario</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" v-model="fitDocumentoRevision.comentario"/>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" @click="envioDocumentoRevision">{{globVar.btnSend}}</button>
                <button class="btn btn-secondary" @click="limpiezaInsercionDocumento">{{globVar.btnClose}}</button>
            </div>
        </div>
      </div>
    </div>
</div>
</template>

<script>
import moment from "moment";
import globFunct from '../../../services/globFunct';
import globVar from '../../../services/globVar';
import Multiselect from 'vue-multiselect';
export default {
    components: { Multiselect },
    props: ['usuario'],
    data(){
    return{
        globVar: new globVar(),
        globFunct: new globFunct(),
        moment: moment,
        fitDocumentoRevision: {
            fitId: '',
            comentario: '',
            tipo: 'revision',
        },
        busquedaComision: {
            tituloFID: '',
            nombreAlumno: '',
            nombreProf: '',
            existenciaComision: { "id": 2, "valor": "Todas" },
            dateRange: {
                startDate: null,
                endDate: null
            }
        },
        listRolPermisosByUsuario: JSON.parse(localStorage.getItem('listRolPermisosByUsuario')),
        terminoTitulo: JSON.parse(localStorage.getItem('TerminoDeTitulo')),
        listAlumnos:[],
        listPermisos:[],
        listMisComisiones:[],
        listComisiones:[],
        listComisionesProvisorias: [],
        listAllComisiones:[],
        listActasGuia:[],
        listActasParticipe:[],
        listActasTotal:[],
        busquedaOpcion: [
            {id:0, valor:'Faltantes'},
            {id:1, valor:'Creadas'},
            {id:2, valor:'Todas'}
        ],
        fullscreenLoading: false,
        pageNumber: 0,
        pageNumber2: 0,
        pageNumber3: 0,
        totalPageNumber:0,
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
        mensajeError:[],
        tesisFile: '',
        tesisParams: {
            types: [],
            size: 0,
            formatError: false,
            sizeError: false
        },
        tesisForm: new FormData(),
        rolActivo: JSON.parse(localStorage.getItem('rolActivo')),
        hover: false,
        startOption: {
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
    },
    pageCount3(){
        //obtener el numero de paginas
        let a = this.listComisionesProvisorias.length,
            b = this.perPage;
      return Math.ceil(a / b);
    },
    listarComisionesPaginated3(){
        let inicio = this.pageNumber3 * this.perPage,
        fin = inicio + this.perPage;
        return this.listComisionesProvisorias.slice(inicio, fin);
    },
    pagesList3(){
      let a = this.listComisionesProvisorias.length,
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
    totalPageCount(){
        //obtener el numero de paginas
        let a = this.listAllComisiones.length,
            b = this.perPage;
        return Math.ceil(a / b);
    },
    listarComisionesTotalesPaginated() {
        let inicio = this.totalPageNumber * this.perPage,
        fin = inicio + this.perPage;
        return this.listAllComisiones.slice(inicio, fin);
    },
    totalPagesList(){
        let a = this.listAllComisiones.length,
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
  },
  mounted(){
    EventBus.$emit('navegar', 'Comisiones');
    EventBus.$on('refresh', x => {this.init()});
    this.init();
  },
  beforeDestroy(){
    EventBus.$off('refresh');
  },
  methods:{
    init(){
        this.comisionesByRol();
        this.getParametros();
        this.selectStart();
    },
    comisionesByRol() {
        if (this.rolActivo == 'Profesor') {
            this.getListarMisComisiones();
            this.getListarComisiones();
            this.getListarComisionesProvisorias();
        } else {
            this.getListarTodasComisiones();
        }
    },
    getParametros() {
        var url = "/admin/parametros";
        axios
        .post(url, { params: ['AvancesTesisSize', 'AvancesTesisFormato'] })
        .then((response) => {
          this.tesisParams.size  = response.data[0][0];
          this.tesisParams.types   = response.data[1];
        });
    },
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
        var url = '/comisiones/getListarMisComisiones';
        console.log(this.busquedaComision);
        axios.get(url, {
            params: {
                bComision: this.busquedaComision.existenciaComision.id,
                cTitulo: this.busquedaComision.tituloFID,
                cAlum: this.busquedaComision.nombreAlumno,
                startDate: this.busquedaComision.dateRange.startDate,
                endDate: this.busquedaComision.dateRange.endDate
            }
        }).then(response => {
            this.inicializarPaginacion();
            this.listMisComisiones = response.data;
            console.log('guia',this.listMisComisiones)
            this.fullscreenLoading = false;
        })
    },
    getListarComisiones(){
        this.fullscreenLoading = true;
        var url = '/comisiones/getListarComisiones';
        axios.get(url, {
            params: {
                cTitulo:    this.busquedaComision.tituloFID,
                cAlum:      this.busquedaComision.nombreAlumno,
                startDate:  this.busquedaComision.dateRange.startDate,
                endDate:    this.busquedaComision.dateRange.endDate,
                estado:     'Definitiva'
            }
        }).then(response => {
            this.inicializarPaginacion2();
            this.listComisiones = response.data;
            console.log('pertenezco definitivo',this.listComisiones);
            this.fullscreenLoading = false;
        })
    },
    getListarComisionesProvisorias(){
        this.fullscreenLoading = true;
        var url = '/comisiones/getListarComisiones';
        axios.get(url, {
            params: {
                cTitulo:    this.busquedaComision.tituloFID,
                cAlum:      this.busquedaComision.nombreAlumno,
                startDate:  this.busquedaComision.dateRange.startDate,
                endDate:    this.busquedaComision.dateRange.endDate,
                estado:     'Provisoria'
            }
        }).then(response => {
            this.inicializarPaginacion3();
            this.listComisionesProvisorias = response.data;
            console.log('pertenezco provisoriamente', this.listComisionesProvisorias);
            this.fullscreenLoading = false;
        })
    },
    getListarTodasComisiones() {
      this.fullscreenLoading = true;
      var url = '/comisiones/getListarTodasComisiones';
      axios.get(url, {
      }).then(response => {
          this.inicializarPaginacionTotal();
          this.listAllComisiones = response.data;
          this.fullscreenLoading = false;
      })
    },
    getListarComisionesByParametros() {
      this.fullscreenLoading = true;
      var url = '/comisiones/getListarComisionesByParametros';
      axios.get(url, {
        params: {
            bComision: this.busquedaComision.existenciaComision.id,
            cTitulo: this.busquedaComision.tituloFID,
            cProf: this.busquedaComision.nombreProf,
            cAlum: this.busquedaComision.nombreAlumno,
            startDate: this.busquedaComision.dateRange.startDate,
            endDate: this.busquedaComision.dateRange.endDate
        }
      }).then(response => {
          this.inicializarPaginacionTotal();
          this.listAllComisiones = response.data;
          console.log(this.listAllComisiones)
          this.fullscreenLoading = false;
      });
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
    nextPage3(){
      this.pageNumber3++;
    },
    prevPage3(){
      this.pageNumber3--;
    },
    selectPage3(page){
      this.pageNumber3 = page;
    },
    inicializarPaginacion3(){
      this.pageNumber3 = 0;
    },
    totalNextPage(){
      this.pageNumber2++;
    },
    totalPrevPage(){
      this.pageNumber2--;
    },
    totalSelectPage(page){
      this.pageNumber2 = page;
    },
    inicializarPaginacionTotal(){
      this.pageNumber2 = 0;
    },
    descargarDocumento (id) {
        var url = '/comisiones/pathDocumentoComision';
        this.fullscreenLoading = true;
        axios.get(url, {
            params: {
            'id' : id
            }
        }).then(response => {
            this.fullscreenLoading = false;
            location.href=response.data.path;
        });
    },
    modalInsercionDocumento(fit) {
        // console.log(fit);
        this.modalShow = !this.modalShow;
        this.fitDocumentoRevision.fitId = fit.id;
    },
    limpiezaInsercionDocumento() {
        this.fitDocumentoRevision.id = null;
        this.fitDocumentoRevision.comentario = null;
        this.tesisForm = new FormData();
        this.modalShow = !this.modalShow;
    },
    envioDocumentoRevision() {
        this.fullscreenLoading = true;
        const url = '/comisiones/setRegistrarDocumentoComision';
        this.tesisForm.append("file", this.tesisFile);
        this.tesisForm.append("id_fit", this.fitDocumentoRevision.fitId);
        this.tesisForm.append("tipo", this.fitDocumentoRevision.tipo);
        this.tesisForm.append("comentario", this.fitDocumentoRevision.comentario);
        const config = { headers: { "Content-Type": "multipart/form-data" } };
        axios.post(url, this.tesisForm, config)
        .then(response => {
            this.fullscreenLoading = false;
            Swal.fire({
                icon: "success",
                title: "Documento ingresado correctamente",
                showConfirmButton: false,
                timer: 2000,
            });
            this.limpiezaInsercionDocumento();
        }).catch((response) => {
            // console.log(response)
            this.fullscreenLoading = false;
            Swal.fire({
                icon: "error",
                title: "Error al ingresar documento",
                showConfirmButton: false,
                timer: 2000,
            });
        });
    },
    getFileTesis (element) {
      this.tesisParams.formatError = false;
      this.tesisParams.sizeError = false;
      this.tesisFile = element.target.files[0];
      if (!this.tesisFile) return;
      const fileName = this.tesisFile.name;
      const fileSize = this.tesisFile.size;
      var dots = fileName.split(".");
      var fileType = "." + dots[dots.length - 1];
      if (this.tesisParams.types.join(".").indexOf(fileType) == -1) {
        this.tesisParams.formatError = true;
      }
      if (fileSize >= this.tesisParams.size * 1000000) {
        this.tesisParams.sizeError = true;
      }
    },
    getDocumento(idTesis){
        this.fullscreenLoading = true;
        var url = '/archivo/getArchivo';
        axios.get(url,{
            params: {
                'tipo'    : 'acta',
                'fit'     : idTesis
            }
        }).then(response => {
            this.fullscreenLoading = false;
            location.href = response.data.path;
        });
    },
    descargaActaZip (fit, tipo){
        var url = '/comisiones/descargaActaZip';
        if (fit[0].fit) fit = fit.map(x=>{return x.fit;}); // Revisa si tiene el objeto fit o comision y fuerza a ser fit
        var arrayComisiones = fit.map(x=>{ // obtengo id de acta de archivoPDF
            if(x.archivoActa.length > 0) return x.archivoActa[0].id;
        });
        arrayComisiones = arrayComisiones.filter(n => n); // limpieza de datos indefinidos
        var lista = {
            nIdArchivo: arrayComisiones,
            cTipo: tipo
        };
        let postConfig = {
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            responseType: 'blob',
        }
        axios.post(url,
        lista,
        postConfig
        ).then(response => {
            var oMyBlob = new Blob([response.data], {type : 'application/zip'});
            var url = document.createElement('a')
            url.href = URL.createObjectURL(oMyBlob);
            url.download = tipo+'.zip';
            url.click();
        });
    },
    selectStart () {
        this.busquedaComision.dateRange.endDate = null;
        this.endOption = {
            disabledDate: (time) => {
                return time.getTime() < Date.parse(this.busquedaComision.dateRange.startDate) || time.getTime() > Date.now();
            }
        };
    },
    eliminarComision (item) {
        const url = '/comisiones/borrarComision';
        axios
        .post(url, item)
        .then(response => {
            Swal.fire({
                icon: "success",
                title: response.data.msg,
                showConfirmButton: false,
                timer: 2000,
            });
            this.getListarMisComisiones();
        });
    }
  }//cierre de methods
}
</script>

<style>
  .scrollTable{
    max-height: 350px !important;
    overflow: auto !important;
  }
</style>
