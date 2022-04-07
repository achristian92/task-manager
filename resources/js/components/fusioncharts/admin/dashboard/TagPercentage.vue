<template>
    <div class="div">
        <fusioncharts
            :type="type"
            :width="width"
            :height="height"
            :dataFormat="dataFormat"
            :dataSource="dataSource"
        ></fusioncharts>
    </div>
</template>

<script>
export default {
    data() {
        return {
            type       : "pie3d",
            width      : "100%",
            height     : "400",
            dataFormat : "json",
            dataSource : {},
        }
    },
    props: ['p_percentage'],
    created() {
        if (this.p_percentage) {
            this.dataSource = {
                chart: {
                    caption: "División porcentual por etiquetas",
                    subcaption: "(Total Horas)",
                    showvalues: "1",
                    showpercentintooltip: "0",
                    enablemultislicing: "1",
                    theme: "fusion"
                },
                data: this.p_percentage
            }
        }

    },
}
</script>

<style scoped>

</style>
