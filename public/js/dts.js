$(document).ready( function () {
    const dtsasc = [
        "dtCustomers",  "dtUsers", "dtTags","dtImboxBasic","dtTracks",
    ];
    dtsasc.forEach(function (dtascable) {
        $('#'+dtascable).DataTable({
            "order": [0,'asc'],
            "dom": '<"top"fl>rt<"bottom"ip>',
            language: {
                "url": srclangdt
            },
        });
    })

    const dtsdesc = [
       "dtUserHistory", "dtUserDocument",
    ];
    dtsdesc.forEach(function (dtable) {
        $('#'+dtable).DataTable({
            "order": [0,'desc'],
            "dom": '<"top"fl>rt<"bottom"ip>',
            language: {
                "url": srclangdt
            },
        });
    })

});
