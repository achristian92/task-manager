<template>
    <table class="table align-items-center table-flush border-bottom-0" id="dtImboxBasic">
        <thead class="thead-light">
        <tr>
            <th style="width: 10px;">Fecha</th>
            <th>Actividad | Cliente</th>
            <th>Estado</th>
            <th>Planeado</th>
            <th>Duración</th>
            <th class="text-right">Acciones</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(activity,index) in activities">

            <td style="width: 10px;">{{ activity.startDateShort }}</td>
            <td>
                <a class="font-weight-bold">{{ activity.activityNameShort }}</a>
                <span v-if="!activity.isPlanned" class="text-success ml-2">(Nuevo)</span>
                <br>
                <span class="h6 text-muted">
                        <i class="far fa-building mr-1"></i> {{activity.customerNameShort}} |
                        <i class="far fa-user mr-1 ml-1"></i> {{activity.userNameShort}} |
                        <i class="far fa-flag mr-1 ml-1"></i>
                        <span v-if="activity.isPriority">Alta</span>
                        <span v-else>Normal</span>
                        <span v-if="activity.qtySubActivities > 0">
                          |  <i class="fas fa-cogs mr-1 ml-1" ></i> {{activity.qtySubActivities}}
                        </span>
                        <span v-if="activity.qtyDependencies > 0">
                          |  <i class="fas fa-code-branch mr-1 ml-1" ></i> {{activity.qtyDependencies}}
                        </span>
                        <span v-if="activity.tagId">
                            <i class="ni ni-tag ml-2 mr-1" :style="'color:'+activity.tagColor"></i>
                            {{ activity.tagName }}
                        </span>
                    </span>
            </td>
            <td>
                <button type="button" :class="'btn btn-sm '+activity.statusColorBtnOutline">
                    {{ activity.statusName }}
                    <span v-show="activity.status === 'partial'"><i class="fas fa-bell ml-1 text-yellow"></i></span>
                </button>
            </td>
            <td>
                {{ activity.estimatedTime }} h
            </td>
            <td>
                <div>{{ activity.realTime }} h</div>

            </td>

            <td class="text-right">
                <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a @click.prevent="show(activity.id)"
                           class="dropdown-item"
                           href="">
                            <span>Detalle</span>
                        </a>
                        <a v-show="activity.canDestroy"
                           class="dropdown-item"
                           href=""
                           @click.prevent="destroy(activity.id)">
                            <span>Eliminar</span>
                        </a>
                    </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import axios from "axios";
import EventBus from "../../../event-bus";

export default {
    name: "Today",
    data() {
        return {
            activities : [],
        }
    },
    props:['p_activities'],
    created() {
        if (this.p_activities)
            this.activities = this.p_activities
    },
    methods: {
        show: function (activity_id) {
            this.isLoading = true
            axios.get(`${this.appUrl}api/activities/${activity_id}`)
                .then(res => {
                    this.isLoading = false
                    EventBus.$emit('ev-activity-show', res.data);
                })
                .catch(error => {
                    this.isLoading = false
                    if (error.response.status === 401) {
                        Vue.$toast.error(error.response.data.msg);
                    }
                });
        },
        destroy: function (activity_id) {
            let isConfirmed = confirm("Estas seguro de eliminar esta actividad?")
            if(!isConfirmed) {return false;}

            axios.delete(`${this.appUrl}api/activities/${activity_id}`)
                .then(res => {
                    let indexInActivitiesData = this.activities.findIndex(i => i.id === activity_id);
                    if (indexInActivitiesData !== undefined) this.activities.splice(indexInActivitiesData, 1);
                    Vue.$toast.success(res.data.msg);
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
