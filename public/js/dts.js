$(document).ready( function () {
    const dts = [
        "dtCustomers",  "dtUsers", "dtTags","dtImboxBasic", "dtUserHistory", "dtDocuments", "dtTracks"
    ];
    dts.forEach(function (dtable) {
        $('#'+dtable).DataTable({
            "order": [0,'desc'],
            "dom": '<"top"fl>rt<"bottom"ip>',
            language: {
                "url": srclangdt
            },
        });
    })

});
