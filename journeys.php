<h1>Journeys</h1>

<div class="col-sm-12">

        <table class="table" id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-show-refresh="true"
               data-show-toggle="true"
               data-show-columns="true"
               data-url="src/list_journeys.php"
               data-show-export="true"
               data-search-on-enter-key="true"
               data-toggle="table"
               data-detail-view="true"
               data-sort-name="id"
               data-sort-order="desc"
               data-minimum-count-columns="2"
               data-show-pagination-switch="true"
               data-pagination="true"
               data-id-field="id"
               data-page-size="10"
               data-page-list="[5, 10, 25, 50, 100, ALL]"
               data-show-footer="false"
               data-side-pagination="server"
               data-icons-prefix="fa"       
               data-icons="icons"
               >
            <thead>
                <tr>
                    <th scope="col" data-field="id" data-sortable="true">Joruney ID</th>
                    <th scope="col" data-field="TRAILER_NAME" data-sortable="true">Trailer Name</th>
                    <th scope="col" data-field="DRIVER_NAME" data-sortable="true">Driver Name</th>
                    <th scope="col" data-field="DISTANCE" data-sortable="true">Distance</th>
                    <th scope="col" data-field="WEIGTH" data-sortable="true">Weigth</th>
                   
                    <th scope="col" data-field="ST"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    var $table = $('#table');
    window.icons = {
        refresh: 'fa-sync-alt',
        toggleOn: 'fa-toggle-on',
        toggleOff: 'fa-toggle-off',
        columns: 'fa-columns',
        paginationSwitchDown: 'fa-chevron-circle-right',
        paginationSwitchUp: 'fa-chevron-right',
        detailOpen: 'fa-plus',
        detailClose: 'fa-minus'
    };
    $(function () {
        $('#topalert').hide();
        var alertContent = ($('#topalertcontent').text());
        if (alertContent.length > 0) {
            $('#topalert').show();
        }
        
        $('#frm-search').on('submit', function(e){
            e.preventDefault();
        });


        $('#btn-search').on('click', function () {
            $table.bootstrapTable('destroy').bootstrapTable({
                queryParams: queryParams,
                method: 'get'
            });

        });

        $table.on('expand-row.bs.table', function (e, index, row, $detail) {
            //$detail.addClass('bg-secondary');
            $.ajax({
                type: "POST",
                url: "journey_details.php",
                dataType: "json",
                data: {journeyid: row['id']}
            }).done(function (msg) {
                var htmlStr = '';
                $.each(msg, function (key, val) {
                    htmlStr += '<p>#'+val.seq+':</p>';
                    htmlStr += '<p>Customer Name: <b>'+val.customer_name+'</b></p>';
                    htmlStr += '<p>Origin: <b>'+val.origin+'</b></p>';
                    htmlStr += '<p>Destination: <b>'+val.destination+'</b></p>';
                    htmlStr += '<p>Distance: <b>'+val.distance+'</b></p>';
                    htmlStr += '<p>Weigth: <b>'+val.weigth+'</b></p>';
                });
                $detail.append(htmlStr);
            });
        });




    });


</script>
