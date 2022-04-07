<template>
    <form @submit.prevent="formFilter">
        <loading :active.sync="isLoading" :is-full-page="false"></loading>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label text-muted">Usuario</label>
                    <Select2 v-model="userSelected"
                             :options="users"
                             @change="getListCustomers"
                             placeholder="Seleccionar" required />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-control-label text-muted">Clientes</label>
                    <Select2 v-model="customerSelected"
                             :options="customers"
                             placeholder="Todos..."/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-control-label text-muted"
                           for="filterActivityStatus">
                        Estados
                    </label>
                    <select class="form-control form-control-sm"
                            id="filterActivityStatus"
                            v-model="statusSelected">
                        <option value="">Todos...</option>
                        <option v-for="(status,key) in statuses" :value="key">{{status}}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <button type="submit"
                        class="btn btn-sm btn-outline-default btn-block"
                        style="margin-top: 30px">
                    Filtrar
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import axios from "axios";
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import moment from 'moment'
import EventBus from '../../../../event-bus'
import Select2 from 'v-select2-component';

export default {
    components: {
        Loading,
        Select2
    },
    data() {
        return {
            isLoading         : false,
            userSelected      : '',
            customerSelected  : '',
            statusSelected    : '',
            customers         : [],
            currentDate       : moment().format('YYYY-MM-DD'),
        }
    },
    created() {
        EventBus.$on('resetFilter', data => {
            this.customerSelected  = ''
            this.statusSelected = ''
        });
    },
    props: {
        users    : '',
        statuses : '',
        view     : ''
    },
    watch: {
        userSelected: function () {
            this.customerSelected  = ''
            this.statusSelected = ''
            EventBus.$emit('selectedAnotherUser', {});
        }
    },
    methods: {
        formFilter() {
            const filter = {
                'user_id'      : this.userSelected,
                'customer_id'  : this.customerSelected,
                'status_id'    : this.statusSelected,
                'current_date' : this.currentDate
            }
            EventBus.$emit('ev-workplans-filters', filter);
        },
        getListCustomers() {
            this.isLoading = true
            axios.get(`${this.appUrl}api/users/${this.userSelected}/customers`).then(res =>{
                this.isLoading = false
                this.customers = res.data.customers
            });
        },
    }
}
</script>

<style scoped>

</style>
