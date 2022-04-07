<template>
    <div class="table-responsive">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <table class="table align-items-center table-flush table-hover border-bottom-0" id="dtTags">
            <thead class="thead-light">
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="list">
            <tr v-for="tag in tags">
                <td>
                    <span class="name mb-0 text-sm">
                        <i class="ni ni-tag mr-2" :style="'color:'+ tag.color"></i>
                        {{tag.name}}
                    </span>
                </td>
                <td>
                    <span v-if="tag.is_active"  class='badge badge-success'>Activo</span>
                    <span v-else class='badge badge-danger'>Inactivo</span>
                </td>
                <td class="text-right">
                    <a href="#" @click.prevent="edit(tag.id)">
                        <img :src="appUrl+'img/icons/edit.png'" class="ml-2" title="Editar producto" alt="icon edit">
                    </a>

                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                            <a class="dropdown-item" href="" @click.prevent="destroy(tag.id)">
                                <i class="fas fa-trash-alt text-danger"></i>
                                <span>Eliminar</span>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>
        <br>
    </div>
</template>

<script>
import axios from 'axios'
import EventBus from "../../../event-bus";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Vue from "vue";

export default {
    components: { Loading },
    data() {
        return {
            isLoading: false,
            tags: [],
        }
    },
    props: ['p_tags'],
    created() {
        if (this.p_tags)
            this.tags = this.p_tags
    },
    methods: {
        edit(id) {
            this.isLoading = true
            axios.get(`${this.appUrl}api/admin/tags/${id}/edit`)
                .then(res => {
                    this.isLoading = false
                    EventBus.$emit('ev-tag-edit', {tag:res.data.tag});
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        destroy(id) {
            this.isLoading = true
            axios.delete(`${this.appUrl}api/admin/tags/${id}`)
                .then(res => {
                    this.isLoading = false
                    Vue.$toast.success(res.data.msg);
                    $('#ModalTag').modal('hide');
                    setTimeout(() => {
                        window.location.href = res.data.route;
                    }, 1000)
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
    }
}
</script>

<style scoped>

</style>
