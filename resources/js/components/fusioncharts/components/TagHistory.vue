<template>
    <div class="div">
        <fusioncharts
            :type="typePeriod"
            :width="width"
            :dataFormat="dataFormat"
            :dataSource="dataSource"
        ></fusioncharts>
    </div>
</template>

<script>
export default {

    data() {
        return {
            typePeriod : "msline",
            width      : "100%",
            dataFormat : "json",
            dataSource : {},
        }
    },
    props: ['p_history'],
    created() {
        if (this.p_history)
            this.dataSource = {
                chart: {
                    caption: "Tendencia por etiquetas",
                    subcaption: "(mensual)",
                    yAxisName: "Total Horas",
                    drawcrossline: "1",
                    theme: "fusion"
                },
                categories: [
                    {
                        "category": this.p_history.categories
                    }
                ],
                dataset: this.p_history.dataset
            }
    },
}
</script>

<style scoped>

</style>
