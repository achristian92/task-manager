<template>
    <button @click.prevent="create" type="button" href="#" class="btn btn-sm btn-primary btn-icon">
        <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
        <span class="btn-inner--text">Nueva</span>
    </button>
</template>

<script>
import EventBus from "../../../event-bus";


export default {
    data() {
        return {}
    },

    methods: {
        create() {
            EventBus.$emit('ev-tag-open', {});
        },
    }
}
</script>

<style scoped>

</style>
