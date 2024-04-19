<template>
    <div class="modal fade" id="customerFileFormModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir Archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <form @submit.prevent="submitFile">
                    <loading :active.sync="isLoading" :is-full-page="false"></loading>
                    <div class="modal-body">
<!--                        <validation-errors :errors="errors" v-if="errors"></validation-errors>-->
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" class="form-control" v-model="name">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="ismain"
                                   v-model="is_main">
                            <label class="form-check-label" for="ismain">Prioridad Alta</label>
                        </div>
                        <div class="form-group">
                            <label >Cargar archivo</label>
                            <input type="file" class="form-control-file" @change="onFileChange">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-link" data-dismiss="modal">Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary" :disabled="disableSubmit">Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import axios from 'axios'

export default {
    components: {
        Loading,
    },
    data() {
        return {
            disableSubmit: false,
            isLoading : false,
            name      : '',
            is_main   : false,
            fileObject : '',
            errors    : [],
        }
    },
    props: ['p_customer_id'],
    created() {
        console.log(this.p_customer_id)
    },
    methods: {

        submitFile() {
            this.disableSubmit = true
            this.errors = []
            this.isLoading = true

            let data = new FormData()

            if (this.fileObject)
                data.append('attachment_file', this.fileObject)

            data.append('name', this.name)
            data.append('is_main', this.is_main)

            axios.post(`${this.appUrl}api/admin/customers/${this.p_customer_id}/files`, data,{
                headers: { 'content-type': 'multipart/form-data' }
            }).
            then(res => {
                this.isLoading = false
                Vue.$toast.success(res.data.msg)
                setTimeout(() => {
                    window.location.href = res.data.route;
                }, 1000)
            }).
            catch(error => {
                this.disableSubmit = false
                this.isLoading = false
                if (error.response.status === 422){
                    this.errors = error.response.data.errors;
                    Vue.$toast.error("Información inválida");
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        onFileChange: function(event) {
            let input = event.target;
            if (input.files && input.files[0])
                this.fileObject = input.files[0];

        },

    }
}
</script>

<style scoped>

</style>
