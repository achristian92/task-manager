<template>
    <div class="modal fade" id="TimeWorkedByDayModal" role="dialog" aria-labelledby="edit-event-label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <loading :active.sync="isLoading" :is-full-page="false"></loading>
                <div class="modal-body">
                    <div class="row">
                        <div class="col text-center">
                            <h4 class="">TIEMPO TRABAJO POR DÍA</h4>
                        </div>
                    </div>
                    <form class="form" @submit.prevent="handleExport">
                        <div class="form-group">
                            <label for="userTimeWorkedByDay" class="form-control-label">Usuario</label>
                            <Select2 v-model="user_id"
                                     :options="users"
                                     placeholder="Seleccionar"
                                     required />
                            <div v-if="errors && errors.user_id" class="h6 text-danger">{{ errors.user_id[0] }}</div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="monthTimeWorkedByDay" class="form-control-label">Mes</label>
                                    <input type="month"
                                           class="form-control form-control-sm"
                                           id="monthTimeWorkedByDay"
                                           v-model="yearAndMonth">
                                    <div v-if="errors && errors.yearAndMonth" class="h6 text-danger">{{ errors.yearAndMonth[0] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-sm">Exportar</button>
                            </div>
                            <div class="col text-right">
                                <button class="btn btn-sm btn-outline-default ml-auto" data-dismiss="modal">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import axios from 'axios'
import moment from 'moment'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import Select2 from 'v-select2-component';

export default {
    components: {
        Loading,
        Select2
    },
    data() {
        return {
            isLoading    : false,
            user_id      : '',
            yearAndMonth : moment().startOf('month').format('YYYY-MM'),
            counters     : [],
            errors       : []
        }
    },
    props: ['p_users'],
    created() {
        if (this.p_users) this.users = this.p_users;
        if (this.users.length === 1) this.user_id = this.users[0].id;
    },
    methods: {
        handleExport: function () {
            this.isLoading = true
            axios.get(`${this.appUrl}api/reports/users/time-worked-by-day`,{
                params: {
                    'user_id'      : this.user_id,
                    'yearAndMonth' : this.yearAndMonth,
                }
            }).then(res => {
                this.isLoading = false
                if (res.status !== 201) {
                    Vue.$toast.warning("Inténtelo de nuevo");
                    return;
                }

                this.reset()
                Vue.$toast.success(res.data.msg);
                setTimeout(function(){window.open(res.data.url)},2000);
                setTimeout(function(){$('#TimeWorkedByDayModal').modal('hide');},4000);
            }).catch(error => {
                this.isLoading = false
                if (error.response.status === 422){
                    Vue.$toast.error("Información inválida");
                    this.errors = error.response.data.errors;
                }
                if (error.response.status === 401) {
                    Vue.$toast.error(error.response.data.msg);
                }
            });
        },
        reset: function ()
        {
            this.user_id      = ''
            this.yearAndMonth = moment().startOf('month').format('YYYY-MM')
            this.errors       = []
        }
    }
}
</script>

<style scoped>

</style>
