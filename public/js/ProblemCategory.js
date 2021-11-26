$(document).on('click', '#goBtn', function () {
    $('#loading').show();
    window.location.href = '/dash/pcategory/'+$('#datefrom').val()+'/'+$('#dateto').val();
});
var ProblemCatSoftTable, ProblemCatHardTable, ProblemCatInfraTable, CategoryTable;
$(document).ready(function()
{ 
    if (datefrom == 'default') {
        datefrom = $('#dfrom').val().replaceAll('-', '/');
        dateto= $('#dto').val().replaceAll('-', '/');
    }
    CategoryTable =
        $('table.CategoryTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": "No data!",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 50,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/pcatdata',
                data: {
                    datefrom: datefrom,
                    dateto: dateto
                },
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'ProblemCategory', name:'ProblemCategory'},
                { data: 'Total', name:'Total'},
                { data: 'percentage', name:'percentage'}
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                Total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(Total.toLocaleString());
                var api1 = this.api();
                api1.columns( 2, {
                    page: 'current'
                }).every(function() {
                    var sum1 = this
                    .data()
                    .reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                    $(this.footer()).html(Math.round(sum1)+"%");
                });
            },
            "initComplete": function(){
                const PieLabels = [];
                const PieColor = [];
                const PieData = [];
                var color = ["#2bcc71","#3b98db","#ff7600","#9ba5a6"];
                for (let index = 0; index < CategoryTable.data().count(); index++) {
                    PieLabels.push(CategoryTable.row(index).data().ProblemCategory);
                    PieData.push(CategoryTable.row(index).data().Total.split(",").join(""));
                    PieColor.push(color[index]);
                }
                console.log(PieColor);
                new Chart($('#pie1Chart'), {
                    type: 'pie',
                    data: {
                        labels: PieLabels,
                        datasets: [
                            {
                                backgroundColor: PieColor,
                                data: PieData,
                            }
                        ]
                    },
                    options: {
                        tooltips: {
                            enabled: true
                        },
                        plugins: {
                            datalabels: {
                            formatter: (value, ctx) => {
                                return (value * 100 / ctx.dataset._meta[0].total).toFixed(2) + "%";
                            },
                            color: '#41e',
                            }
                        },
                        legend:{
                            position: 'right',
                            labels:{
                                fontColor: "black"
                            },
                        },
                        animation: {
                            duration: 3000,
                        },
                    }
                });
            }
        });


    ProblemCatSoftTable =
        $('table.ProblemCatSoftTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": "No data!",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 50,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/softdata',
                data: {
                    datefrom: datefrom,
                    dateto: dateto
                },
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'SubCategory', name:'SubCategory'},
                { data: 'Total', name:'Total'},
                { data: 'percentage', name:'percentage'}
            ],
            "footerCallback": function(row, data, start, end, display) {
               var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                Total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(Total.toLocaleString());
                var api1 = this.api();
                api1.columns( 2, {
                    page: 'current'
                }).every(function() {
                    var sum1 = this
                    .data()
                    .reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                    $(this.footer()).html(Math.round(sum1)+"%");
                });
            }
        });
    ProblemCatHardTable =
        $('table.ProblemCatHardTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": "No data!",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 50,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/harddata',
                data: {
                    datefrom: datefrom,
                    dateto: dateto
                },
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'SubCategory', name:'SubCategory'},
                { data: 'Total', name:'Total'},
                { data: 'percentage', name:'percentage'}
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                Total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(Total.toLocaleString());
                var api1 = this.api();
                api1.columns( 2, {
                    page: 'current'
                }).every(function() {
                    var sum1 = this
                    .data()
                    .reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                    $(this.footer()).html(Math.round(sum1)+"%");
                });
            }
        });
    ProblemCatInfraTable =
        $('table.ProblemCatInfraTable').DataTable({ 
            "dom": 't',
            "language": {
                    "emptyTable": "No data!",
                    // "processing": '<i class="fa fa-spinner fa-spin fa-2x fa-fw"><span class="sr-only">Searching...</span></i>',
                    "loadingRecords": "Please wait - loading..."
                },
            "pageLength": 50,
            "ordering": false,
            processing: false,
            serverSide: false,
            ajax: {
                url: '/infradata',
                data: {
                    datefrom: datefrom,
                    dateto: dateto
                },
                statusCode: {
                    401: function() {
                        location.reload();
                    }
                }
            },
            columns: [
                { data: 'SubCategory', name:'SubCategory'},
                { data: 'Total', name:'Total'},
                { data: 'percentage', name:'percentage'}
            ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(), data;
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                Total = api
                    .column( 1 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 1 ).footer() ).html(Total.toLocaleString());
                var api1 = this.api();
                api1.columns( 2, {
                    page: 'current'
                }).every(function() {
                    var sum1 = this
                    .data()
                    .reduce(function(a, b) {
                        var x = parseFloat(a) || 0;
                        var y = parseFloat(b) || 0;
                        return x + y;
                    }, 0);
                    $(this.footer()).html(Math.round(sum1)+"%");
                });
            }
        });
});