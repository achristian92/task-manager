<template>
    <div class="card">
        <loading :active.sync="isLoading" :is-full-page="true"></loading>
        <div class="card-body">
            <br>
            <div class="row">
                <div class="col-md-10">
                    <multiselect v-model="customersSelected"
                                 :options="customers"
                                 :multiple="true"
                                 :close-on-select="false"
                                 :clear-on-select="false"
                                 :preserve-search="true"
                                 placeholder="Seleccionar..."
                                 label="name"
                                 :max="5"
                                 track-by="name">
                        <template slot="selection" slot-scope="{ values, search, isOpen }">
                                    <span class="multiselect__single"
                                          v-if="values.length &amp;&amp; !isOpen">
                                        {{ values.length }} seleccionado
                                    </span>
                        </template>
                    </multiselect>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-secondary mt-1" @click="handleClickFilterCustomers">Filtrar</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <customers-compare-activities :activities="barActivities"></customers-compare-activities>
                </div>
                <div class="col-md-6">
                    <customers-compare-hours :hours="barHours"></customers-compare-hours>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <customers-history-hours :line="line"></customers-history-hours>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import Multiselect from 'vue-multiselect'
import moment from 'moment'
moment.locale('es')

export default {
    components: {
        Loading,
        Multiselect,
    },
    data() {
        return {
            isLoading     : false,
            barActivities : null,
            barHours      : null,
            line          : null,
            yearAndMonth  : moment().startOf('month').format('YYYY-MM'),
            firstLoadPage : true,
            customers     : [],
            customersSelected: [],
        }
    },
    props: ['c_customers','p_yearandmonth'],
    created() {
        if (this.c_customers) this.customers = this.c_customers;
        if (this.p_yearandmonth) this.yearAndMonth = this.p_yearandmonth

    },

    methods: {
        getInfo: function ()
        {
            let customer_ids = this.customersSelected.map(function(a) {return a.id;});
            this.isLoading = true
            axios.get(`${this.appUrl}api/dashboard/compare`,{
                params: {
                    customer_ids : customer_ids,
                    yearAndMonth : this.yearAndMonth,
                }
            })
                .then(res => {
                    this.isLoading         = false

                    this.barActivities     = res.data.activities
                    this.barHours          = res.data.hours
                    this.line              = res.data.period
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        handleClickFilterCustomers ()
        {
            this.getInfo();
        },

    }
}
</script>

<style scoped>

</style>
