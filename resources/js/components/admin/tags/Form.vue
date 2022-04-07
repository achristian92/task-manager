<template>
    <div class="modal fade" id="ModalTag" tabindex="-1" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Etiqueta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" @submit.prevent="submit">
                    <loading :active.sync="isLoading" :is-full-page="false"></loading>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10">
                                    <label class="form-control-label" for="nametagInput">Nombre de la etiqueta</label>
                                    <input type="text" class="form-control" id="nametagInput" v-model="formData.name" required>
                                    <div v-if="errors && errors.name" class="h6 text-danger">{{ errors.name[0] }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form__field">
                                        <div class="form__input">
                                            <v-swatches v-model="formData.color" popover-x="left" style="margin-top: 32px;"></v-swatches>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-outline-default  ml-auto" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import EventBus from '../../../event-bus';
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import VSwatches from 'vue-swatches'
import 'vue-swatches/dist/vue-swatches.css'


export default {
    components: { VSwatches,Loading },
    data() {
        return {
            isLoading: false,
            toUpdate: false,
            formData: {
                id    : '',
                name  : '',
                color : '#dde5e8'
            },
            errors: []
        }
    },
    created() {
        EventBus.$on('ev-tag-open', data => this.open());
        EventBus.$on('ev-tag-edit', data => this.edit(data));
    },
    methods: {
        submit() {
            console.log("enviando...")
            this.isLoading = true

            let data = new FormData;
            data.append('name', this.formData.name)
            data.append('color', this.formData.color)

            let url = `${this.appUrl}api/admin/tags`

            if (this.toUpdate) {
                data.append('_method', 'PUT')
                url = `${this.appUrl}api/admin/tags/${this.formData.id}`
            }

            axios.post(url,data)
                .then(res => {
                    console.log(res.data)
                    this.isLoading = false
                    $('#ModalTag').modal('hide');
                    Vue.$toast.success(res.data.msg);
                    setTimeout(() => {
                        window.location.href = res.data.route;
                    }, 1000)
                })
                .catch(error => {
                    if (error.response.status === 422){
                        this.errors = error.response.data.errors;
                        Vue.$toast.error("Información inválida");
                    }
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },

        open() {
            this.clear()
            $('#ModalTag').modal('show');
        },
        edit(data) {
            this.toUpdate = true
            this.formData = {
                id    : data.tag.id,
                name  : data.tag.name,
                color : data.tag.color
            }

            $('#ModalTag').modal('show');
        },
        clear() {
            this.formData = {
                id    : '',
                name  : '',
                color : '#dde5e8'
            }
            this.errors = []
        },
    }
}
</script>

<style scoped>

</style>
