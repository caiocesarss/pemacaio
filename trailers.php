<h1>Trailers</h1>
<div class="col-sm-12">
        <table class="table" id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-show-refresh="true"
               data-show-toggle="true"
               data-show-columns="true"
               data-url="src/list_trailers.php"
               data-show-export="true"
               data-search-on-enter-key="true"
               data-toggle="table"
               data-sort-name="TRAILER_ID"
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
            <thead >
                <tr>
                    <th scope="col" data-field="id" data-sortable="true">Trailer ID</th>
                    <th scope="col" data-field="TRAILER_NAME" data-sortable="true">Trailer Name</th>
                    <th scope="col" data-field="WEIGTH_CAPACITY" data-sortable="true">Capacity</th>
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
       
       
        
        $('#frm-search').on('submit', function(e){
            e.preventDefault();
        });


        $('#btn-search').on('click', function () {
            $table.bootstrapTable('destroy').bootstrapTable({
                queryParams: queryParams,
                method: 'get'
            });

        });

       
    });

</script>
