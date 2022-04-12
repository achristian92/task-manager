
// require('./bootstrap');


window.Vue = require('vue');

import 'fullcalendar/dist/fullcalendar.css';
import FullCalendar from 'vue-full-calendar';
Vue.use(FullCalendar);
//
import * as VueFusionCharts from 'vue-fusioncharts';
import * as FusionCharts from 'fusioncharts';
import Column2D from 'fusioncharts/fusioncharts.charts';
import * as FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion';
import * as Widgets from "fusioncharts/fusioncharts.widgets.js";
import TreeMap from 'fusioncharts/fusioncharts.treemap';
import * as Gantt from "fusioncharts/fusioncharts.gantt.js";
Vue.use(VueFusionCharts, FusionCharts, Column2D, FusionTheme,Widgets,TreeMap,Gantt);

import VueToast from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
Vue.use(VueToast)


Vue.component('progress-fusionchart', require('./components/fusioncharts/components/Progress').default);
Vue.component('resume-fusionchart', require('./components/fusioncharts/components/Resume').default);
Vue.component('customers-more-hours', require('./components/fusioncharts/admin/dashboard/CustomersMoreHours').default);
Vue.component('customers-less-hours', require('./components/fusioncharts/admin/dashboard/CustomersLessHours').default);
Vue.component('users-more-hours', require('./components/fusioncharts/admin/dashboard/UsersMoreHours').default);
Vue.component('users-less-hours', require('./components/fusioncharts/admin/dashboard/UsersLessHours').default);
Vue.component('tags-history', require('./components/fusioncharts/components/TagHistory').default);
Vue.component('tags-percentage', require('./components/fusioncharts/admin/dashboard/TagPercentage').default);
Vue.component('customers-compare', require('./components/fusioncharts/admin/dashboard/Compare').default);
Vue.component('customers-compare-activities', require('./components/fusioncharts/admin/dashboard/CompareActivities').default);
Vue.component('customers-compare-hours', require('./components/fusioncharts/admin/dashboard/CompareHours').default);
Vue.component('customers-history-hours', require('./components/fusioncharts/admin/dashboard/CustomerHistoryHours').default);
Vue.component('customers-compare', require('./components/fusioncharts/admin/dashboard/Compare').default);

Vue.component('tags-btn-add', require('./components/admin/tags/BtnAdd.vue').default);
Vue.component('tags-list', require('./components/admin/tags/List.vue').default);
Vue.component('tags-form', require('./components/admin/tags/Form.vue').default);

Vue.component('workplans-show', require('./components/admin/workplans/Show.vue').default);
Vue.component('workplans-filters', require('./components/admin/workplans/partials/Filters.vue').default);
Vue.component('workplans-resume', require('./components/admin/workplans/partials/Resume.vue').default);

Vue.component('activity-form', require('./components/shared/activities/Form').default);
Vue.component('activity-show', require('./components/shared/activities/Show').default);

Vue.component('activity-finish', require('./components/users/imbox/partials/finish').default);
Vue.component('activity-sub-form', require('./components/users/imbox/partials/SubForm').default);
Vue.component('activity-btn-add', require('./components/users/imbox/partials/BtnAdd').default);
Vue.component('activity-new-form', require('./components/users/imbox/partials/NewForm').default);


Vue.component('admin-imbox-evaluate', require('./components/admin/imbox/EvaluationTable').default);
Vue.component('admin-imbox-basic', require('./components/admin/imbox/GeneralTable').default);


Vue.component('workplans-user-show', require('./components/users/workplans/Show.vue').default);
Vue.component('workplans-user-filters', require('./components/users/workplans/partials/Filters.vue').default);
Vue.component('workplans-user-actions', require('./components/users/workplans/partials/Actions.vue').default);
Vue.component('workplans-user-import', require('./components/users/workplans/partials/Import.vue').default);
Vue.component('workplans-user-mass-delete', require('./components/users/workplans/partials/MassDelete.vue').default);
Vue.component('workplans-user-duplicate', require('./components/users/workplans/partials/Duplicate.vue').default);

Vue.component('user-imbox-basic', require('./components/users/imbox/GeneralTable').default);


Vue.component('report-users', require('./components/reports/users/Index').default);
Vue.component('report-users-plannedvsreal', require('./components/reports/users/PlannedVsReal').default);
Vue.component('report-users-totalhourscustomers', require('./components/reports/users/TotalHoursCustomers').default);
Vue.component('report-users-totalhoursdays', require('./components/reports/users/TotalHoursDays').default);

Vue.component('report-customers', require('./components/reports/customers/Index').default);
Vue.component('report-customers-totalhoursdays', require('./components/reports/customers/TotalHoursDays').default);
Vue.component('report-customers-listusers', require('./components/reports/customers/ListUsers').default);
Vue.component('report-customers-history', require('./components/reports/customers/History').default);
Vue.component('report-customers-tag', require('./components/reports/customers/ByTags').default);

Vue.component('report-activities', require('./components/reports/activities/Index').default);
Vue.component('report-activities-list', require('./components/reports/activities/List').default);





import auth from './mixins/auth'
Vue.mixin(auth);


const app = new Vue({
    el: '#app',
});
