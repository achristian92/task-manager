<template>
    <div class="modal fade" id="ImportWorkPlanModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <loading :active.sync="isLoading" :is-full-page="false"></loading>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">IMPORTAR PLAN DE TRABAJO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="custom-file">
                        <input id="logo"
                               type="file"
                               class="custom-file-input"
                               @change="onFileChange"
                               ref="fileInput"
                               required>
                        <label for="logo" class="custom-file-label">...</label>
                    </div>
                    <br><br>
                    <div class="alert alert-secondary">
                        Descargar información de clientes y etiquetas
                        <a href="" @click="downloadMatriz">Click aqui!</a>
                    </div>
                    <div class="alert alert-secondary">
                        Descargar plantilla para importar plan de trabajo
                        <a href="" @click="downloadTemplate">Click aqui!</a>
                    </div>
                    <ul>
                        <li v-for="error in errors"><span class="text-sm text-danger">{{error[0]}}</span></li>
                    </ul>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary" @click="uploadFile">Guardar</button>
                    <button type="button" class="btn btn-sm btn-outline-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import axios from "axios";
import Loading from 'vue-loading-overlay'
import EventBus from "../../../../event-bus";

export default {
    name: "ModalImportPlanned",
    components: {
        Loading,
    },
    data() {
        return {
            errors      : [],
            import_file : '',
            isLoading   : false,
            view        : 'calendar'
        }
    },
    created() {
        EventBus.$on('importFrom',data => this.view = data.view)
    },
    methods: {
        onFileChange(e) {
            e.preventDefault();
            this.import_file = e.target.files[0];
        },
        uploadFile() {
            this.isLoading = true
            let formData = new FormData();
            formData.append('file_upload', this.import_file);
            formData.append('view', this.view);

            let url = `${this.appUrl}api/my-workplans/import`
            axios.post(url, formData, {
                headers: { 'content-type': 'multipart/form-data' }
            })
                .then(res => {
                    this.isLoading = false
                    this.reset()
                    this.import_file = ''

                    if(res.status !== 201) {
                        Vue.$toast.error("Inténtalo de nuevo");
                        return;
                    }

                    Vue.$toast.success(res.data.msg);
                    $('#ImportWorkPlanModal').modal('hide');
                    if (res.data.view === 'calendar') {
                        EventBus.$emit('refreshEvents', {});
                    } else {
                        EventBus.$emit('refreshList', {});
                    }
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 422){
                        this.reset()
                        this.errors = error.response.data.errors;
                    }
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });

        },
        downloadMatriz(e) {
            e.preventDefault();
            Vue.$toast.success("Descargando...");
            e.preventDefault();
            window.location =  `${this.appUrl}api/my-workplans/matriz`
        },
        downloadTemplate(e) {
            e.preventDefault();
            Vue.$toast.success("Descargando...");
            e.preventDefault();
            window.location =  `${this.appUrl}api/my-workplans/template`
        },
        reset() {
            const input = this.$refs.fileInput
            input.type = 'text'
            input.type = 'file'
        }
    }
}
</script>

<style scoped>

</style>
